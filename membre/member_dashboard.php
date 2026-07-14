<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);



// Vérification robuste de la session
if (empty($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true ) {
    header('Location: /membre/membre.php');
    exit();
}

// Protection contre la fixation de session
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /membre/membre.php');
    exit();
}

// Connexion à la base de données avec MySQLi
$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");

// Vérification de la connexion
if (!$link) {
    error_log("Erreur de connexion MySQLi: " . mysqli_connect_error());
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.");
}

// Définir le charset
mysqli_set_charset($link, "utf8mb4");

// Récupération des infos utilisateur depuis la base de données
$email = $_SESSION['email'];
$query = "SELECT id, email, telephone, date_naissance, mail_valide, date_creation, validation_token FROM Users WHERE id = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result) {
        $user_data = mysqli_fetch_assoc($result);
        
        // Stockage des données dans la session
        if ($user_data) {
            $_SESSION['id'] = $user_data['id'] ?? null;
            $_SESSION['email'] = $user_data['email'] ?? null;
            $_SESSION['telephone'] = $user_data['telephone'] ?? null;
            $_SESSION['date_naissance'] = $user_data['date_naissance'] ?? null;
            $_SESSION['mail_valide'] = $user_data['mail_valide'] ?? 0;
            $_SESSION['validation_token'] = $user_data['validation_token'] ?? null;
            $_SESSION['date_creation'] = $user_data['date_creation'] ?? null;
        }
    } else {
        error_log("Erreur MySQLi: " . mysqli_error($link));
    }
    
    mysqli_stmt_close($stmt);
} else {
    error_log("Erreur de préparation MySQLi: " . mysqli_error($link));
}

mysqli_close($link);

// Formatage des données pour l'affichage
$date_creation_formatted = '(Erreur de récupération de la date de création de compte)';

if (isset($_SESSION['date_creation']) && !empty($_SESSION['date_creation'])) {
    $creation_timestamp = strtotime($_SESSION['date_creation']);
    if ($creation_timestamp !== false && $creation_timestamp > 0) {
        $date_creation_formatted = date('d/m/Y', $creation_timestamp);
    }
}

$telephone_formatted = isset($_SESSION['telephone']) && !empty($_SESSION['telephone']) ? 
    chunk_split($_SESSION['telephone'], 2, ' ') : 'Non renseigné';

$date_naissance_formatted = 'Non renseignée';

