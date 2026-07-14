<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';

// Traitement des actions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ici nous incluons le traitement des formulaires
    require_once __DIR__ . '/sections/form_processing.php';
}

// Déterminer quelle section afficher
$current_section = 'members';
if (isset($_GET['section']) {
    $valid_sections = ['members', 'events', 'newsletter', 'del-acc'];
    if (in_array($_GET['section'], $valid_sections)) {
        $current_section = $_GET['section'];
    }
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
    <link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body>
    <main>
        <h1 class='titre'>Tableau de bord administrateur</h1>        
        <a href="admin_logout.php" class="logout-btn">Se déconnecter</a>
        
        <div class="container">
            <aside class="side-menu">
                <ul>
                    <li><a href="?section=members" class="<?= $current_section === 'members' ? 'active' : '' ?>">Liste des membres</a></li>
                    <hr>
                    <li><a href="?section=events" class="<?= $current_section === 'events' ? 'active' : '' ?>">Évènements</a></li>
                    <hr>
                    <li><a href="?section=newsletter" class="<?= $current_section === 'newsletter' ? 'active' : '' ?>">Newsletter</a></li>
                    <hr>
                    <li><a href="?section=del-acc" class="<?= $current_section === 'del-acc' ? 'active' : '' ?>">Suppression de compte</a></li>
                </ul>
            </aside>
            
            <div id="v-line"></div>
            
            <div class="content-area">
                <?php
                // Inclure la section appropriée
                require_once __DIR__ . "/sections/{$current_section}.php";
                
                // Inclure les modals si nécessaire
                if (isset($_GET['edit_user'])) {
                    require_once __DIR__ . '/sections/member_edit_modal.php';
                }
                if (isset($_GET['view_member'])) {
                    require_once __DIR__ . '/sections/member_view_details.php';
                }
                ?>
            </div>
        </div>
    </main>
    
    <script src="/admin/js/admin.js"></script>
</body>
</html>