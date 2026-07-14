<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Connexion DB
            $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");

            if (!$link) {
                throw new Exception("Erreur de connexion à la base de données.");
            }

            // Vérifier si l'utilisateur existe
            $query = "SELECT id, prenom, nom, email, telephone, password FROM Users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                // Debug: Afficher le hash stocké (à enlever en production)
                error_log("Hash stocké: " . $user['password']);
                
                if (isset($user['password'])) {
                    if (password_verify($password, $user['password'])) {
                        // Connexion réussie
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['prenom'] = $user['prenom'];
                        $_SESSION['nom'] = $user['nom'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['telephone'] = $user['telephone'];
                        $_SESSION['member_logged_in'] = true;

                        header("Location: /membre/member_dashboard.php?id=".$_SESSION['user_id']);
                        exit();
                    } else {
                        // Debug: Vérifier le mot de passe en clair (temporaire)
                        error_log("Mot de passe saisi: " . $password);
                        error_log("Hash généré: " . password_hash($password, PASSWORD_DEFAULT));
                        
                        $error_message = "Mot de passe incorrect. Vérifie ta saisie.";
                    }
                } else {
                    $error_message = "Erreur de configuration du compte. Contacte l'administrateur.";
                }
            } else {
                $error_message = "Aucun compte trouvé avec cette adresse mail.";
            }

            mysqli_close($link);
        } catch (Exception $e) {
            error_log("Erreur connexion: " . $e->getMessage());
            $error_message = "Erreur technique - Réessaie plus tard.";
        }
    }
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
    <title>Connexion membre - Racines</title>
    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</head>
<style>
/* Champs de formulaire */
input[type="email"],
input[type="password"],
input[type="submit"] {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 1rem;
    font-size: 1em;
    box-sizing: border-box;
}

/* Bouton de connexion */
input[type="submit"] {
    background-color: #7D6852;
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.2s ease;
}

input[type="submit"]:hover {
    background-color: #5e4f41;
}

/* Container mot de passe avec l'icône */
.password-container {
    position: relative;
    display: flex;
    align-items: center;
}

.password-container input {
    flex: 1;
    padding-right: 40px;
}

/* Bouton œil */
.toggle-password {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    padding: 5px;
}

.toggle-password:hover {
    color: #333;
}

.toggle-password:focus {
    outline: none;
}

/* Message d'erreur */
.error-message {
    color: #a10000;
    background: #ffe5e5;
    padding: 10px;
    border-radius: 1rem;
    margin-bottom: 15px;
    font-weight: bold;
    text-align: center;
}

/* Responsive */
@media (max-width: 480px) {
    .form-container {
        margin: 30px 20px;
        padding: 20px;
    }
}
</style>

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
    
    <div class="contenu">
        <main>
            <h1 class="titre">Connecte toi à ton compte membre</h1>
            <div class="form-container" id="form-contact">
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form method="post" action="membre.php">
                    <div class="field">
                        <label for="email">Adresse mail :</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    
                    <div class="field">
                        <label for="password">Mot de passe :</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', 'password-icon')">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <input type="submit" name="submit" value="Se connecter">
                </form>
                
                <hr>
                
                <div class="help">
                    <ul>
                        <li><a href="password_change1.php">Mot de passe oublié ?</a></li>
                    </ul>
                    <p>Tu n'as pas de compte ? <a href="creer-compte.php">Inscris-toi ici</a></p>
                </div>
            </div>
        </main>
    </div>
        
</body>
</html>