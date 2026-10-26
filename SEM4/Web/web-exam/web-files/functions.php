<?php
function getTotalFilesCount($conn, $userId) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Files WHERE userid = :userid");
    $stmt->execute([':userid' => $userId]);
    return (int)$stmt->fetchColumn();
}

function getPaginatedFiles($conn, $userId, $limit, $offset) {
    $stmt = $conn->prepare("SELECT id, filename, filepath, size FROM Files WHERE userid = :userid LIMIT :limit OFFSET :offset");
    
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}