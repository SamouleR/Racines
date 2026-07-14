<?php
session_start();

$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
if (!$link) {
    throw new Exception("Échec de la connexion MySQL: " . mysqli_connect_error());
}

$confirmation = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = htmlspecialchars(trim($_POST['fname']));
    $nom = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = nl2br(htmlspecialchars(trim($_POST['msg'])));

    // hCaptcha
    $hcaptcha_response = $_POST['h-captcha-response'] ?? '';
    $secret = 'VOTRE_CLE_SECRETE_HCAPTCHA'; // ← Remplace par ta clé secrète hCaptcha

    $verify_response = file_get_contents("https://hcaptcha.com/siteverify?secret=$secret&response=$hcaptcha_response");
    $captcha_data = json_decode($verify_response);

    if (!$captcha_data->success) {
        $confirmation = "❌ Vérification hCaptcha échouée. Veuillez réessayer.";
    } elseif (!$email) {
        $confirmation = "Adresse email invalide.";
    } else {
        $to = "ralaikoa.samuel@gmail.com";
        $subject = "📬 Nouveau message de contact - Racines";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: \"$prenom $nom\" <$email>\r\n";

        $logoUrl = "racines.ralikoa.com/img/racines-logo.png"; // ← Mets ici l’URL réelle de ton logo

        $body = "
<html>
<head>
  <meta charset='UTF-8'>
  <link href='https://fonts.googleapis.com/css2?family=Sora:wght@400;600&display=swap' rel='stylesheet'>
</head>
<body style='font-family: Sora, sans-serif; background-color: #FFF5EB; padding: 20px; margin: 0;'>

  <div style='max-width: 600px; margin: auto; background-color: #FFD9A8; border-radius: 12px; overflow: hidden; box-shadow: 0 0 10px #BC163A;'>

    <div style='background-color: #FFF5EB; padding: 20px 30px; text-align: center;'>
      <img src='$logoUrl' alt='Logo Racines' style='max-width: 150px; height: auto; margin-bottom: 10px;'>
      <h2 style='color: #BC163A; margin: 0;'>📩 Nouveau message reçu</h2>
    </div>

    <div style='padding: 20px 30px; background-color: #FFFFFF; color: #000000;'>
      <p><strong style='color:#7D6852;'> Prénom :</strong> $prenom</p>
      <p><strong style='color:#7D6852;'> Nom :</strong> $nom</p>
      <p><strong style='color:#7D6852;'> Email :</strong> $email</p>
      <p><strong style='color:#7D6852;'> Message :</strong><br>$message</p>
    </div>

    <div style='background-color: #BC163A; text-align: center; padding: 15px; color: #FFF; font-size: 14px;'>
      Racines - Arts & Traditions Populaires
    </div>

  </div>

</body>
</html>";

        if (mail($to, $subject, $body, $headers)) {
            $confirmation = "✅ Merci, votre message a bien été envoyé !";
        } else {
            $confirmation = "❌ Une erreur est survenue lors de l'envoi du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nous contacter - Racines</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
  <style>
   body {
  font-family: 'Sora', sans-serif;
  background-color: #fff5eb;
  margin: 0;
  padding: 0;
}

.form-container {
  max-width: 750px;
  margin: 80px auto; /* ↑ Augmenté ici : espace haut/bas */
  background-color: #FFF5EB;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 0 12px #BC163A;
}

.titre {
  text-align: center;
  color: #BC163A;
  margin: 10px 0 30px; /* Ajoute de l’espace en dessous du titre */
}

.row {
  display: flex;
  gap: 20px;
}

.row .field {
  flex: 1;
}

.field {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
}

input[type="text"],
input[type="email"],
textarea {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 16px;
  box-sizing: border-box;
}

textarea {
  resize: vertical;
}

input[type="submit"] {
  background-color: #BC163A;
  color: white;
  padding: 12px 25px;
  border: none;
  font-size: 16px;
  border-radius: 50px;
  cursor: pointer;
  transition: background-color 0.3s;
  display: block;
  margin: 40px auto 0; /* ↑ Ajoute une marge avant le bouton */
  box-shadow: 0 0 8px #BC163A;
}

input[type="submit"]:hover {
  background-color: #a51230;
}

.confirmation {
  text-align: center;
  font-weight: bold;
  color: #1d7f5f;
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .row {
    flex-direction: column;
  }

  .form-container {
    padding: 20px;
    margin: 40px 20px; /* ↑ Augmente aussi la marge sur mobile */
  }

  input[type="submit"] {
    width: 100%;
  }
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
  <h1 class="titre">Nous contacter</h1>
  <h2 class="titre">Une question à nous poser ?<br>Écris-nous un mail en renseignant tes infos !</h2>

  <div class="form-container">
    <?php if ($confirmation): ?>
      <div class="confirmation"><?= $confirmation ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="row">
        <div class="field">
          <label for="fname">Prénom :</label>
          <input type="text" name="fname" id="fname" required>
        </div>
        <div class="field">
          <label for="name">Nom :</label>
          <input type="text" name="name" id="name" required>
        </div>
      </div>

      <div class="field">
        <label for="email">Adresse mail :</label>
        <input type="email" name="email" id="email" required>
      </div>

      <div class="field">
        <label for="msg">Message :</label>
        <textarea name="msg" id="msg" rows="8" required placeholder="Écris ton message ici..."></textarea>
      </div>

      <!-- ✅ hCaptcha ici -->
      <div class="h-captcha" data-sitekey="f910dbbe-fbdc-4d28-8356-461421e062d4"></div>

      <input type="submit" value="Envoyer">
    </form>
  </div>
</main>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
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
