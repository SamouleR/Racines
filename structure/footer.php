<?php
session_start();

$link = mysqli_connect("localhost", "u237218091_racine", "racineSSJJ1234", "u237218091_racine");
if (!$link) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Traitement du formulaire
$responseMessage = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $responseMessage = "Email invalide.";
    } else {
        $stmt = $link->prepare("SELECT id FROM newsletter WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $responseMessage = "Cet email est déjà inscrit.";
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

                mail($email, $subject, $message, $headers);
                $responseMessage = "Inscription réussie ! Un email de bienvenue a été envoyé.";
            } else {
                $responseMessage = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer Racines</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reset et styles de base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      
    }
    
    body {
      font-family: 'Sora', sans-serif;
    }
    
    /* Styles du footer */
    .footer {
      background-color: #FFD9A8;
      color: #7D6852;
      padding: 60px 40px 20px;
      width: 100%;
    }
    
    .footer-main {
      display: flex;
      justify-content: space-between;
      gap: 60px;
      max-width: 1200px;
      margin: 0 auto 40px;
    }
    
    /* Colonne logo */
    .footer-logo-column {
      flex: 0 0 30%;
      min-width: 280px;
      margin-right: auto;
    }
    
    .footer-logo {
      margin-bottom: 20px;
    }
    
    .footer-logo svg {
      width: 200px;
      height: auto;
      display: block;
    }
    
    .footer-desc {
      font-size: 0.95rem;
      line-height: 1.6;
      max-width: 280px;
    }
    
    /* Autres colonnes */
    .footer-column {
      flex: 1;
      min-width: 160px;
    }
    
    .footer-column h3 {
      font-size: 1.2rem;
      color: #BC163A;
      margin-bottom: 15px;
      padding-bottom: 5px;
      border-bottom: 2px solid #BC163A;
      display: inline-block;
    }
    
    .footer-links {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    
    .footer-links a {
      color: #A72642;
      text-decoration: none;
      font-size: 0.95rem;
      transition: color 0.3s;
    }
    
    .footer-links a:hover {
      color: #BC163A;
      text-decoration: underline;
    }
    
    .footer-contact p {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
      font-size: 0.95rem;
    }
    
    .footer-social-icons {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .footer-social-icons a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      background-color: rgba(189, 22, 58, 0.1);
      border-radius: 8px;
      transition: background-color 0.3s;
    }
    
    .footer-social-icons a:hover {
      background-color: rgba(189, 22, 58, 0.2);
    }
    
    .footer-social-icons img {
      width: 20px;
      height: 20px;
    }
    
    /* Newsletter */
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
    .newsletter-unsubscribe {
      margin-top: 15px;
      text-align: center;
    }
    .newsletter-unsubscribe a {
      color: #BC163A;
      text-decoration: underline;
    }
    /* Footer info */
    .footer-info {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(125, 104, 82, 0.2);
      font-size: 0.9rem;
    }
    
    .footer-legal {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 15px;
    }
    
    .footer-legal a {
      color: #A72642;
      text-decoration: none;
      font-size: 0.85rem;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
      .footer-main {
        gap: 40px;
      }
      
      .footer-logo-column {
        min-width: 240px;
      }
    }
    
    @media (max-width: 768px) {
      .footer {
        padding: 40px 20px 20px;
      }
      
      .footer-main {
        flex-wrap: wrap;
        gap: 30px;
      }
      
      .footer-logo-column {
        flex: 1 1 100%;
        text-align: center;
        margin-right: 0;
      }
      
      .footer-logo svg {
        margin: 0 auto;
      }
      
      .footer-desc {
        margin: 0 auto;
      }
      
      .footer-column {
        flex: 1 1 calc(50% - 20px);
      }
    }
    
    @media (max-width: 480px) {
      .footer-column {
        flex: 1 1 100%;
      }
      
      .footer-legal {
        flex-direction: column;
        gap: 8px;
      }
    }
  </style>
</head>
<body>

<footer class="footer">
  <div class="footer-main">
    <!-- Colonne logo + description -->
    <div class="footer-logo-column">
      <div class="footer-logo">
        <svg width="200" viewBox="0 0 514 124" fill="#7D6852" xmlns="http://www.w3.org/2000/svg">
          <path d="M505.636 42.3642L507.784 62.6197L505.636 63.2335C495.508 51.1109 489.063 45.7401 480.47 45.7401C474.025 45.7401 469.882 48.8091 469.882 54.3334C469.882 62.7732 479.549 65.5353 490.444 70.1388C501.953 75.0492 513.308 81.4941 513.308 97.9133C513.308 116.481 497.963 124 479.396 124C469.575 124 459.754 121.852 452.542 118.936L450.24 95.9185L452.389 95.3047C462.516 109.729 471.723 118.015 483.386 118.015C491.825 118.015 494.741 113.872 494.741 108.962C494.741 100.829 485.841 97.6065 476.02 93.3098C464.358 88.0925 451.468 82.4148 451.468 65.2284C451.468 47.1212 466.813 39.7556 485.38 39.7556C492.746 39.7556 500.419 40.9832 505.636 42.3642Z"/>
          <path d="M421.233 104.972C430.133 104.972 435.964 102.21 441.028 97.453L442.869 98.6806C438.266 113.719 426.143 124 407.729 124C383.944 124 369.366 106.66 369.366 83.3355C369.366 57.7093 386.553 39.7556 409.11 39.7556C431.974 39.7556 443.79 58.7834 443.023 77.351H394.992C396.68 97.7599 407.422 104.972 421.233 104.972ZM408.803 45.7401C400.21 45.7401 394.839 57.7093 394.839 71.0595L418.931 70.4457V64.3077C418.931 51.2644 414.941 45.7401 408.803 45.7401Z"/>
          <path d="M275.855 121.545V121.238C278.924 112.798 280.612 105.893 280.612 91.0082V76.4304C280.612 63.3871 278.003 56.0214 271.098 48.6558V48.3489L304.857 38.9884V55.2542L305.625 55.4076C309.921 48.3489 319.435 39.7557 333.706 39.7557C348.437 39.7557 357.798 48.5023 357.798 62.4664V91.0082C357.798 105.586 359.179 112.798 362.248 121.238V121.545H328.335V121.238C331.404 112.798 332.785 105.433 332.785 91.3151V65.9957C332.785 59.3973 329.409 54.4869 321.277 54.4869C314.678 54.4869 309.001 57.8628 305.625 62.6198V91.1616C305.625 105.433 307.006 112.798 309.921 121.238V121.545H275.855Z"/>
          <path d="M230.974 121.545V121.238C233.583 112.798 235.577 105.586 235.577 91.0082V76.7373C235.577 63.8474 232.969 56.3283 226.063 48.9627V48.3489L260.59 38.9884V91.1616C260.59 105.433 261.971 112.798 264.886 121.238V121.545H230.974Z"/>
          <path d="M220.473 98.3737L222.467 99.6013C218.017 114.026 205.741 124 187.941 124C165.691 124 150.346 108.195 150.346 83.7959C150.346 58.0162 169.067 39.7556 195.153 39.7556C205.434 39.7556 212.647 41.9039 217.25 43.5918L219.705 66.456L217.71 67.0698C208.043 54.3334 199.91 45.7401 192.238 45.7401C183.338 45.7401 175.818 55.1006 175.818 73.3612C175.818 96.0719 187.174 105.432 201.138 105.432C209.271 105.432 216.483 102.67 220.473 98.3737Z"/>
          <path d="M85.6772 124C75.0891 124 66.9562 117.708 66.9562 106.507C66.9562 87.7856 90.1272 79.1924 109.002 75.0492V70.4457C109.002 60.6248 104.705 56.3282 93.1962 56.3282C86.291 56.3282 79.2322 57.8627 72.02 60.9317L70.1786 58.7834C78.0046 48.9626 91.2014 39.7556 108.541 39.7556C126.035 39.7556 134.014 49.2695 134.014 63.0801V100.215C134.014 105.126 135.702 108.501 140.919 108.501C143.835 108.501 146.29 107.581 148.745 105.279L150.587 106.813C148.132 112.645 142.3 123.847 127.723 123.847C117.135 123.847 111.303 118.015 109.769 110.65C105.012 117.555 96.2653 124 85.6772 124ZM88.8996 98.6806C88.8996 104.512 93.0428 108.808 99.7946 108.808C103.631 108.808 106.853 107.427 109.002 105.893V81.0338C96.1118 84.7166 88.8996 91.0081 88.8996 98.6806Z"/>
          <path d="M4.91042 121.545V121.238C7.82598 112.798 9.51394 105.893 9.51394 91.0082V76.4304C9.51394 63.3871 6.90528 56.0214 0 48.6558V48.3489L33.7591 38.9884V59.2439C41.2782 47.2747 49.8715 36.6866 65.8303 39.7557L61.6872 69.0647L60.4596 69.2182C54.6284 65.075 46.4956 62.1595 42.1989 62.1595C39.5903 62.1595 36.8282 64.0009 34.5264 66.9164V91.1616C34.5264 105.433 35.9075 112.798 38.823 121.238V121.545H4.91042Z"/>
          <path d="M242.236 1.65672C244.201 -0.55224 247.653 -0.552239 249.617 1.65672L251.037 3.254C251.913 4.23899 253.146 4.83293 254.463 4.90361L256.597 5.01822C259.549 5.17673 261.701 7.87551 261.199 10.7885L260.836 12.8949C260.612 14.1939 260.916 15.5284 261.682 16.6016L262.923 18.3418C264.639 20.7484 263.871 24.1137 261.28 25.5372L259.407 26.5666C258.252 27.2013 257.398 28.2716 257.037 29.5391L256.45 31.5945C255.639 34.4369 252.529 35.9347 249.8 34.7967L247.828 33.9739C246.611 33.4665 245.242 33.4665 244.026 33.9739L242.053 34.7967C239.325 35.9347 236.215 34.4369 235.403 31.5944L234.817 29.5391C234.455 28.2716 233.601 27.2013 232.446 26.5666L230.573 25.5372C227.982 24.1137 227.214 20.7484 228.93 18.3418L230.172 16.6016C230.937 15.5284 231.242 14.1939 231.018 12.8949L230.654 10.7885C230.152 7.8755 232.304 5.17673 235.256 5.01822L237.39 4.90361C238.707 4.83293 239.94 4.23899 240.816 3.254L242.236 1.65672Z"/>
          <path d="M505.636 42.3642..." fill="#FFD9A8"/>
        </svg>
      </div>
      <p class="footer-desc">Association culturelle mettant en lumière les traditions et événements locaux depuis 2025.</p>
    </div>

    <!-- Navigation -->
    <div class="footer-column">
      <h3>Navigation</h3>
      <nav class="footer-links">
        <a href="index.html">Accueil</a>
        <a href="spectacles.html">Événements</a>
        <a href="/traditions.php">Traditions</a>
        <?php if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in']) : ?>
            <a href="/membre/member_dashboard.php" class="nav-link">Mon compte</a>
        <?php else : ?>
            <a href="/membre/membre.php" class="nav-link">Membres</a>
        <?php endif; ?></li>
        <a href="contact.html">Contact</a>
      </nav>
    </div>

    <!-- Contact -->
    <div class="footer-column footer-contact">
      <h3>Contact</h3>
      <p><i class="fas fa-envelope"></i> contact@racines.ralaikoa.com</p>
      <p><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
      <p><i class="fas fa-map-marker-alt"></i> France</p>
    </div>

    <!-- Réseaux + Newsletter -->
    <div class="footer-column">
      <h3>Réseaux sociaux</h3>
      <div class="footer-social-icons">
        <a href="#"><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram"></a>
        <a href="#"><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" alt="TikTok"></a>
        <a href="#"><img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn"></a>
      </div>
       <form class="newsletter" method="POST">
    <label for="email">Abonnez-vous à notre newsletter</label>
    <div class="newsletter-input">
      <input type="email" id="email" name="email" placeholder="Votre email" required>
      <button type="submit">Envoyer</button>
    </div>
   <div class="newsletter-unsubscribe">
  <a href="unsubscribe.php">Se désinscrire</a>
</div>

  </form>

    </div>
  </div>

  <!-- Mentions légales + Copyright -->
  <div class="footer-info">
    <p>© 2025 Racines. Tous droits réservés.</p>
    <div class="footer-legal">
      <a href="/copyright.html#mentions-legales">Mentions légales</a>
      <a href="/copyright.html#rgpd">RGPD</a>
      <a href="/copyright.html#cgu">Confidentialité</a>
      <a href="/copyright.html#cookies">Utilisation des cookies</a>
    </div>
  </div>
</footer>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const emailInput = document.getElementById('email');
    const messageDiv = document.getElementById('newsletter-message');
    const unsubscribeLink = document.getElementById('unsubscribe-link');
    
    // Gestion de la newsletter
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = emailInput.value.trim();
      
      if (!validateEmail(email)) {
        showMessage('Merci de saisir une adresse email valide.', 'error');
        return;
      }
      
      showMessage('Envoi en cours...', 'loading');
      
      try {
        // Simulation d'envoi (remplacer par un vrai appel API)
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // En production, utiliser :
        // const response = await fetch('/api/newsletter', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify({ email, action: 'subscribe' })
        // });
        // const result = await response.json();
        
        const result = { success: true, message: 'Merci pour votre inscription !' };
        
        if (result.success) {
          showMessage(result.message, 'success');
          emailInput.value = '';
        } else {
          showMessage(result.message || 'Une erreur est survenue', 'error');
        }
      } catch (error) {
        showMessage('Erreur serveur, veuillez réessayer plus tard.', 'error');
      }
    });
    
    // Gestion de la désinscription
    unsubscribeLink.addEventListener('click', async (e) => {
      e.preventDefault();
      const email = prompt("Entrez votre email pour vous désinscrire :");
      
      if (!email || !validateEmail(email)) {
        alert("Adresse email invalide.");
        return;
      }
      
      showMessage('Traitement en cours...', 'loading');
      
      try {
        // Simulation de désinscription
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // En production, utiliser :
        // const response = await fetch('/api/newsletter', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify({ email, action: 'unsubscribe' })
        // });
        // const result = await response.json();
        
        const result = { success: true, message: 'Vous avez été désinscrit avec succès.' };
        
        if (result.success) {
          showMessage(result.message, 'success');
        } else {
          showMessage(result.message || 'Une erreur est survenue', 'error');
        }
      } catch (error) {
        showMessage('Erreur serveur, veuillez réessayer plus tard.', 'error');
      }
    });
    
    // Fonctions utilitaires
    function validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function showMessage(message, type) {
      messageDiv.textContent = message;
      messageDiv.style.color = type === 'error' ? '#d32f2f' : 
                               type === 'success' ? '#388e3c' : '#7D6852';
      messageDiv.style.fontWeight = type === 'loading' ? 'normal' : '600';
    }
  });
</script>

</body>
</html>
