<?php
session_start();

// Connexion à la base de données avec MySQLi
$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");

// Vérification de la connexion
if (!$link) {
    error_log("Erreur de connexion MySQLi: " . mysqli_connect_error());
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.");
}

// Vérifier si l'utilisateur est connecté (en utilisant user_id ou id comme dans le code de connexion)
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_SESSION['id']) ? $_SESSION['id'] : null);

if ($userId) {
    // Sécurisation de l'ID
    $userId = intval($userId);
    
    // Vérification que l'ID est valide
    if ($userId <= 0) {
        die("ID utilisateur invalide.");
    }

    // Requête préparée pour plus de sécurité
    $query = "DELETE FROM Users WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            // Suppression réussie, on détruit la session
            session_unset();
            session_destroy();
            // Redirection
            header('Location: /membre/membre.php');
            exit();
        } else {
            // Erreur SQL
            error_log("Erreur lors de la suppression de l'utilisateur : " . mysqli_error($link));
            echo "Une erreur est survenue lors de la suppression du compte.";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        error_log("Erreur de préparation de la requête : " . mysqli_error($link));
        echo "Une erreur est survenue lors de la préparation de la requête.";
    }
} else {
    echo "Aucun utilisateur connecté ou ID manquant.";
}

// Fermer la connexion
mysqli_close($link);
?>