<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (session_status() !== PHP_SESSION_ACTIVE) {
    die('Les sessions ne fonctionnent pas');
}

// Initialisation de la variable en dehors du bloc POST
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Vérification que les champs existent avant de les utiliser
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Connexion DB
            $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");

            if (!$link) {
                throw new Exception("Erreur de connexion à la base de données.");
            }

           // Vérifier si l'admin existe
            $query = "SELECT * FROM admin WHERE username = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            if (!$stmt) {
                throw new Exception("Erreur de préparation de requête: " . mysqli_error($link));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Erreur d'exécution de requête: " . mysqli_stmt_error($stmt));
            }
            
            $result = mysqli_stmt_get_result($stmt);
            if (!$result) {
                throw new Exception("Erreur d'obtention des résultats: " . mysqli_error($link));
            }
            
            if ($admin = mysqli_fetch_assoc($result)) {
                if ($password === $admin['password']) {  
                    // Connexion réussie
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_logged_in'] = true;
                    header("Location: /admin/admin_dashboard.php?id=".$admin['id']);
                    exit();
                
                } else {
                    $error_message = "Identifiants incorrects."; 
                }
            } else {
                $error_message = "Identifiants incorrects."; 
            }

            mysqli_close($link);
        } catch (Exception $e) {
            error_log("Erreur connexion: " . $e->getMessage());
            $error_message = "Erreur technique";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin - Racines</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            color: #bc163a;
        }
        main {
            align-items: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .titre {
            color: #bc163a;    
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-logo img {
            max-width: 150px;
        }
        
        .error {
            color: #dc3545;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
        <h1 class='titre'>Connexion administrateur</h1>
        <div class="form-container">
            <div class="login-logo">
                <img src="/img/racines-logo.png" alt="Logo Racines">
            </div>
            
            <?php if (!empty($error_message)): ?>
                <p class="error"><?= htmlspecialchars($error_message, ENT_QUOTES) ?></p>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="field">
                    <label>Nom d'utilisateur :</label>
                    <input type="text" name="username" required value="<?= htmlspecialchars($username ?? '', ENT_QUOTES) ?>">
                </div>
                <div class="field">
                    <label>Mot de passe :</label>
                    <input type="password" name="password" required>
                </div>
                <input type="submit" name="submit" value="Connexion">
            </form>
        </div>
    </main>
</body>
</html>
