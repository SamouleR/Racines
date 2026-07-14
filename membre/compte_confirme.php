<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Messages possibles selon les paramètres GET
$already_verified = isset($_GET['already_verified']);
$success = isset($_GET['success']);

// Déterminer le message à afficher
if ($already_verified) {
    $title = "Email déjà confirmé";
    $message = "Ton adresse email est déjà confirmée !";
    $submessage = "Tu peux profiter pleinement de ton compte.";
} elseif ($success) {
    $title = "Confirmation réussie";
    $message = "Félicitations, ton adresse email a été confirmée avec succès !";
    $submessage = "Tu peux maintenant accéder à toutes les fonctionnalités de ton compte.";
} else {
    // Redirection si aucun paramètre valide
    header("Location: membre.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo htmlspecialchars($title); ?> - Racines</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
   .confirmation-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    text-align: center;
    background-color: #fff5eb;
    border-radius: 1.5rem;
    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.1),        /* ombre classique */
        0 0 0 6px rgba(188, 22, 58, 0.1);     /* ombre rouge douce */
    font-family: 'Sora', sans-serif;
    color:black;
}



.confirmation-icon {
    font-size: 4rem;
    color: #bc163a; /* rouge principal */
    margin-bottom: 1.5rem;
}

.confirmation-title {
    font-size: 1.8rem;
    color: #bc163a;
    margin-bottom: 1rem;
    font-weight: 600;
}

.confirmation-message {
    font-size: 1.2rem;
    color: #7d6852;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.confirmation-submessage {
    font-size: 1rem;
    color: #7d6852;
    margin-bottom: 2rem;
}

.action-button {
    display: inline-block;
    padding: 0.9rem 1.7rem;
    background-color: #a7264f;
    color: #ffffff;
    text-decoration: none;
    border-radius: 2rem;
    transition: background-color 0.3s, transform 0.2s;
    font-size: 1rem;
    font-weight: 600;
}

.action-button:hover {
    background-color: #bc163a;
    transform: translateY(-2px);
}

.secondary-action {
    display: block;
    margin-top: 1rem;
    color: #bc163a;
    text-decoration: none;
    font-weight: 500;
}

.secondary-action:hover {
    text-decoration: underline;
    color: #a7264f;
}

    </style>
</head>
<body>
<!--header-->
<div id="header-placeholder"></div>

<script>
  fetch("/structure/header.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("header-placeholder").innerHTML = data;
    });
</script>

<main>
    <div class="confirmation-container">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="confirmation-title"><?php echo htmlspecialchars($title); ?></h1>
        <p class="confirmation-message"><?php echo htmlspecialchars($message); ?></p>
        <p class="confirmation-submessage"><?php echo htmlspecialchars($submessage); ?></p>
        
        <?php if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in']): ?>
            <a href="member_dashboard.php" class="action-button">Accéder à mon compte</a>
        <?php else: ?>
            <a href="membre.php" class="action-button">Se connecter</a>
            <a href="/" class="secondary-action">Retour à l'accueil</a>
        <?php endif; ?>
    </div>
</main>

<!--footer-->
<div id="footer-placeholder"></div>

<script>
  fetch("/structure/footer.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("footer-placeholder").innerHTML = data;
    });
</script>
</body>
</html>