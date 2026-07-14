<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = '';
$success_message = '';
$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error_message =  "Entre ton adresse email";
    } else {
        try {
            $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
            
            if (!$link) {
                throw new Exception("Échec de la connexion MySQL: " . mysqli_connect_error());
            }

            // Vérifier si l'email existe et n'est pas déjà validé
            $query = "SELECT id, mail_valide FROM Users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            
            if (!$stmt) {
                throw new Exception("Erreur de préparation de la requête: " . mysqli_error($link));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                
                if ($user['mail_valide'] == 1) {
                    $success_message = "Cette adresse email est déjà confirmée. Tu peux te connecter dès maintenant !";
                } else {
                    // Générer un nouveau token
                    $new_token = bin2hex(random_bytes(32));
                    $new_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
                    
                    // Mettre à jour le token dans la base de données
                    $update_query = "UPDATE Users SET validation_token = ?, token_expiration = ? WHERE id = ?";
                    $update_stmt = mysqli_prepare($link, $update_query);
                    
                    if (!$update_stmt) {
                        throw new Exception("Erreur de préparation de la requête de mise à jour: " . mysqli_error($link));
                    }
                    
                    mysqli_stmt_bind_param($update_stmt, "ssi", $new_token, $new_expiration, $user['id']);
                    $update_result = mysqli_stmt_execute($update_stmt);
                    
                    if ($update_result) {
                        // Envoyer le nouvel email de confirmation
                        $confirmation_link = "https://racines.ralaikoa.com/membre/mail_verify.php?token=" . $new_token;
                        
                        $to = $email;
                        $subject = "Nouveau lien de confirmation - Racines";
                        $message = "Bonjour,\n\n";
                        $message .= "Tu as demandé un nouveau lien de confirmation pour ton compte Racines.\n\n";
                        $message .= "Clique sur le lien suivant pour confirmer ton adresse email :\n";
                        $message .= $confirmation_link . "\n\n";
                        $message .= "Ce lien expirera dans 24 heures.\n\n";
                        $message .= "Si tu n'as pas fait cette demande, tu peux ignorer cet email.\n\n";
                        $message .= "Cordialement,\nL'équipe Racines";
                        $headers = "From: no-reply@racines.com";
                        
                        if (mail($to, $subject, $message, $headers)) {
                            $success_message = "Un nouveau lien de confirmation a été envoyé à cette adresse email.";
                        } else {
                            throw new Exception("Erreur lors de l'envoi de l'email");
                        }
                    } else {
                        throw new Exception("Erreur lors de la mise à jour du token");
                    }
                }
            } else {
                $error_message = "Aucun compte trouvé avec cette adresse email";
            }
            
            mysqli_close($link);
        } catch (Exception $e) {
            error_log("ERREUR: " . $e->getMessage());
            $error_message = "Une erreur technique est survenue. Réessaie plus tard.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Renvoyer la confirmation - Racines</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
    .form-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #fff5eb;
    border-radius: 16px;
    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.1),       /* ombre classique */
       /* ombre rouge douce */
    font-family: 'Sora', sans-serif;
}

.form-title {
    text-align: center;
    margin-bottom: 1.5rem;
    color: #bc163a; /* Titre en couleur principale */
    font-size: 1.5rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #7d6852; /* Texte doux et élégant */
}

.form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #a7264f;
    border-radius: 12px;
    font-size: 1rem;
    background-color: #ffffff;
    color: #000000;
    transition: border-color 0.3s;
}

.form-group input:focus {
    border-color: #bc163a;
    outline: none;
}

.submit-btn {
    width: 100%;
    padding: 0.8rem;
    background-color: #bc163a;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #a7264f;
}

.success-message {
    color: #4CAF50;
    margin-bottom: 1.5rem;
    text-align: center;
    font-weight: 500;
}

.error-message {
    color: #f44336;
    margin-bottom: 1.5rem;
    text-align: center;
    font-weight: 500;
}

.info-message {
    color: #7d6852;
    margin-bottom: 1.5rem;
    text-align: center;
    font-size: 0.9rem;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 1rem;
    color: #bc163a;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s;
}

.back-link:hover {
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
    <div class="form-container">
        <h2 class="form-title">Renvoyer le lien de confirmation</h2>
        
        <?php if (isset($_GET['expired'])): ?>
            <div class="info-message">
                Le lien précédent a expiré. Entre ton adresse email pour recevoir un nouveau lien.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <p style="text-align: center;"><a href="membre.php" class="action-button">Se connecter</a></p>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Adresse email :</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <button type="submit" class="submit-btn">Envoyer un nouveau lien</button>
            </form>
            
            <a href="membre.php" class="back-link">Retour à la page de connexion</a>
        <?php endif; ?>
    </div>
</main>


</body>
</html>