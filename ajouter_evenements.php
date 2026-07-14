<?php
// Connexion à la BDD
$host = 'localhost';
$db = 'u237218091_racine';
$user = 'u237218091_racine';
$pass = 'racineSSJJ1234';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $participants = (int)$_POST['participants'];
    $image = $_POST['image'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (isset($_POST['update']) && $id) {
        $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, date=?, location=?, participants=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $date, $location, $participants, $image, $id]);
        $success = "Événement modifié !";
    } elseif (isset($_POST['delete']) && $id) {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
        $stmt->execute([$id]);
        $success = "Événement supprimé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, date, location, participants, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $date, $location, $participants, $image]);
        $success = "Événement ajouté.";
    }
}

$events = $pdo->query("SELECT * FROM events ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Racines - Événements</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: #ff5e5b;
            color: #333;
            display: flex;
        }
        .sidebar {
            width: 30%;
            padding: 2rem;
            background: #fff0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .mascotte {
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
        }
        .card-red {
            background: #ff5e5b;
            color: white;
            padding: 1rem;
            border-radius: 15px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 2rem;
        }
        form {
            width: 100%;
            background: white;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form label {
            margin-top: 1rem;
            display: block;
        }
        form input, form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        form button {
            margin-top: 1rem;
            background: #ff5e5b;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
        }
        .main-content {
            width: 70%;
            padding: 2rem;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
        }
        .event-card {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        .event-card img {
            width: 150px;
            height: auto;
            margin-right: 1rem;
            border-radius: 10px;
        }
        .event-info {
            flex: 1;
        }
        .event-actions form {
            display: inline-block;
        }
        h1 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <!-- Mascotte SVG -->
    <div class="mascotte">
        <svg viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="30" fill="#ff5e5b"/>
            <circle cx="24" cy="26" r="4" fill="white"/>
            <circle cx="40" cy="26" r="4" fill="white"/>
            <path d="M20 42c4 4 12 4 16 0" stroke="white" stroke-width="3" stroke-linecap="round"/>
        </svg>
    </div>

    <div class="card-red">
        Ici, personnalisation des événements Racines
    </div>

    <form method="POST">
        <h3>Ajouter un Événement</h3>
        <label>Titre</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Date</label>
        <input type="date" name="date" required>

        <label>Lieu</label>
        <input type="text" name="location" required>

        <label>Participants</label>
        <input type="number" name="participants" required>

        <label>Image (URL)</label>
        <input type="text" name="image" required>

        <button type="submit">Ajouter</button>
    </form>
</div>

<div class="main-content">
    <h1>Liste des Événements</h1>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php foreach ($events as $event): ?>
        <div class="event-card">
            <img src="<?= htmlspecialchars($event['image']) ?>" alt="Affiche">
            <div class="event-info">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p><strong>Date :</strong> <?= $event['date'] ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($event['location']) ?></p>
                <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                <div class="event-actions">
                    <!-- Modifier -->
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                        <input type="hidden" name="title" value="<?= htmlspecialchars($event['title']) ?>">
                        <input type="hidden" name="description" value="<?= htmlspecialchars($event['description']) ?>">
                        <input type="hidden" name="date" value="<?= $event['date'] ?>">
                        <input type="hidden" name="location" value="<?= htmlspecialchars($event['location']) ?>">
                        <input type="hidden" name="participants" value="<?= $event['participants'] ?>">
                        <input type="hidden" name="image" value="<?= htmlspecialchars($event['image']) ?>">
                        <button type="submit" name="update">Modifier</button>
                    </form>

                    <!-- Supprimer -->
                    <form method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                        <button type="submit" name="delete">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
