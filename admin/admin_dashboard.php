<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
if (!$link) {
    die("Échec de la connexion MySQL: " . mysqli_connect_error());
}

// Vérification de la session admin
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true || empty($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

// Protection contre la fixation de session
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Fonctions utilitaires
function safeDisplay($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

// Traitement des actions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ajout d'un événement
    if (isset($_POST['add_event'])) {
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $date = mysqli_real_escape_string($link, $_POST['date']);
        $price = mysqli_real_escape_string($link, $_POST['price']);
        $location = mysqli_real_escape_string($link, $_POST['location']);
        $organizer = mysqli_real_escape_string($link, $_POST['organizer']);
        $activityType = mysqli_real_escape_string($link, $_POST['activityType']);
        $image = mysqli_real_escape_string($link, $_POST['image']);

        $query = "INSERT INTO events (title, date, price, location, organizer, activityType, image)
                  VALUES ('$title', '$date', '$price', '$location', '$organizer', '$activityType', '$image')";
        mysqli_query($link, $query);
    }
    
    // Suppression d'un événement
    if (isset($_POST['delete_event'])) {
        $event_id = intval($_POST['event_id']);
        mysqli_query($link, "DELETE FROM events WHERE id = $event_id");
    }
    
    // Suppression d'un membre
    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);
        mysqli_query($link, "DELETE FROM Users WHERE id = $user_id");
    }
    
    // Suppression d'un abonné newsletter
    if (isset($_POST['delete_subscriber'])) {
        $email = mysqli_real_escape_string($link, $_POST['email']);
        mysqli_query($link, "DELETE FROM newsletter_subscribers WHERE email = '$email'");
    }
    
    // Modification d'un membre
    if (isset($_POST['edit_user'])) {
        $user_id = intval($_POST['user_id']);
        $prenom = mysqli_real_escape_string($link, $_POST['prenom']);
        $nom = mysqli_real_escape_string($link, $_POST['nom']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $telephone = mysqli_real_escape_string($link, $_POST['telephone']);
        $date_naissance = mysqli_real_escape_string($link, $_POST['date_naissance']);
        
        $query = "UPDATE Users SET 
                 prenom = '$prenom',
                 nom = '$nom',
                 email = '$email',
                 telephone = '$telephone',
                 date_naissance = '$date_naissance'
                 WHERE id = $user_id";
        mysqli_query($link, $query);
    }
}

// Récupérer l'utilisateur à éditer si l'ID est passé en paramètre
$user_to_edit = null;
if (isset($_GET['edit_user'])) {
    $user_id = intval($_GET['edit_user']);
    $result = mysqli_query($link, "SELECT * FROM Users WHERE id = $user_id");
    $user_to_edit = mysqli_fetch_assoc($result);
}

