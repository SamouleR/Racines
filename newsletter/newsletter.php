<?php
session_start();

$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
if (!$link) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Traitement du formulaire
$responseMessage = "";
$responseClass = ""; // Pour gérer le style du message

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $responseMessage = "Email invalide.";
        $responseClass = "error";
    } else {
        $stmt = $link->prepare("SELECT id FROM newsletter WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $responseMessage = "Cet email est déjà inscrit à notre newsletter.";
            $responseClass = "error";
        } else {
            $stmt = $link->prepare("INSERT INTO newsletter (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                // Envoi d'un mail de bienvenue avec style HTML
                $subject = "Bienvenue dans notre newsletter !";
                $message = '
                <html>
                <head>
                  <style>
                    body { font-family: "Sora", sans-serif; background-color: #fff5eb; color: #BC163A; }
                    .container { padding: 20px; }
                    .title { font-size: 20px; font-weight: bold; }
                  </style>
                </head>
                <body>
                  <div class="container">
                    <p class="title">Bienvenue !</p>
                    <p>Merci de vous être inscrit à notre newsletter. ❤️</p>
                  </div>
                </body>
                </html>';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: contact@tonsite.com' . "\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    $responseMessage = "Inscription réussie ! Un email de bienvenue a été envoyé.";
                    $responseClass = "success";
                } else {
                    $responseMessage = "Inscription réussie mais l'email de bienvenue n'a pas pu être envoyé.";
                    $responseClass = "warning";
                }
            } else {
                $responseMessage = "Erreur lors de l'inscription.";
                $responseClass = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Newsletter - Racines</title>
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
    }
    .newsletter {
      max-width: 400px;
      width: 100%;
    }
    label {
      font-weight: 600;
      margin-bottom: 10px;
      display: block;
    }
    .newsletter-input {
      display: flex;
      border: 2px solid #BC163A;
      border-radius: 30px;
      overflow: hidden;
      margin-bottom: 10px;
    }
    .newsletter-input input {
      flex: 1;
      border: none;
      padding: 12px 15px;
      background: transparent;
      font-size: 0.95rem;
      color: #BC163A;
      outline: none;
    }
    .newsletter-input button {
      background-color: #BC163A;
      color: #fff5eb;
      padding: 0 20px;
      border: none;
      cursor: pointer;
      font-size: 1rem;
    }
    #newsletter-message {
      margin-top: 10px;
      font-weight: bold;
    }
    .error {
      color: #ff0000;
    }
    .success {
      color: #008000;
    }
    .warning {
      color: #ffa500;
    }
    .newsletter-unsubscribe {
      margin-top: 15px;
      text-align: center;
    }
    .newsletter-unsubscribe a {
      color: #BC163A;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <form class="newsletter" method="POST">
    <label for="email">Abonnez-vous à notre newsletter</label>
    <div class="newsletter-input">
      <input type="email" id="email" name="email" placeholder="Votre email" required>
      <button type="submit">Envoyer</button>
    </div>
    <?php if (!empty($responseMessage)): ?>
      <div id="newsletter-message" class="<?php echo $responseClass; ?>">
        <?php echo $responseMessage; ?>
      </div>
    <?php endif; ?>
    <div class="newsletter-unsubscribe">
      <a href="unsubscribe.php">Se désinscrire</a>
    </div>
  </form>

</body>
</html>