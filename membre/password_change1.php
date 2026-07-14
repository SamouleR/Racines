<?php
session_start(); // Toujours commencer la session AVANT toute sortie HTML

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Configuration de la base de données
    $servername = "localhost";
    $username = "u237218091_racine";
    $password = "racineSSJJ1234";
    $dbname = "u237218091_racine";

    try {
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer et nettoyer l'email du formulaire
        $email = trim($_POST['email']);

        // Vérifier si l'email existe dans la table Users
        $stmt = $conn->prepare("SELECT id FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // L'utilisateur existe, récupérer son id
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $user['id'];

            // Générer un token sécurisé et expiration dans 24h
            $password_token = bin2hex(random_bytes(32));
            $password_token_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));

            // Mettre à jour la base avec le token et l'expiration
            $update_query = "UPDATE Users SET password_token = :token, password_token_expiration = :expiration WHERE id = :id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':token', $password_token);
            $update_stmt->bindParam(':expiration', $password_token_expiration);
            $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                // Préparer et envoyer l'email de réinitialisation
                $reset_link = "https://racines.ralaikoa.com/membre/password_change.php?token=" . $password_token;
                
                // Contenu HTML de l'email
                $to = $email;
                $subject = "Réinitialisation de ton mot de passe Racines";
                
                $message = '
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Réinitialisation de mot de passe</title>
                    <style>
                        body {
                            font-family: "Sora", Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                            margin: 0;
                            padding: 0;
                            background-color: #FFF5EB;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: white;
                            border-radius: 10px;
                            box-shadow: 0 0 20px rgba(167, 38, 66, 0.1);
                        }
                        .header {
                            text-align: center;
                            padding: 20px 0;
                            border-bottom: 1px solid #BC163A;
                        }
                        .logo {
                            max-width: 200px;
                            margin-bottom: 20px;
                        }
                        .content {
                            padding: 20px;
                        }
                        h1 {
                            color: #fff5eb;
                            font-size: 24px;
                            margin-bottom: 20px;
                        }
                        p {
                            margin-bottom: 20px;
                            font-size: 16px;
                        }
                        .button {
                            display: inline-block;
                            padding: 12px 24px;
                            background-color: #BC163A;
                            color: white !important;
                            text-decoration: none;
                            border-radius: 25px;
                            font-weight: bold;
                            margin: 20px 0;
                        }
                        .footer {
                            text-align: center;
                            padding: 20px;
                            font-size: 14px;
                            color: #666;
                            border-top: 1px solid #BC163A;
                        }
                        .illustration {
                            width: 100%;
                            max-height: 200px;
                            object-fit: cover;
                            border-radius: 8px;
                            margin: 20px 0;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <img src="https://racines.ralaikoa.com/img/racines-logo.png" alt="Logo Racines" class="logo">
                        </div>
                        <div class="content">
                            <h1>Réinitialisation de ton mot de passe</h1>
                            <p>Bonjour,</p>
                            <p>Tu as demandé à réinitialiser ton mot de passe pour ton compte Racines. Clique sur le bouton ci-dessous pour continuer :</p>
                            
                            <img src="https://racines.ralaikoa.com/img/racines-logo.png" alt="Réinitialisation de mot de passe" class="illustration">
                            
                            <p style="text-align: center;">
                                <a href="'.$reset_link.'" class="button">Réinitialiser mon mot de passe</a>
                            </p>
                            
                            <p>Si tu ne peux pas cliquer sur le bouton, copie et colle le lien suivant dans ton navigateur :</p>
                            <p><small>'.$reset_link.'</small></p>
                            
                            <p>Si tu n\'as pas fait cette demande, tu peux ignorer cet email en toute sécurité.</p>
                            <p>Ce lien expirera dans 24 heures.</p>
                        </div>
                        <div class="footer">
                            <p>L\'équipe Racines</p>
                            <p><small>© '.date('Y').' Racines - Tous droits réservés</small></p>
                        </div>
                    </div>
                </body>
                </html>
                ';
                
                // En-têtes pour l'email HTML
                $headers = "From: Racines <contact@racines.ralaikoa.com>\r\n";
                $headers .= "Reply-To: contact@racines.ralaikoa.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    $_SESSION['message'] = "Si cette adresse email est associée à un compte, tu recevras un lien de réinitialisation.";
                } else {
                    $_SESSION['message'] = "Une erreur est survenue lors de l'envoi de l'email.";
                }
            } else {
                $_SESSION['message'] = "Une erreur est survenue lors de la mise à jour du token.";
            }
        } else {
            // Même message si l'utilisateur n'existe pas
            $_SESSION['message'] = "Si cette adresse email est associée à un compte, tu recevras un lien de réinitialisation.";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;

    // Redirection pour éviter le renvoi du formulaire au rafraîchissement
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<meta charset='utf-8'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/style_form.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
<head>
    <title>Mot de passe oublié - Racines</title>
</head>

<style>
    body {
    background-color: #FFF5EB;
    font-family: 'Sora', sans-serif;
    margin: 0;
    padding: 0;
}

.titre {
    color: #000000;
    font-size: 2em;
    font-weight: 600;
    margin-top: 40px;
    text-align: center;
}

/* Texte explicatif */
main p {
    text-align: center;
    color: #333;
    margin: 10px 0 30px;
}

/* Bloc formulaire */
.form-container {
    max-width: 400px;
    margin: 0 auto 50px;
    background: rgba(255, 255, 255, 0.75);
    padding: 30px;
    border-radius: 2rem;
    box-shadow: 0 8px 24px rgba(167, 38, 66, 0.6); /* Ombre rouge */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s ease-in-out;
}

label {
    font-size: 1.1em;
    font-weight: 500;
    color: #A72642;
}

input[type="email"],
input[type="submit"] {
    width: 100%;
    padding: 12px;
    margin: 5px 0 15px;
    border: 1px solid #ccc;
    border-radius: 1rem;
    font-size: 1em;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #7D6852;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

input[type="submit"]:hover {
    background-color: #5e4f41;
}

/* Notification */
.notification {
    background-color: #fff1f1;
    color: #A72642;
    padding: 15px;
    margin: 20px auto;
    max-width: 500px;
    border-radius: 1rem;
    text-align: center;
    font-weight: 500;
}

/* Trait rouge */
hr {
    border: none;
    border-top: 1px solid #A72642;
    margin: 20px 0;
}

/* Aide */
.help {
    text-align: center;
    margin-top: 20px;
}

.help p {
    color: #A72642;
    font-weight: 500;
}

.help a {
    color: #A72642;
    font-weight: 600;
    text-decoration: none;
}

.help a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 480px) {
    .form-container {
        margin: 20px;
        padding: 20px;
    }
}

</style>

<body>
    <!--header-->
    <div id="header-placeholder"></div>
    
  
    <main>
        <h1 class="titre">Renseigne ton adresse mail</h1>
        <p>Nous enverrons un mail à cette adresse pour que tu puisses réinitialiser ton mot de passe</p>
        
         <?php if (isset($message)): ?>
            <div class="notification"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="form-container" id="form-change">
            <?php
                session_start();
                if (isset($_SESSION['message'])) {
                    echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
                    unset($_SESSION['message']); // Supprimer le message après l'avoir affiché
                }
            ?>

            <form method="post" action="">
                <div class="field">
                    <input type="email" name="email" id="email" placeholder="Ton adresse mail" value="<?php echo $_SESSION['email']; ?>" required>
                </div>
                
                <input type="submit" name="submit" value="Valider">
            </form>
            
            <hr>
            
            <div class="help">
                <p>Tu te rappelles de ton mot de passe ? <a href="membre.php">Connecte toi ici</a></p>
                <p>Tu n'as pas encore créé un compte ? </br><a href="creer-compte.php">Crée un compte ici</a></p>
            </div>
        </div>
    </main>
    <div id="footer-placeholder"></div>
</body>
  <script>
      fetch("/structure/header.php")
        .then(response => response.text())
        .then(data => {
          document.getElementById("header-placeholder").innerHTML = data;
        });
         fetch("/structure/footer.php")
        .then(response => response.text())
        .then(data => {
          document.getElementById("footer-placeholder").innerHTML = data;
        });
    </script>
    
</html>