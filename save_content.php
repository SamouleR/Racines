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
    die(json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]));
}

// Récupération des données POST
$videoId = filter_input(INPUT_POST, 'videoId', FILTER_VALIDATE_INT);
$userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
$commentText = filter_input(INPUT_POST, 'commentText', FILTER_SANITIZE_STRING);

// Validation des données
if (!$videoId || !$userName || !$commentText) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

try {
    // Insertion du commentaire
    $stmt = $pdo->prepare("INSERT INTO comments (video_id, user_name, comment_text) VALUES (:video_id, :user_name, :comment_text)");
    $stmt->execute([
        ':video_id' => $videoId,
        ':user_name' => $userName,
        ':comment_text' => $commentText
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Commentaire enregistré']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du commentaire: ' . $e->getMessage()]);
}
?>