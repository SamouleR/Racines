<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error_message = "";

// Vérification si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] === false) {
    header("Location: /membre/membre.php");
    exit();
}

// Récupérer les informations complètes de l'utilisateur depuis la base de données
try {
    $link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
    
    $query = "SELECT id, prenom, nom, email, telephone, date_naissance, password FROM Users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($result)) {
        $_SESSION['date_naissance'] = $user['date_naissance'];
        $hashed_password = $user['password'];
        $current_email = $user['email'];
    } else {
        throw new Exception("Utilisateur non trouvé");
    }
} catch (Exception $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Vérification AJAX du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_password'])) {
    $password = $_POST['password'];
    if (password_verify($password, $hashed_password)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

// Soumission du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $date_naissance = $_POST['date_naissance'];
    $password = $_POST['password_confirm'];
    
    try {
        // Vérifier d'abord le mot de passe
        if (!password_verify($password, $hashed_password)) {
            $error_message = "Mot de passe incorrect.";
        } else {
            // Vérifier si l'email a été modifié
            $email_changed = ($email !== $current_email);
            
            // Mettre à jour les informations de base
            $update_query = "UPDATE Users SET prenom = ?, nom = ?, email = ?, telephone = ?, date_naissance = ? WHERE id = ?";
            $stmt = mysqli_prepare($link, $update_query);
            mysqli_stmt_bind_param($stmt, "sssssi", $prenom, $nom, $email, $telephone, $date_naissance, $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            
            // Mettre à jour la session
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;
            $_SESSION['email'] = $email;
            $_SESSION['telephone'] = $telephone;
            $_SESSION['date_naissance'] = $date_naissance;
            
            $_SESSION['success_message'] = "Vos informations ont été mises à jour avec succès.";
            
            // Si l'email a changé
            if ($email_changed) {
                // Vérifier si l'email existe déjà
                $check_email_query = "SELECT id FROM Users WHERE email = ? AND id != ? LIMIT 1";
                $stmt = mysqli_prepare($link, $check_email_query);
                mysqli_stmt_bind_param($stmt, "si", $email, $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    throw new Exception("Cet email est déjà utilisé par un autre compte.");
                }
                
            } else {
                
                // Générer et stocker le token
                $validation_token = bin2hex(random_bytes(32));
                $token_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                $verify_query = "UPDATE Users SET mail_valide = 0 WHERE id = ?";
                $stmt = mysqli_prepare($link, $verify_query);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                
                $token_query = "UPDATE Users SET validation_token = ?, token_expiration = ? WHERE id = ?";
                $stmt = mysqli_prepare($link, $token_query);
                mysqli_stmt_bind_param($stmt, "ssi", $validation_token, $token_expiration, $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                
                // Préparer l'email
                $confirmation_link = "https://racines.ralaikoa.com/membre/mail_verify.php?token=" . $validation_token;
                
                $to = $email;
                $subject = "Vérification de votre nouvelle adresse email";
                $message = "
                <html>
                <head>
                <link href='https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap' rel='stylesheet'>
                <meta charset='utf-8'>
                	<title>Vérification d'email</title>
                	<style>
                		
                		body {
                		    color: black;
                		    font-family: 'Sora', sans-serif;
                			line-height: 1.6; 
                		}
                		
                		.container { 
                			max-width: 600px; 
                			margin: 0 auto; 
                			padding: 20px; 
                		}
                		
                		.button {
                			display: inline-block;
                			padding: 10px 20px;
                			background-color: #A72642;
                			color: white !important;
                			text-decoration: none;
                			border-radius: 5px;
                			margin: 15px 0;
                		}
                	</style>
                </head>
                <body>
                	<div class='container'>
                		<h2>Bonjour ".htmlspecialchars($prenom).",</h2>
                		<p>Tu as modifié ton adresse email sur notre plateforme Racines.</p>
                		<p>Clique sur le bouton suivant pour confirmer ta nouvelle adresse email :</p>
                		<a href='".$confirmation_link."' class='button'>Confirmer mon email</a>
                		<p>Ou copie ce lien dans ton navigateur :<br>
                		<small>".$confirmation_link."</small></p>
                		<p>Ce lien expirera dans <strong>24 heures.</strong></p>
                		<p>Cordialement,<br>L'équipe Racines</p>
                	</div>
                </body>
                </html>
                ";
                
                $headers = [
                    'MIME-Version' => '1.0',
                    'Content-type' => 'text/html; charset=UTF-8',
                    'From' => 'no-reply@racines.ralaikoa.com',
                    'Reply-To' => 'no-reply@racines.ralaikoa.com',
                    'X-Mailer' => 'PHP/' . phpversion()
                ];
                
                // Construire les headers
                $headers_str = '';
                foreach ($headers as $key => $value) {
                    $headers_str .= "$key: $value\r\n";
                }
                
                // Envoyer l'email
                if (mail($to, $subject, $message, $headers_str)) {
                    $_SESSION['success_message'] .= " Un email de confirmation a été envoyé à votre nouvelle adresse.";
                } else {
                    error_log("Échec de l'envoi d'email à: $to");
                    $_SESSION['warning_message'] = "L'email de confirmation n'a pas pu être envoyé.";
                }
            }
            
            header("Location: /membre/member_dashboard.php");
            exit();
        }
    } catch (Exception $e) {
        $error_message = "Erreur : " . $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset='utf-8'>
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/style_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
        .form-container {
            min-width: 400px;
            width: auto;
            max-width: 1000px;  
        }  
        
        small {
            color: black;
        }
        
        #password-field {
            background-color: white;    
        }
        
        .btn-container a, #submitBtn {
            background-color: #A72642;
            color: #FFF;
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }
        .btn-container a:hover {
            background-color: #BC163A;
        }
        
        #password_change {
            text-align: center;
            text-decoration: none;
            background-color: #A72642;
            color: #FFF;
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            transition: background-color 0.3s;
            width: auto;
        }
        
        #submitBtn {
            background-color: #3bcc09;
        }
        
        #submitBtn:hover {
            background-color: #5bda30;
        }
        
        .btn-container {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            list-style-type: none;
        }
        
        /* Styles pour le modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            align-items: center;
            justify-content: center;
            color: black;
        }
        
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            width: 350px;
            box-shadow: 0 0 10px rgba(0,0,0,0.25);
            text-align: center;
        }
        
        .modal-content input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        
        .modal-content button {
            background-color: #A72642;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .modal-content button:hover {
            background-color: #BC163A;
        }
        
        .close {
            position: relative;
            top: 5px;
            right: 5px;
            float: right;
            font-size: 20px;
            cursor: pointer;
            color: #BC163A;
        }
        
        #confirmError {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<!-- MODAL DE CONFIRMATION -->
<div id="passwordModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Confirme ton mot de passe pour valider les modifications :</p>
        <input type="password" id="confirmPasswordInput" placeholder="Mot de passe">
        <button id="confirmPasswordBtn">Confirmer</button>
        <p id="confirmError" style="color: red;"></p>
    </div>
</div>



<body>
    <h1 class="titre">Modifier mon profil</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form class="form-container" id="form-profil-edit" method="POST">
        <div class="field">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id='prenom' value="<?php echo htmlspecialchars($_SESSION['prenom']); ?>">
        </div>
        
        <div class="field">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id='nom' value="<?php echo htmlspecialchars($_SESSION['nom']); ?>">
        </div>
        
        <div class="field">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
            <small>Changer d'adresse mail nécessitera de la valider à nouveau</small>
        </div>

        <div class="field">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password-field" value="••••••••" disabled>
            <ul class="btn-container">
                <a id="password_change" type="button" href="password_change1.php?id=<?php echo $_SESSION['user_id']; ?>">Modifier le mot de passe</a>
            </ul>
        </div>
        
        <div class="field">
            <label for="telephone">Téléphone : </label>
            <input type='tel' name="telephone" id="telephone" value="<?php echo htmlspecialchars($_SESSION['telephone']); ?>"> 
        </div>
        
        <div class="field">
            <label for="date_naissance">Date de naissance : </label>
            <input type="date" name="date_naissance" id="date_naissance" value="<?php echo htmlspecialchars($_SESSION['date_naissance']); ?>">
        </div>
        
        <!-- Champ caché pour le mot de passe confirmé -->
        <input type="hidden" name="password_confirm" id="password_confirm">
        
        <div class="btn-container">
            <a type="button" href="/membre/member_dashboard.php">Annuler</a>
            <button type="button" id="submitBtn">Modifier</button>
            <!-- Le bouton caché pour soumettre réellement -->
            <input type="submit" name="submit" id="hiddenSubmit" style="display: none;">
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal
            const modal = document.getElementById("passwordModal");
            const btn = document.getElementById("submitBtn");
            const span = document.getElementsByClassName("close")[0];
            const confirmBtn = document.getElementById("confirmPasswordBtn");
            const confirmInput = document.getElementById("confirmPasswordInput");
            const confirmError = document.getElementById("confirmError");
            const passwordConfirmField = document.getElementById("password_confirm");
            const hiddenSubmit = document.getElementById("hiddenSubmit");
            const form = document.getElementById("form-profil-edit");
            
            // Quand on clique sur le bouton Modifier
            btn.onclick = function() {
                modal.style.display = "flex";
                confirmInput.focus(); // Focus sur le champ de mot de passe
            }
            
            // Quand on clique sur la croix
            span.onclick = function() {
                closeModal();
            }
            
            // Fonction pour fermer le modal
            function closeModal() {
                modal.style.display = "none";
                confirmInput.value = "";
                confirmError.textContent = "";
            }
            
            // Quand on clique en dehors du modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }
            
            // Quand on clique sur Confirmer
            confirmBtn.onclick = function() {
                verifyPassword();
            }
            
            // Fonction pour vérifier le mot de passe
            function verifyPassword() {
                const password = confirmInput.value;
                
                if (!password) {
                    confirmError.textContent = "Veuillez entrer votre mot de passe";
                    return;
                }
                
                // Créer un FormData pour envoyer les données
                const formData = new FormData();
                formData.append('verify_password', '1');
                formData.append('password', password);
                
                // Envoyer le mot de passe au serveur pour vérification via AJAX
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Si le mot de passe est correct
                        passwordConfirmField.value = password;
                        closeModal();
                        hiddenSubmit.click(); // Soumettre le formulaire
                    } else {
                        confirmError.textContent = "Mot de passe incorrect";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    confirmError.textContent = "Une erreur s'est produite. Veuillez réessayer.";
                });
            }
            
            // Permettre aussi la validation avec Entrée
            confirmInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    verifyPassword();
                }
            });
            
            // Empêcher la soumission directe du formulaire
            form.addEventListener('submit', function(e) {
                if (!passwordConfirmField.value) {
                    e.preventDefault();
                    btn.click();
                }
            });
        });
        </script>
</body>
</html>