if (isset($_SESSION['date_naissance']) && !empty($_SESSION['date_naissance'])) {
    $timestamp = strtotime($_SESSION['date_naissance']);
    if ($timestamp !== false && $timestamp > 0) {
        $date_naissance_formatted = date('d/m/Y', $timestamp);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Racines</title>

    <!-- Fonts & Styles -->
    <link rel="stylesheet" href="/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script>
        function setClickedButton(name) {
            document.getElementById('clickedButton').value = name;
        }
        
        
    </script>
    <style>
        main {
            color: black;
        }
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .info-container {
            width:50%;
            align-content: center;
            display: none;
            text-align: center;
        }
        
        .info-container.active {
            display: block;
        }
        
        .side-menu {
            width: 50%;
            color: black;
            display: flex;
            flex-direction: column;
            justify-content: center;   
            align-items: center;     
            height: 100%;              
        }
        
        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;       
        }


        .side-menu li {
            margin: 1rem;
        }  
        
        .side-menu hr {
            width: 100%;
            opacity: 75%;
            justify-self:center;
        }

        .side-menu button {
            background: none;
            border: none;
            padding: 0.8rem 1rem;
            color: #555;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 300;
            font-size: 1.25rem;
            cursor: pointer;
        }

        .side-menu button:hover, .side-menu button.active {
            background-color: var(--primary);
            color: #bc163a;
        }
        
        #v-line {
            border-left: 1px solid black;
            height: 500px;
            position: relative;
            left: 0;
            margin-left: -3px;
        }
        
        #edit-btn {
            background-color: #fff5eb;
            color: black;
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        #edit-btn:hover {
            background-color: #fffcf9;
        }
        
        #auth-db-facteur-btn {
            background-color: #fff5eb;
            color: black;
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        #auth-db-facteur-btn:hover {
            background-color: #fffcf9;
        }
        
        .logout-container {
            align-items: center;
        }
        
        .logout-btn {
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
        .logout-btn:hover {
            background-color: #BC163A;
        }
        
        #user-info {
            color: white;
            background-color: #bc163a;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        #user-info .titre {
            color: white;
        }
        
        #user-info #creation-date {
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
        
        #user-info p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
        }
        
        .info-label {
            font-weight: 600;
            color: #e8e8e8;
        }
        
        .btn-container {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            list-style-type: none;
        }
        
        .del-account-btn {
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
        .del-account-btn:hover {
            background-color: #BC163A;
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
    
        <?php if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])): ?>
        <h1 class="titre">Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']); ?> !</h1>
        <?php elseif (isset($_SESSION['email'])): ?>
        <h1> Connecté(e) à l'adresse <?php echo htmlspecialchars($_SESSION['email']); ?></h1>
        <?php else: ?>
        <h1>Bienvenue</h1>
        <?php endif; ?>
        
            <style>
              /* Styles du pop-up - Plus visible et prioritaire */
              #mail-verification-popup {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 350px; /* Légèrement plus large */
                background: #fff3e0; /* Couleur plus claire */
                border: 2px solid #bc163a;
                padding: 20px;
                box-shadow: 0 0 15px rgba(188, 22, 58, 0.7);
                font-family: "Sora", sans-serif;
                font-size: 15px;
                color: #d32f2f;
                border-radius: 8px;
                z-index: 10000; /* Z-index plus élevé */
                font-weight: 400;
                animation: slideIn 0.5s ease-out;
              }
              
              @keyframes slideIn {
                from { transform: translateX(100%); }
                to { transform: translateX(0); }
              }
              
              #mail-verification-popup a {
                color: #bc163a;
                text-decoration: underline;
                font-weight: 600;
              }
              
              #mail-verification-popup a:hover {
                color: #e53935;
              }
              
              #mail-verification-popup .close-btn {
                position: absolute;
                top: 10px;
                right: 12px;
                cursor: pointer;
                font-weight: bold;
                color: #d32f2f;
                font-size: 18px;
                background: none;
                border: none;
              }
              
              #mail-verification-popup p {
                margin-bottom: 10px;
                line-height: 1.5;
              }
            </style>
            
            <div id="mail-verification-popup">
              <button class="close-btn" onclick="document.getElementById('mail-verification-popup').style.display='none'">&times;</button>
              <p><strong>Ton adresse mail n'est pas vérifiée !</strong></p>
              <p>Pour accéder à toutes les fonctionnalités du site, merci de vérifier ton adresse email en cliquant sur le lien que nous t'avons envoyé.</p>
              <p>Tu n'as pas reçu le mail ? <a href="renvoyer_verification.php?token=<?php echo isset($_SESSION['validation_token']) ? urlencode($_SESSION['validation_token']) : ''; ?>">Clique ici pour en recevoir un nouveau</a>.</p>
            </div>
            
            <script>
              // Fermer le pop-up après 30 secondes
              setTimeout(function() {
                const popup = document.getElementById('mail-verification-popup');
                if (popup) popup.style.display = 'none';
              }, 30000);
            </script>
        
        <div class="logout-container">
            <a href="member_logout.php" class="logout-btn">Se déconnecter</a>
        </div>
        
        <div class="container">
            <aside class="side-menu">
                <ul>
                    <li><button type="button" name="profile" href="#user-info" class="active" onclick="setClickedButton('profile')">Mon profil</button></li><hr>
                    <li><button type="button" name="fav" href="#fav" onclick="setClickedButton('fav')">Mes favoris</button></li><hr>
                    <li><button type="button" name="newsletter" href="#mentions-legales" onclick="setClickedButton('newsletter')">Newsletter</button></li><hr>
                    <li><button type="button" name="del-acc" href="#supp-compte" onclick="setClickedButton('del-acc')">Suppression de compte</button></li>
                </ul>
            </aside>
            
            <div id="v-line"></div>
            
            <div class="info-container active" id="profile">
                <div id="user-info">
                <h2 class='titre'>Informations du profil</h2>
                
                
                 <?php if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])): ?>
                <p><span class="info-label">Nom complet :</span> <?php echo htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']); ?></p>
                <?php endif; ?>
                
                <p><span class="info-label">Email :</span>
                <?php echo htmlspecialchars($_SESSION['email']); ?> 
                <?php if (isset($_SESSION['mail_valide']) && $_SESSION['mail_valide'] == 1) : ?>(Validée)
                <?php else : ?>(Non validée)
                <?php endif; ?></p>
                
               
                <p><span class="info-label">Téléphone :</span> <?php echo htmlspecialchars($telephone_formatted); ?></p>
                <p><span class="info-label">Date de naissance :</span> <?php echo htmlspecialchars($date_naissance_formatted); ?></p>
                <p id="creation-date">A rejoint Racines le <?php echo htmlspecialchars($date_creation_formatted); ?></p>
                
                <ul class="btn-container">
                    <li><a href="/membre/profil_edit.php?id=<?php echo isset($_SESSION['id']) ? urlencode($_SESSION['id']) : ''; ?>" id="edit-btn">Modifier mon profil</a></li>
                    <li><a href="auth-db-facteur.php" id="auth-db-facteur-btn">Activer l'A2F</a></li>
                </ul>    
                </div>
            </div>
            
            <div class="info-container" id="fav">
                <div>
                    <h2 class='titre'>Mes favoris</h2>
                    <p>Liste de favoris ici...</p>
                </div>
            </div>
        
        <div class="info-container" id="newsletter">
            <div>
                <h2 class='titre'>Newsletter</h2>
                <p>Paramètres de la newsletter...</p>
            </div>
        </div>
            <div class="info-container" id="del-acc">
                <div id="supprimer-compte">
                    <h2 class='titre'>Suppression de ton compte membre</h2>
                    <strong>ATTENTION !</strong><p>La suppression de ton compte est une action<strong> définitive !</strong></br>Es-tu sûr(e) de vouloir continuer ?</p>
                    <div class="btn-container">    
                        <a href="supprimer-compte.php?id=<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : (isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : ''); ?>" class="del-account-btn">SUPPRIMER LE COMPTE</a>
                    </div>
                </div>
            </div>
        </div>
  </main>
  <script>
      document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.side-menu button');
        const sections = document.querySelectorAll('.info-container');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.name;

                // Retirer la classe active de tous les boutons
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Masquer toutes les sections
                sections.forEach(section => section.classList.remove('active'));

                // Afficher la section correspondante
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            });
        });
    });
  </script>
</body>
</html>