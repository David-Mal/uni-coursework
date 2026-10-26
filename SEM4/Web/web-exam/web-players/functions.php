<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'functions.php'; // getDegreeN, getColleagues
requireLogin();
function getDegree1($conn, $currentPlayerID) {
    $stmt = $conn->prepare('
        SELECT DISTINCT p.*
        FROM Player p
        JOIN TeamMembers tm ON (tm.IDplayer1 = p.ID OR tm.IDplayer2 = p.ID)
        WHERE (tm.IDplayer1 = ? OR tm.IDplayer2 = ?)
        AND p.ID != ?
    ');
    $stmt->execute([$currentPlayerID, $currentPlayerID, $currentPlayerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDegree2($conn, $currentPlayerID) {
    $stmt = $conn->prepare('
        SELECT DISTINCT p.*
        FROM Player p
        JOIN TeamMembers tm2 ON (tm2.IDplayer1 = p.ID OR tm2.IDplayer2 = p.ID)
        JOIN TeamMembers tm1 ON (
            (tm1.IDplayer1 = tm2.IDplayer1 OR tm1.IDplayer1 = tm2.IDplayer2 OR
             tm1.IDplayer2 = tm2.IDplayer1 OR tm1.IDplayer2 = tm2.IDplayer2)
        )
        WHERE (tm1.IDplayer1 = ? OR tm1.IDplayer2 = ?)
        AND p.ID != ?
    ');
    $stmt->execute([$currentPlayerID, $currentPlayerID, $currentPlayerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getColleagues($conn, $playerID) {
    $stmt = $conn->prepare('
        SELECT DISTINCT p.ID, p.name, p.position
        FROM Player p
        JOIN TeamMembers tm ON (tm.IDplayer1 = p.ID OR tm.IDplayer2 = p.ID)
        WHERE (tm.IDplayer1 = ? OR tm.IDplayer2 = ?)
        AND p.ID != ?
    ');
    $stmt->execute([$playerID, $playerID, $playerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDegreeN($conn, $startPlayerID, $n) {
    $currentLevel = [$startPlayerID];
    $visited = [$startPlayerID];
    $result = [];

    for ($i = 0; $i < $n; $i++) {
        $nextLevel = [];
        foreach ($currentLevel as $playerID) {
            $colleagues = getColleagues($conn, $playerID);
            foreach ($colleagues as $c) {
                if (!in_array($c['ID'], $visited)) {
                    $visited[] = $c['ID'];
                    $nextLevel[] = $c['ID'];
                    $result[] = $c;
                }
            }
        }
        $currentLevel = $nextLevel;
    }

    return $result;
}
?>