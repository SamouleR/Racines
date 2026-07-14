<?php
header('Content-Type: application/json');

// Configuration de la base de données
$host = 'localhost';
$dbname = 'u237218091_racine';
$username = 'u237218091_racine';
$password = 'RacineSSJJ1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]));
}

// Récupération de l'ID de la vidéo
$videoId = filter_input(INPUT_GET, 'videoId', FILTER_VALIDATE_INT);

if (!$videoId) {
    echo json_encode([]);
    exit;
}

try {
    // Récupération des commentaires
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE video_id = :video_id ORDER BY created_at DESC");
    $stmt->execute([':video_id' => $videoId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($comments);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des commentaires: ' . $e->getMessage()]);
}
?>