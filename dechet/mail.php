<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$message_envoye = "";
$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = strip_tags(trim($_POST["nom"] ?? ""));
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
    $sujet = strip_tags(trim($_POST["sujet"] ?? ""));
    $message = strip_tags(trim($_POST["message"] ?? ""));

    if (!$nom || !$email || !$sujet || !$message) {
        $message_erreur = "Veuillez remplir tous les champs correctement.";
    } else {
        $destinataire = "
        gmocellin12@gmail.com"; // <-- Mets ton mail ici

        // Encodage du sujet pour éviter les soucis d'accents
        $sujet_encode = "=?UTF-8?B?" . base64_encode($sujet) . "?=";

        // Préparation des headers pour un mail HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . htmlspecialchars($nom) . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";

        // Contenu HTML du mail (stylisé)
        $contenu_mail = "
        <html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; background-color: #f5f8fa; padding: 20px; }
            .container { background: #fff; padding: 20px; border: 2px solid #4a90e2; border-radius: 8px; }
            h2 { color: #4a90e2; }
            p { font-size: 14px; }
            .info { margin-bottom: 15px; }
            .label { font-weight: bold; color: #4a90e2; }
            .message { background-color: #eef6fc; padding: 15px; border-radius: 5px; white-space: pre-wrap; }
          </style>
        </head>
        <body>
          <div class='container'>
            <h2>Nouveau message depuis le formulaire de contact</h2>
            <p class='info'><span class='label'>Nom :</span> " . htmlspecialchars($nom) . "</p>
            <p class='info'><span class='label'>Email :</span> " . htmlspecialchars($email) . "</p>
            <p class='info'><span class='label'>Sujet :</span> " . htmlspecialchars($sujet) . "</p>
            <p class='label'>Message :</p>
            <div class='message'>" . nl2br(htmlspecialchars($message)) . "</div>
          </div>
        </body>
        </html>
        ";

        // Envoi du mail
        if (mail($destinataire, $sujet_encode, $contenu_mail, $headers)) {
            $message_envoye = "Votre message a bien été envoyé, merci !";
            // Optionnel : vider les champs après envoi réussi
            $_POST = [];
        } else {
            $message_erreur = "Une erreur est survenue lors de l'envoi du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Formulaire de Contact</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f5f8fa;
    padding: 30px;
  }
  .form-container {
    max-width: 500px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 8px;
    border: 2px solid #4a90e2;
    box-shadow: 0 0 15px rgba(74, 144, 226, 0.3);
  }
  h2 {
    color: #4a90e2;
    margin-bottom: 20px;
    text-align: center;
  }
  label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
  }
  input[type=text], input[type=email], textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 14px;
  }
  textarea {
    resize: vertical;
    height: 120px;
  }
  button {
    margin-top: 20px;
    background-color: #4a90e2;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }
  button:hover {
    background-color: #357ABD;
  }
  .success {
    color: green;
    margin-top: 15px;
    text-align: center;
  }
  .error {
    color: red;
    margin-top: 15px;
    text-align: center;
  }
</style>
</head>
<body>

<div class="form-container">
  <h2>Contactez-nous</h2>

  <?php if ($message_envoye): ?>
    <p class="success"><?= htmlspecialchars($message_envoye) ?></p>
  <?php elseif ($message_erreur): ?>
    <p class="error"><?= htmlspecialchars($message_erreur) ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="nom">Nom complet</label>
    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">

    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label for="sujet">Sujet</label>
    <input type="text" id="sujet" name="sujet" required value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">

    <label for="message">Message</label>
    <textarea id="message" name="message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

    <button type="submit">Envoyer</button>
  </form>
</div>

</body>
</html>
