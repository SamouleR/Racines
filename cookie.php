<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff4ee;
      margin: 0;
      padding: 0;
    }

    .mini-popup {
      position: fixed;
      bottom: 20px;
      left: 20px;
      background: white;
      color: #c22138;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 1em;
      width: 240px;
      z-index: 1000;
      display: none;
    }

    .mini-popup img {
      width: 40px;
      margin-bottom: 0.5em;
    }

    .mini-popup p {
      text-align: center;
      margin: 0 0 0.5em 0;
    }

    .mini-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 0.5em;
    }

    .mini-buttons button {
      flex: 1;
      margin: 0 2px;
      border: none;
      padding: 0.5em;
      font-weight: bold;
      cursor: pointer;
      border-radius: 6px;
    }

    .btn-policy {
      background: white;
      color: #c22138;
      border: 1px solid #c22138;
    }

    .btn-ok {
      background: #c22138;
      color: white;
    }

    .floating-mask {
      display: none;
      position: fixed;
      bottom: 20px;
      left: 20px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: transparent;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 999;
      cursor: pointer;
      padding: 0;
    }

    .floating-mask img {
      width: 100%;
      height: auto;
      border-radius: 50%;
    }

    .cookie-popup {
      display: none;
      position: fixed;
      top: 10%;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      max-width: 420px;
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 1001;
    }

    .cookie-header {
      background: #c22138;
      padding: 1em;
      color: white;
      text-align: center;
    }

    .cookie-body {
      padding: 1.5em;
      color: #c22138;
    }

    .cookie-icon {
      display: block;
      margin: 0 auto 1em;
      width: 50px;
    }

    .cookie-section {
      margin-bottom: 1.2em;
    }

    .btn-group {
      display: flex;
      justify-content: space-between;
      gap: 0.5em;
      margin-top: 0.5em;
    }

    .btn {
      padding: 0.5em 1em;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-black { background: black; color: white; }
    .btn-white { background: white; border: 1px solid #ccc; }
    .btn-beige { background: #ffdbb7; color: #c22138; }

    .close-btn {
      text-align: right;
      padding: 0.5em 1em 0 0;
      font-weight: bold;
      color: #c22138;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Mini popup -->
  <div class="mini-popup" id="miniPopup">
    <img src="/img/patterns/rouge/masque.png" alt="Masque" />
    <p>Avant de plonger dans vos racines,<br><strong>Acceptez ces cookies</strong></p>
    <div class="mini-buttons">
      <button class="btn-policy" onclick="showFullPopup()">Politique</button>
      <button class="btn-ok" onclick="acceptAllCookies()">Ok</button>
    </div>
  </div>

  <!-- Masque -->
  <div class="floating-mask" id="floatingMask" onclick="showFullPopup()">
    <img src="/img/patterns/rouge/masque.png" alt="Masque" />
  </div>

  <!-- Grand popup -->
  <div class="cookie-popup" id="cookiePopup">
    <div class="close-btn" onclick="closeFullPopup()">FERMER</div>
    <div class="cookie-header">
      En autorisant ces services tiers, vous acceptez les cookies
    </div>
    <div class="cookie-body">
      <img src="/img/patterns/rouge/masque.png" alt="Masque" class="cookie-icon" />

      <div class="cookie-section">
        <strong>Préférences globales</strong>
        <div class="btn-group">
          <button class="btn btn-white" onclick="refuseAllCookies()">Tout refuser</button>
          <button class="btn btn-black" onclick="acceptAllCookies()">Tout accepter</button>
        </div>
      </div>

      <div class="cookie-section">
        <strong>Marketing</strong>
        <div class="btn-group">
          <button class="btn btn-white" onclick="setCookie('cookieMarketing', true)">Autoriser</button>
          <button class="btn btn-black" onclick="setCookie('cookieMarketing', false)">Interdire</button>
        </div>
      </div>

      <div class="cookie-section">
        <strong>Statistiques</strong>
        <div class="btn-group">
          <button class="btn btn-white" onclick="setCookie('cookieStats', true)">Autoriser</button>
          <button class="btn btn-black" onclick="setCookie('cookieStats', false)">Interdire</button>
        </div>
      </div>

      <div class="btn-group">
        <button class="btn btn-beige" onclick="saveCookies()">ENREGISTRER</button>
      </div>
    </div>
  </div>

  <script>
    const miniPopup = document.getElementById('miniPopup');
    const floatingMask = document.getElementById('floatingMask');
    const cookiePopup = document.getElementById('cookiePopup');

    function acceptAllCookies() {
      localStorage.setItem('cookieConsent', 'true');
      localStorage.setItem('cookieMarketing', 'true');
      localStorage.setItem('cookieStats', 'true');
      hideAllPopups();
    }

    function refuseAllCookies() {
      localStorage.setItem('cookieConsent', 'false');
      localStorage.setItem('cookieMarketing', 'false');
      localStorage.setItem('cookieStats', 'false');
      hideAllPopups();
    }

    function setCookie(key, value) {
      localStorage.setItem(key, value);
    }

    function saveCookies() {
      localStorage.setItem('cookieConsent', 'true');
      hideAllPopups();
    }

    function hideAllPopups() {
      miniPopup.style.display = 'none';
      cookiePopup.style.display = 'none';
      floatingMask.style.display = 'block';
    }

    function showFullPopup() {
      cookiePopup.style.display = 'block';
      miniPopup.style.display = 'none';
    }

    function closeFullPopup() {
      cookiePopup.style.display = 'none';
    }

    window.onload = () => {
      const consent = localStorage.getItem('cookieConsent');
      if (consent === 'true') {
        floatingMask.style.display = 'block';
      } else {
        miniPopup.style.display = 'block';
      }
    };
  </script>
</body>
</html>
