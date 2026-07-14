<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$user = null;
$errorMessage = '';
$successMessage = '';
$token = $_GET['token'] ?? $_POST['password_token'] ?? null;

if ($token) {
    try {
        $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");

        if (!$link) {
            throw new Exception("Échec de la connexion MySQL: " . mysqli_connect_error());
        }

        // === Si GET : Afficher le formulaire après vérification du token
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $query = "SELECT id FROM Users WHERE password_token = ? AND password_token_expiration > NOW() LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result); // Affichage autorisé
            } else {
                $errorMessage = "Le lien de réinitialisation est invalide ou expiré.";
            }
        }

        // === Si POST : Traitement du changement de mot de passe
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $password_check = $_POST['password-check'] ?? '';

            // Validation mot de passe (même logique que l’inscription)
            if ($password !== $password_check) {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($password) < 8 || 
                      !preg_match('/[A-Z]/', $password) ||
                      !preg_match('/[0-9]/', $password) ||
                      !preg_match('/[^a-zA-Z0-9]/', $password)) {
                $errorMessage = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial.";
            } else {
                // Vérifier à nouveau le token avant mise à jour
                $query = "SELECT id FROM Users WHERE password_token = ? AND password_token_expiration > NOW() LIMIT 1";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "s", $token);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) === 1) {
                    $user = mysqli_fetch_assoc($result);
                    $userId = $user['id'];

                    // Hash du nouveau mot de passe
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Mise à jour dans la base
                    $update = "UPDATE Users SET password = ?, password_token = NULL, password_token_expiration = NULL WHERE id = ?";
                    $stmtUpdate = mysqli_prepare($link, $update);
                    mysqli_stmt_bind_param($stmtUpdate, "si", $hashed_password, $userId);
                    mysqli_stmt_execute($stmtUpdate);

                    // Redirection après succès
                    header("Location: /membre/membre.php");
                    exit();
                } else {
                    $errorMessage = "Le lien de réinitialisation est invalide ou expiré.";
                }
            }
        }

    } catch (Exception $e) {
        $errorMessage = "Erreur technique : " . $e->getMessage();
    }
} else {
    $errorMessage = "Aucun token fourni dans l'URL.";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>

    <!-- Fonts & Styles -->
    <link rel="stylesheet" href="/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        function validateForm() {
            const clicked = document.getElementById('clickedButton').value;
            const step = document.getElementById('form-step').value;
    
            // Ne valider les mots de passe QUE si on est à l'étape 2 ET qu'on clique sur "Continuer"
            if (step == 2 && clicked === 'submit_step2') {
                return validatePassword();
            }
    
            // Sinon, pas de validation JS
            return true;
        }
    
        function validatePassword() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("password-check").value;
            const errorElement = document.getElementById("password-error");
    
            if (password !== confirmPassword) {
                errorElement.textContent = "Les mots de passe ne correspondent pas";
                return false;
            }
    
            const hasUpperCase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecialChar = /[^a-zA-Z0-9]/.test(password);
    
            if (password.length < 8) {
                errorElement.textContent = "Le mot de passe doit contenir au moins 8 caractères";
                return false;
            }
    
            if (!hasUpperCase || !hasNumber || !hasSpecialChar) {
                errorElement.textContent = "Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial";
                return false;
            }
    
            errorElement.textContent = "";
            return true;
        }
    
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
    </script>
</head>

<body>
<?php if ($user): ?>
    <form class="form-container" method="POST" onsubmit="return validateForm();">
        <input type="hidden" name="password_token" value="<?= htmlspecialchars($token) ?>">

        <div class="field">
            <label for="password">Nouveau mot de passe :</label>
             <div class="password-container">
                <input type="password" name="password" id="password" required oninput="validatePassword()" value="">
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', 'password-icon')">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="field">
            <label for="password-check">Confirme le mot de passe :</label>
            <div class="password-container">
                <input type="password" name="password-check" id="password-check" required oninput="validatePassword()" value="">
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-check', 'password-check-icon')">
                    <i id="password-check-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="password-error" class="error-message" style="color:red;"></div>
        </div>
        
        <small>Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial</small>
        
        <div class="field">
            <input type="submit" value="Changer le mot de passe">
        </div>

        <?php if ($errorMessage): ?>
            <div style="color:red"><?= $errorMessage ?></div>
        <?php endif; ?>
    </form>
                
<?php else: ?>
    <p style="color:red"><?= $errorMessage ?: "Lien invalide ou expiré." ?></p>
<?php endif; ?>

</body>


</html>