// Récupérer le membre à afficher si l'ID est passé en paramètre
$member_to_view = null;
if (isset($_GET['view_member'])) {
    $member_id = intval($_GET['view_member']);
    $result = mysqli_query($link, "SELECT * FROM Users WHERE id = $member_id");
    $member_to_view = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord administrateur - Racines</title>
    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Sora', sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #fff5eb;
        }
        
        main {
            color: black;
            padding: 2rem;
        }
        
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .info-container {
            width: 100%;
            display: none;
            padding: 20px;
            box-sizing: border-box;
        }

        .info-container.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .side-menu {
            width: 50%;
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

        .side-menu button:hover, 
        .side-menu button.active {
            background-color: var(--primary);
            color: #bc163a;
        }
        
        #v-line {
            border-left: 1px solid black;
            height: 500px;
            margin: 0 2rem;
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
            display: inline-block;
        }
        
        .logout-btn:hover {
            background-color: #BC163A;
        }
        
        .members-list {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
        }
        
        .members-list th, 
        .members-list td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
        }
        
        .members-list th {
            background-color: #f0f0f0;
        }
        
        .btn-container {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            list-style-type: none;
        }
        
        .titre {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        /* Formulaire d'ajout d'événement */
        .event-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .event-form input,
        .event-form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .event-form button {
            background-color: #bc163a;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .event-form button:hover {
            background-color: #A72642;
        }
        
        /* Boutons d'action */
        .action-btn {
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 0.3rem;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .view-btn {
            background-color: #2196F3;
            color: white;
        }
        
        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        /* Détails membre */
        .member-details {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .member-details p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        
        .side-menu button.active {
            background-color: #f0f0f0;
            color: #bc163a;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <main>
        <h1 class='titre'>Tableau de bord administrateur</h1>        
        <a href="admin_logout.php" class="logout-btn">Se déconnecter</a>
        
        <div class="container">
            <aside class="side-menu">
                <ul>
                    <li><button type="button" name="members">Liste des membres</button></li>
                    <hr>
                    <li><button type="button" name="events">Évènements</button></li>
                    <hr>
                    <li><button type="button" name="newsletter">Newsletter</button></li>
                    <hr>
                    <li><button type="button" name="del-acc">Suppression de compte</button></li>
                </ul>
            </aside>
            <div id="v-line"></div>
            
            <div class="info-container active" id="members">
                <h2>Liste des membres</h2>
                <?php
                $members = mysqli_query($link, "SELECT id, prenom, nom, email FROM Users ORDER BY nom, prenom");
                if ($members && mysqli_num_rows($members) > 0) {
                    echo '<table class="members-list">';
                    echo "<thead><tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr></thead><tbody>";

                    while ($member = mysqli_fetch_assoc($members)) {
                        echo "<tr>
                            <td>{$member['prenom']}</td>
                            <td>{$member['nom']}</td>
                            <td>{$member['email']}</td>
                            <td>
                                <a href='?view_member={$member['id']}#memberDetails' class='action-btn view-btn'>Voir</a>
                                <a href='?edit_user={$member['id']}#editModal' class='action-btn edit-btn'>Modifier</a>
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Êtes-vous sûr de vouloir supprimer ce membre ?\");'>
                                    <input type='hidden' name='user_id' value='{$member['id']}'>
                                    <button type='submit' name='delete_user' class='action-btn delete-btn'>Supprimer</button>
                                </form>
                            </td>
                        </tr>";
                    }

                    echo '</tbody></table>';
                    
                    // Affichage des détails du membre si demandé
                    if ($member_to_view) {
                        echo '<div id="memberDetails" class="member-details">';
                        echo '<h3>Détails du membre</h3>';
                        echo '<p><span class="detail-label">ID:</span> '.$member_to_view['id'].'</p>';
                        echo '<p><span class="detail-label">Prénom:</span> '.safeDisplay($member_to_view['prenom']).'</p>';
                        echo '<p><span class="detail-label">Nom:</span> '.safeDisplay($member_to_view['nom']).'</p>';
                        echo '<p><span class="detail-label">Email:</span> '.safeDisplay($member_to_view['email']).'</p>';
                        echo '<p><span class="detail-label">Téléphone:</span> '.safeDisplay($member_to_view['telephone']).'</p>';
                        echo '<p><span class="detail-label">Date de naissance:</span> '.formatDate($member_to_view['date_naissance']).'</p>';
                        echo '<p><span class="detail-label">Mail validé:</span> '.($member_to_view['mail_valide'] ? 'Oui' : 'Non').'</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucun membre trouvé</p>';
                }
                ?>
            </div>
            
            <div class="info-container" id="events">
                <h2>Gestion des évènements</h2>
                
                <div class="event-form">
                    <h3>Ajouter un nouvel évènement</h3>
                    <form method="POST">
                        <input type="text" name="title" placeholder="Titre de l'évènement" required>
                        <input type="date" name="date" required>
                        <input type="text" name="price" placeholder="Prix">
                        <input type="text" name="location" placeholder="Lieu" required>
                        <input type="text" name="organizer" placeholder="Organisateur">
                        <input type="text" name="activityType" placeholder="Type d'activité">
                        <input type="text" name="image" placeholder="URL de l'image">
                        <button type="submit" name="add_event">Ajouter l'évènement</button>
                    </form>
                </div>
                
                <h3>Évènements à venir</h3>
                <?php
                $events = mysqli_query($link, "SELECT * FROM evenements ORDER BY date DESC");
                if ($events && mysqli_num_rows($events) > 0) {
                    echo '<table class="members-list">';
                    echo "<thead><tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr></thead><tbody>";

                    while ($event = mysqli_fetch_assoc($events)) {
                        echo "<tr>
                            <td>{$event['title']}</td>
                            <td>".formatDate($event['date'])."</td>
                            <td>{$event['location']}</td>
                            <td>{$event['price']}</td>
                            <td>
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Êtes-vous sûr de vouloir supprimer cet évènement ?\");'>
                                    <input type='hidden' name='event_id' value='{$event['id']}'>
                                    <button type='submit' name='delete_event' class='action-btn delete-btn'>Supprimer</button>
                                </form>
                            </td>
                        </tr>";
                    }

                    echo '</tbody></table>';
                } else {
                    echo '<p>Aucun évènement à venir</p>';
                }
                ?>
            </div>
        
            <div class="info-container" id="newsletter">
                <h2>Abonnés à la newsletter</h2>
                <?php
                $subscribers = mysqli_query($link, "SELECT email, date_subscribed FROM newsletter_subscribers ORDER BY date_subscribed DESC");
                if ($subscribers && mysqli_num_rows($subscribers) > 0) {
                    echo '<table class="members-list">';
                    echo "<thead><tr>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr></thead><tbody>";

                    while ($subscriber = mysqli_fetch_assoc($subscribers)) {
                        echo "<tr>
                            <td>{$subscriber['email']}</td>
                            <td>".formatDate($subscriber['date_subscribed'])."</td>
                            <td>
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Êtes-vous sûr de vouloir supprimer cet abonné ?\");'>
                                    <input type='hidden' name='email' value='".safeDisplay($subscriber['email'])."'>
                                    <button type='submit' name='delete_subscriber' class='action-btn delete-btn'>Supprimer</button>
                                </form>
                            </td>
                        </tr>";
                    }

                    echo '</tbody></table>';
                } else {
                    echo '<p>Aucun abonné à la newsletter</p>';
                }
                ?>
            </div>
            
            <div class="info-container" id="del-acc">
                <h2>Suppression de compte</h2>
                <p>⚠️ Attention : Cette action est irréversible. Toutes les données du membre seront définitivement supprimées.</p>
                
                <form method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer ce compte ?');">
                    <div style="margin-bottom: 1rem;">
                        <label>ID du membre à supprimer :</label>
                        <input type="number" name="user_id" required style="padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                    </div>
                    <button type="submit" name="delete_user" class="logout-btn">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Modal d'édition -->
    <?php if ($user_to_edit): ?>
    <div id="editModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="window.location.href='?'">&times;</span>
            <h2>Modifier le membre</h2>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?= $user_to_edit['id'] ?>">
                <div style="margin-bottom: 1rem;">
                    <label>Prénom :</label>
                    <input type="text" name="prenom" value="<?= safeDisplay($user_to_edit['prenom']) ?>" required style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Nom :</label>
                    <input type="text" name="nom" value="<?= safeDisplay($user_to_edit['nom']) ?>" required style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Email :</label>
                    <input type="email" name="email" value="<?= safeDisplay($user_to_edit['email']) ?>" required style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Téléphone :</label>
                    <input type="text" name="telephone" value="<?= safeDisplay($user_to_edit['telephone']) ?>" style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Date de naissance :</label>
                    <input type="date" name="date_naissance" value="<?= $user_to_edit['date_naissance'] ?>" style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;">
                </div>
                <button type="submit" name="edit_user" style="background-color: #bc163a; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Fonction pour afficher les sections
        function showSection(sectionId, event) {
            if (event) event.preventDefault();
            
            // Masquer toutes les sections
            document.querySelectorAll('.info-container').forEach(section => {
                section.classList.remove('active');
            });
            
            // Afficher la section demandée
            const sectionToShow = document.getElementById(sectionId);
            if (sectionToShow) {
                sectionToShow.classList.add('active');
            }
            
            // Mettre à jour l'état actif des boutons
            document.querySelectorAll('.side-menu button').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('name') === sectionId) {
                    btn.classList.add('active');
                }
            });
        }

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            // Activer la section correspondant au hash ou le premier bouton par défaut
            const hash = window.location.hash.substring(1);
            const defaultSection = hash || 'members';
            
            // Trouver le bouton correspondant
            const defaultBtn = document.querySelector(`.side-menu button[name="${defaultSection}"]`) || 
                            document.querySelector('.side-menu button[name="members"]');
            
            if (defaultBtn) {
                showSection(defaultSection, {currentTarget: defaultBtn, preventDefault: ()=>{}});
            }
            
            // Gestion des clics sur les boutons du menu
            document.querySelectorAll('.side-menu button').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const sectionId = this.getAttribute('name');
                    showSection(sectionId, e);
                });
            });
            
            <?php if ($user_to_edit): ?>
            window.location.hash = 'editModal';
            <?php endif; ?>
            
            <?php if ($member_to_view): ?>
            window.location.hash = 'memberDetails';
            <?php endif; ?>
        });
    </script>
</body>
</html>