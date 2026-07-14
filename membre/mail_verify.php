<?php
session_start();

// Récupérer le token depuis l'URL
$token = $_GET['token'] ?? null;

if ($token) {
    try {
        $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
        
        if (!$link) {
            throw new Exception("Échec de la connexion MySQL: " . mysqli_connect_error());
        }

        // Vérifier si le token est valide et non expiré
        $query = "SELECT id, email, mail_valide, token_expiration FROM Users WHERE validation_token = ? LIMIT 1";
        $stmt = mysqli_prepare($link, $query);
        
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête: " . mysqli_error($link));
        }
        
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Vérifier si l'email n'est pas déjà validé
            if ($user['mail_valide'] == 1) {
                $success_message = "Cette adresse email est déjà confirmée !";
                header("Location: compte_confirme.php?already_verified=1");
                exit();
            }

            // Vérifier si le token a expiré
            if (strtotime($user['token_expiration']) < time()) {
                // Si le token a expiré
                $error_message = "Le lien de confirmation a expiré. Tu dois demander un nouveau lien.";
                header("Location: renvoyer_verification.php?expired=1&email=" . urlencode($user['email']));
                exit();
            }
            
            // Mettre à jour le statut de l'utilisateur
            $update_query = "UPDATE Users SET mail_valide = 1, validation_token = NULL, token_expiration = NULL WHERE id = ?";
            $update_stmt = mysqli_prepare($link, $update_query);
            
            if (!$update_stmt) {
                throw new Exception("Erreur de préparation de la requête de mise à jour: " . mysqli_error($link));
            }
            
            mysqli_stmt_bind_param($update_stmt, "i", $user['id']);
            $update_result = mysqli_stmt_execute($update_stmt);
            
            if ($update_result) {
                // Mettre à jour la session si l'utilisateur est le même
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) {
                    $_SESSION['email_verified'] = true;
                    $_SESSION['member_logged_in'] = true;
                }
                
                // Envoyer un email de confirmation
                $to = $user['email'];
                $subject = "Confirmation de ton adresse email - Racines";
                $message = "Bonjour,\n\Ton adresse email a été confirmée avec succès.\n\n";
                $message .= "Tu peux maintenant profiter de toutes les fonctionnalités de ton compte.\n\n";
                $message .= "Cordialement,\nL'équipe Racines";
                $headers = "From: no-reply@racines.com";
                
                mail($to, $subject, $message, $headers);
                
                header("Location: compte_confirme.php?success=1");
                exit();
            } else {
                throw new Exception("Erreur lors de la validation de l'email");
            }
        } else {
            // Le token n'existe pas ou est invalide
            $error_message = "Lien de confirmation invalide. Tu dois demander un nouveau lien.";
            header("Location: renvoyer_verification.php?invalid=1");
            exit();
        }
        
        mysqli_close($link);
    } catch (Exception $e) {
        error_log("ERREUR: " . $e->getMessage());
        $error_message = "Une erreur technique est survenue. Réessaie plus tard.";
    }
} else {
    $error_message = "Token de validation manquant dans l'URL";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Confirmation d'email - Racines</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
  .form-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #fff5eb;
    border-radius: 1.5rem;
    box-shadow:
        0 4px 20px rgba(0, 0, 0, 0.1),        /* ombre classique */
    /* ombre rouge douce */
    text-align: center;
    font-family: 'Sora', sans-serif;
    color: #000000;
}

.success-message {
    color: #4CAF50;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
}

.error-message {
    color: #bc163a;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
}

.action-button {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.9rem 1.7rem;
    background-color: #a7264f;
    color: #ffffff;
    text-decoration: none;
    border-radius: 2rem;
    transition: background-color 0.3s, transform 0.2s;
    font-weight: 600;
}

.action-button:hover {
    background-color: #bc163a;
    transform: translateY(-2px);
}

body {
    background-color: #fff5eb;
    font-family: 'Sora', sans-serif;
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
    <div class="form-container">
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <p>Tu peux demander un nouveau lien de confirmation en cliquant ci-dessous :</p>
            <a href="renvoyer_verification.php" class="action-button">Renvoyer un lien de confirmation</a>
        <?php elseif (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <p>Tu peux maintenant accéder à ton compte :</p>
            <a href="membre.php" class="action-button">Se connecter</a>
        <?php endif; ?>
    </div>
</main>


</body>
</html>