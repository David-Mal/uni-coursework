<?php
require_once 'auth.php';
require_once 'db.php';

requireLogin();

$freshLogin = !empty($_SESSION['fresh_login']);
if ($freshLogin) {
    unset($_SESSION['fresh_login']);
}

$topicScores = $_SESSION['topic_scores'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercises</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 32px auto; padding: 0 16px; }
        nav { margin-bottom: 24px; }
        .score-map { background: #f5f5f5; padding: 16px; border-radius: 6px; margin-bottom: 28px; }
        .score-map h3 { margin: 0 0 10px; }
        .score-entry { display: flex; justify-content: space-between; max-width: 280px; padding: 3px 0; }
        #exerciseArea { border: 1px solid #ddd; border-radius: 6px; padding: 20px; min-height: 120px; }
        #exerciseArea p { font-size: 1.1em; margin: 12px 0; }
        .meta { color: #555; font-size: 0.9em; }
        #answerInput { padding: 8px; width: 60%; font-size: 1em; }
        #submitBtn, #startBtn { padding: 8px 18px; font-size: 1em; margin-left: 8px; cursor: pointer; }
        #resultMsg { margin-top: 12px; font-weight: bold; font-size: 1em; }
        .correct   { color: #1b5e20; }
        .incorrect { color: #b71c1c; }
        em { color: #888; }
        #masteryBanner {
            display: none;
            position: fixed; top: 24px; left: 50%; transform: translateX(-50%);
            background: #1b5e20; color: #fff;
            padding: 14px 28px; border-radius: 8px;
            font-size: 1.1em; font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideDown 0.3s ease;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <nav><a href="history.php">My History</a> &nbsp;|&nbsp; <a href="logout.php">Logout</a></nav>

    <div class="score-map">
        <h3>Topic Scores</h3>
        <div id="scoreDisplay"><em>No history yet.</em></div>
    </div>

    <div id="masteryBanner"></div>

    <div id="exerciseArea">
        <button id="startBtn" onclick="fetchNextExercise()">Start</button>
    </div>

    <script>
    const freshLogin  = <?php echo $freshLogin ? 'true' : 'false'; ?>;
    const serverScores = <?php echo json_encode($topicScores, JSON_FORCE_OBJECT); ?>;

    let topicScores;
    if (freshLogin || !sessionStorage.getItem('topicScores')) {
        topicScores = serverScores;
        sessionStorage.setItem('topicScores', JSON.stringify(topicScores));
    } else {
        topicScores = JSON.parse(sessionStorage.getItem('topicScores'));
    }

    let masteredTopics = new Set(
        freshLogin ? [] : JSON.parse(sessionStorage.getItem('masteredTopics') || '[]')
    );
    if (freshLogin) sessionStorage.setItem('masteredTopics', '[]');

    let masteryTimer = null;

    function showMasteryBanner(topic) {
        const banner = document.getElementById('masteryBanner');
        banner.textContent = `You mastered ${topic}!`;
        banner.style.display = 'block';
        clearTimeout(masteryTimer);
        masteryTimer = setTimeout(() => { banner.style.display = 'none'; }, 3000);
    }

    let currentExercise = null;

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function renderScores() {
        const div = document.getElementById('scoreDisplay');
        const entries = Object.entries(topicScores);
        if (entries.length === 0) {
            div.innerHTML = '<em>No history yet.</em>';
            return;
        }
        div.innerHTML = entries
            .sort((a, b) => a[1] - b[1])
            .map(([topic, score]) =>
                `<div class="score-entry">
                    <span>${escapeHtml(topic)}</span>
                    <span>${score.toFixed(1)} / 100</span>
                </div>`)
            .join('');
    }

    function applyScoreUpdate(topic, correct) {
        const old = topicScores[topic] ?? 0;
        const updated = correct ? old + 10 : old - old * 0.8;
        const clamped = Math.max(0, Math.min(100, updated));
        topicScores[topic] = clamped;
        sessionStorage.setItem('topicScores', JSON.stringify(topicScores));
        renderScores();

        if (clamped >= 80 && old < 80 && !masteredTopics.has(topic)) {
            masteredTopics.add(topic);
            sessionStorage.setItem('masteredTopics', JSON.stringify([...masteredTopics]));
            showMasteryBanner(topic);
        }
    }

    async function fetchNextExercise() {
        const area = document.getElementById('exerciseArea');
        area.innerHTML = '<em>Loading…</em>';
        currentExercise = null;

        try {
            const resp = await fetch('next_exercise.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ topicScores })
            });
            const data = await resp.json();

            if (data.done) {
                area.innerHTML = '<p>All available exercises completed. Come back later!</p>';
                return;
            }

            currentExercise = data;

            area.innerHTML = `
                <div class="meta">
                    Topic: <strong>${escapeHtml(data.topic)}</strong>
                    &nbsp;|&nbsp; Difficulty: <strong>${data.difficulty}</strong>
                </div>
                <p>${escapeHtml(data.prompt)}</p>
                <input type="text" id="answerInput" placeholder="Your answer…">
                <button id="submitBtn" onclick="submitCurrentAnswer()">Submit</button>
                <div id="resultMsg"></div>
            `;
            document.getElementById('answerInput').focus();
            document.getElementById('answerInput')
                .addEventListener('keydown', e => { if (e.key === 'Enter') submitCurrentAnswer(); });
        } catch (e) {
            area.innerHTML = '<em>Network error — please refresh.</em>';
        }
    }

    async function submitCurrentAnswer() {
        if (!currentExercise) return;

        const input     = document.getElementById('answerInput');
        const resultMsg = document.getElementById('resultMsg');
        const submitBtn = document.getElementById('submitBtn');
        const answer    = input.value.trim();

        if (!answer) return;

        submitBtn.disabled = true;
        input.disabled     = true;

        try {
            const resp = await fetch('submit_answer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ exerciseId: parseInt(currentExercise.id), givenAnswer: answer })
            });
            const data = await resp.json();

            if (data.error) {
                resultMsg.textContent = 'Error: ' + data.error;
                resultMsg.className   = 'incorrect';
                submitBtn.disabled    = false;
                input.disabled        = false;
                return;
            }

            resultMsg.textContent = data.correct ? 'Correct!' : 'Incorrect.';
            resultMsg.className   = data.correct ? 'correct' : 'incorrect';

            applyScoreUpdate(currentExercise.topic, data.correct);

            setTimeout(fetchNextExercise, 1500);
        } catch (e) {
            resultMsg.textContent = 'Network error.';
            resultMsg.className   = 'incorrect';
            submitBtn.disabled    = false;
            input.disabled        = false;
        }
    }

    renderScores();
    </script>
</body>
</html>
