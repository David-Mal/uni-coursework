<!DOCTYPE html>
<html>
<head>
    <title>News Service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; }
        .filters { background: #f4f4f4; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .news-item { border-bottom: 1px solid #ccc; width: 600px; padding: 10px; }
        #last-filter { color: #666; font-style: italic; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Latest News</h1>
        <div class="top-nav">
            <a href="about.php" class="nav-link">About</a>
            <a href="login.php" class="nav-link admin">Admin Login</a>
        </div>
    </div>

    <div class="filters">
        <input type="date" id="start">
        <input type="date" id="end">
        <select id="category">
            <option value="">All Categories</option>
            <option value="politics">Politics</option>
            <option value="society">Society</option>
            <option value="health">Health</option>
            <option value="sports">Sports</option>
            <option value="weather">Weather</option>
        </select>
        <button onclick="loadNews()">Filter</button>
    </div>

    <div id="last-filter"></div>
    <div id="news-container"></div>

    <script>
        function loadNews() {
            const cat = document.getElementById('category').value;
            const start = document.getElementById('start').value;
            const end = document.getElementById('end').value;

            const filterText = `Showing: ${cat || 'All'} from ${start || 'Any'} to ${end || 'Any'}`;
            document.getElementById('last-filter').innerText = "Previous Filter: " + filterText;
            localStorage.setItem('prevFilter', filterText);

            fetch(`fetch_news.php?category=${cat}&start=${start}&end=${end}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    data.forEach(item => {
                        html += `<div class="news-item">
                        <h3><a href="view_article.php?id=${item.id}">${item.title}</a> (${item.category})</h3>
                        <p>${item.text}</p>
                        <small>${item.producer} - ${item.date}</small>
                    </div>`;
                    });
                    document.getElementById('news-container').innerHTML = html;
                });
        }

        window.onload = () => {
            document.getElementById('last-filter').innerText = localStorage.getItem('prevFilter') || "No previous filters";
            loadNews();
        };
    </script>
</body>
</html>