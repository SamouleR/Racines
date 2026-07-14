<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error_message = "";
$success_message = "";
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

// Initialiser les variables à partir de la session ou valeurs vides
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';
$email = $_SESSION['email'] ?? '';
$telephone = $_SESSION['telephone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mettre à jour les variables depuis POST si présentes
    if (isset($_POST['fname'])) {
        $prenom = trim($_POST['fname']);
        $_SESSION['prenom'] = $prenom;
    }
    if (isset($_POST['name'])) {
        $nom = trim($_POST['name']);
        $_SESSION['nom'] = $nom;
    }
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $_SESSION['email'] = $email;
    }
    if (isset($_POST['telephone'])) {
        $telephone = trim($_POST['telephone']);
        $_SESSION['telephone'] = $telephone;
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        $_SESSION['password'] = $password;
    }
    if (isset($_POST['password-check'])) {
        $password_check = $_POST['password-check'];
        $_SESSION['password_check'] = $password_check;
    }

    if (isset($_POST['submit_step1'])) {
        if (empty($prenom) || empty($nom) || empty($email)) {
            $error_message = "Tous les champs sont obligatoires";
            $step = 1;
        } else {
            $step = 2;
        }
    } elseif (isset($_POST['submit_step2'])) {
        if ($password !== $password_check) {
            $error_message = "Les mots de passe ne correspondent pas";
            $step = 2;
        } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || 
                  !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $error_message = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial";
            $step = 2;
        } else {
            $step = 3;
        }
    } elseif (isset($_POST['back_step'])) {
        // Aller à l'étape choisie
        $step = (int)$_POST['back_step'];
        // Pas besoin de recharger les données, elles sont en session
    } elseif (isset($_POST['submit_step3'])) {
        try {
            $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
            if (!$link) {
                throw new Exception("Échec connexion MySQL: ".mysqli_connect_error());
            }
            error_log("Connexion DB réussie");

            $query = "SELECT id FROM Users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $error_message = "Cet email est déjà utilisé";
                $step = 1;
            } else {
                // Hash le mot de passe AVANT la requête
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Générer un token de validation
                $validation_token = bin2hex(random_bytes(32));
                $token_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                // Obtenir la date actuelle
                $date_creation = date('Y-m-d');
                
                // Insertion de l'utilisateur dans la base de données
                $query = "INSERT INTO Users (prenom, nom, email, telephone, password, validation_token, token_expiration, mail_valide, date_creation) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "ssssssss", $prenom, $nom, $email, $telephone, $hashed_password, $validation_token, $token_expiration, $date_creation);
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    // Récupérer l'ID de l'utilisateur nouvellement créé
                    $user_id = mysqli_insert_id($link);

                    // Créer la session pour connecter l'utilisateur
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['nom'] = $nom;
                    $_SESSION['email'] = $email;
                    $_SESSION['telephone'] = $telephone;
                    $_SESSION['date_creation'] = $date_creation;
                    
                    // Connexion automatique
                    $_SESSION['member_logged_in'] = true;

                    // Construire le lien de confirmation
                    $confirmation_link = "https://racines.ralaikoa.com/membre/mail_verify.php?token=" . $validation_token;

                    // Envoi d'un mail de vérification de l'adresse
                    $to = $email;
                    $subject = "Vérification de l'adresse mail";
                    $message = "Bonjour,\n\nTu as récemment créé un compte membre sur la plateforme Racines. "
                               . "Clique sur le lien suivant pour vérifier ton adresse mail :\n\n"
                               . $confirmation_link . "\n\n"
                               . "Ce lien expirera dans 24 heures.\n\n"
                               . "Cordialement,\nL'équipe Racines";
                    $headers = "From: no-reply@racines.com";
                    
                    // Envoi du mail
                    if (mail($to, $subject, $message, $headers)) {
                        // Redirection vers le tableau de bord
                        header("Location: /membre/member_dashboard.php");
                        exit();
                    } else {
                        throw new Exception("Erreur lors de l'envoi de l'email de vérification.");
                    }
                } else {
                    throw new Exception("Erreur lors de la création du compte");
                }
            }

            mysqli_close($link);
        } catch (Exception $e) {
            error_log("ERREUR: ".$e->getMessage());
            $error_message = "Erreur technique - Consulte les logs";
            $step = 1;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset='utf-8'>
    <title>Créer un compte - Racines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
        body {
            color:black ;
        }
        
        #form-crea-compte {
            max-width: 1000px;
            width: auto;
        }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input {
            flex: 1;
            padding-right: 40px;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .step {
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #f0f0f0;
            color: #666;
            text-align: center;
            flex: 1;
            margin: 0 5px;
        }
        
        .step.completed {
            background-color: #4CAF50;
            color: white;
        }
        
        .step.active {
            background-color: #fff9a8;
            color: 7d685;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .secondary-button {
            background-color: #f0f0f0;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .secondary-button:hover {
            background-color: #e0e0e0;
        }
        
        .recap-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .recap-step-group {
            width: 45%;
        }
        
        .edit-button {
            background-color: #f0f0f0;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            position: relative;
            bottom:0;
            left: 50%;
        }
        
        .recap-field {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .recap-field span {
            text-align: right;
        }
        
        .recap-field:last-child {
            border-bottom: none;
        }
        
        .recap-field strong {
            font-weight: 600;
        }
    </style>
    <script>
    function setClickedButton(name) {
        document.getElementById('clickedButton').value = name;
    }

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
    <h1 class="titre">Crée un compte</h1>
    <div class="form-container" id="form-crea-compte">
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <form method="post" onsubmit="return validateForm()">
            <input type="hidden" name="step" id="form-step" value="<?php echo $step; ?>">
            
            <?php if ($step == 1): ?>
                <!-- Étape 1: Informations personnelles -->
                <div class="progress-steps">
                    <div class="step active">1. Informations personnelles</div>
                    <div class="step">2. Sécurité du compte</div>
                    <div class="step">3. Confirmation</div>
                </div>
                
                <div class="field">
                    <label for="fname">Prénom :</label>
                    <input type="text" name="fname" id="fname" required value="<?php echo htmlspecialchars($prenom); ?>">
                </div>
                
                <div class="field">
                    <label for="name">Nom :</label>
                    <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($nom); ?>">
                </div>
                
                <div class="field">
                    <label for="email">Adresse mail :</label>
                    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div class="form-navigation">
                    <input type="submit" name="submit_step1" value="Continuer">
                </div>
                
            <?php elseif ($step == 2): ?>
                <!-- Étape 2: Sécurité du compte -->
                <div class="progress-steps">
                    <div class="step completed">1. Informations personnelles</div>
                    <div class="step active">2. Sécurité du compte</div>
                    <div class="step">3. Confirmation</div>
                </div>
                
                <input type="hidden" name="fname" value="<?php echo htmlspecialchars($prenom); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($nom); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                
                <div class="field">
                    <label for="telephone">Téléphone :</label>
                    <input type="tel" name="telephone" id="telephone" value="<?php echo htmlspecialchars($telephone); ?>" pattern="^0[1-9][0-9]{8}$" maxlength="10" inputmode="numeric" required title="Numéro français à 10 chiffres commençant par 0">

                </div>
                
                <div class="field">
                    <label for="password">Mot de passe :</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" required oninput="validatePassword()" value="">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', 'password-icon')">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small>Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial</small>
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
                
                <div class="form-navigation">
                    <button type="submit" name="back_step" value="1" class="secondary-button" formnovalidate onclick="setClickedButton('back_step')">Retour</button>
                    <input type="submit" name="submit_step2" value="Continuer" onclick="setClickedButton('submit_step2')">
                </div>
                
            <?php elseif ($step == 3): ?>
            
                <!-- Hidden fields pour conserver les valeurs -->
                <input type="hidden" name="fname" value="<?php echo htmlspecialchars($prenom); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($nom); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>">
                <input type="hidden" name="password" value="<?php echo htmlspecialchars($password); ?>">
                <input type="hidden" name="password-check" value="<?php echo htmlspecialchars($password_check); ?>">

                <!-- Étape 3: Récapitulatif -->
                <div class="progress-steps">
                    <div class="step completed">1. Informations personnelles</div>
                    <div class="step completed">2. Sécurité du compte</div>
                    <div class="step active">3. Confirmation</div>
                </div>
                
                <h2>Vérifie tes informations</h2>
                
                <div class="recap-container">
                    <div class="recap-step-group">
                        <div class="recap-field">
                            <strong>Prénom :</strong>
                            <span><?php echo htmlspecialchars($prenom); ?></span>
                            
                        </div>
                    
                        <div class="recap-field">
                            <strong>Nom :</strong>
                            <span><?php echo htmlspecialchars($nom); ?></span>
                        </div>
                    
                        <div class="recap-field">
                            <strong>Email :</strong>
                            <span><?php echo htmlspecialchars($email); ?></span>
                        </div>
                        <button type="submit" name="back_step" value="1" class="edit-button" formnovalidate>Modifier</button>
                    </div>
                    
                    <div class="recap-step-group">
                        <div class="recap-field">
                            <strong>Téléphone :</strong>
                            <span><?php echo htmlspecialchars($telephone ? $telephone : 'Non renseigné'); ?></span>
                        </div>
                    
                        <div class="recap-field">
                            <strong>Mot de passe :</strong>
                            <span>••••••••</span>
                        </div>
                        <button type="submit" name="back_step" value="2" class="edit-button" formnovalidate>Modifier</button>
                    </div>
                </div>

                
                <div class="form-navigation">
                    <input type="submit" name="submit_step3" value="Confirmer et créer le compte">
                </div>
            <?php endif; ?>
            
            <hr>
            
            <div class="help">
                <p>Tu as déjà un compte ? <a href="membre.php">Clique ici et connecte toi</a></p>
            </div>
        </form>
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