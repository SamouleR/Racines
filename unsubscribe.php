<?php
session_start();

header("Access-Control-Allow-Origin: *"); // utile si fetch provient d’un domaine externe

$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
if (!$link) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur de connexion."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo json_encode(["success" => false, "message" => "Email invalide."]);
    } else {
        $stmt = $link->prepare("DELETE FROM newsletter WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Vous avez été désinscrit avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Cet email n’est pas abonné."]);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Se désinscrire</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #fff5eb;
      font-family: 'Sora', sans-serif;
      color: #BC163A;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .unsubscribe {
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 10px;
    }

    input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #BC163A;
      border-radius: 30px;
      background: transparent;
      font-size: 0.95rem;
      color: #BC163A;
      margin-bottom: 10px;
      outline: none;
    }

    button {
      background-color: #BC163A;
      color: #fff5eb;
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 1rem;
    }

    #message {
      margin-top: 15px;
      font-weight: bold;
    }

    .bottom-links {
      position: fixed;
      bottom: 20px;
      left: 20px;
      display: flex;
      gap: 10px;
    }

    .nav-button {
      background-color: #BC163A;
      color: #fff5eb;
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <form class="unsubscribe" id="unsubscribeForm">
    <h2>Se désinscrire de la newsletter</h2>
    <label for="email">Entrez votre email :</label>
    <input type="email" name="email" id="email" required placeholder="Votre email">
    <button type="submit">Se désinscrire</button>
    <div id="message"></div>
  </form>

  <div class="bottom-links">
    <a class="nav-button" href="/index.php">Accueil</a>
    <a class="nav-button" href="/newsletter/newsletter.php">S’inscrire</a>
  </div>

  <script>
    document.getElementById('unsubscribeForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const email = document.getElementById('email').value;

      fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
      })
      .then(response => response.json())
      .then(data => {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = data.message;
        messageDiv.style.color = data.success ? 'green' : 'red';
      })
      .catch(() => {
        document.getElementById('message').textContent = "Une erreur est survenue.";
      });
    });
  </script>

</body>
</html>
