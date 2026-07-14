<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Traditions - Racines</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet" />

<style>
  body {
    margin: 0; 
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #fff5eb;
    font-family: 'Sora', sans-serif;
    overflow-x: hidden;
  }

  /* Section parallaxe avec logo et titre */
  .parallax-section {
    position: relative;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
  }

  .parallax-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
  }

  .parallax-image {
    position: absolute;
    width: 400px;
    opacity: 0.2;
    transition: transform 0.1s linear;
  }

  .parallax-image.left {
    left: -100px;
    top: 20%;
  }

  .parallax-image.right {
    right: -100px;
    top: 30%;
  }

  .logo1 svg {
    width: 1100px;
    height: auto;
    fill: #BC163A;
    margin: 15rem auto;
    display: block;
  }

  .logo1 path {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease forwards;
    animation-fill-mode: forwards;
  }

  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes tiltShake {
    0%   { transform: rotate(0deg); }
    25%  { transform: rotate(-4deg); }
    50%  { transform: rotate(4deg); }
    75%  { transform: rotate(-2deg); }
    100% { transform: rotate(0deg); }
  }

  .logo1 path.shake {
    animation: 
      fadeInUp 0.8s ease forwards,
      tiltShake 0.4s ease 1.1s 1;
    transform-origin: center;
  }

  .logo1 path:nth-child(1) { animation-delay: 1.4s, 2.2s; }
  .logo1 path:nth-child(2) { animation-delay: 1.2s, 2.0s; }
  .logo1 path:nth-child(3) { animation-delay: 1.0s, 1.8s; }
  .logo1 path:nth-child(4) { animation-delay: 0.8s, 1.6s; }
  .logo1 path:nth-child(5) { animation-delay: 0.6s, 1.4s; }
  .logo1 path:nth-child(6) { animation-delay: 0.4s, 1.2s; }
  .logo1 path:nth-child(7) { animation-delay: 0.2s, 1.0s; }
  .logo1 path:nth-child(8) { animation-delay: 1.6s, 2.4s; }
  
  .title-section {
    position: relative;
    z-index: 1;
    padding: 40px 20px;
    text-align: center;
  }

  .title-section h1 {
    color: #BC163A;
    font-size: 3rem;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
  }

.content-section {
  position: relative;
  width: 100%;
  padding: 100px 0;
  background-color: #BC163A; /* Nouvelle couleur de fond */
  text-align: center; /* Centre le texte */
  color: white; /* Facultatif, pour améliorer la lisibilité */
}

.content-section p,
.content-section h1,
.content-section h2,
.content-section h3 {
  font-size: 2rem; /* Augmente la taille du texte */
  line-height: 1.5;
  margin: 0 auto;
  max-width: 800px; /* Pour éviter que le texte soit trop étiré */
}
  .content-parallax-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
  }

  .content-parallax-image {
  position: absolute;
  width: 500px;
  opacity: 0.2; /* Opacité légèrement augmentée */
  transition: transform 0.1s linear;
  filter: brightness(0.9); /* Légère atténuation */
}
  .content-parallax-image.left {
    left: -50px;
    top: 20%;
  }

  .content-parallax-image.right {
    right: -50px;
    bottom: 20%;
  }

  /* Section des cartes en forme de blob */
  .blob-section {
    position: relative;
    padding: 100px 20px;
    margin-top: 100px;
    overflow: hidden;
    z-index: 1;
  }

  .blob-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    min-height: 600px;
  }


  @keyframes blobMorph {
    0% { border-radius: 60% 40% 70% 30% / 50% 60% 40% 70%; }
    50% { border-radius: 50% 60% 40% 70% / 60% 50% 70% 40%; }
    100% { border-radius: 60% 40% 70% 30% / 50% 60% 40% 70%; }
  }

  .cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    padding: 50px;
    position: relative;
    z-index: 1;
  }

  /* Images fixes à gauche et droite des cartes */
  .cards-decoration {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
    z-index: 0;
  }

  .cards-decoration img {
    position: absolute;
    width: 150px;
    opacity: 0.2;
  }

  .cards-decoration .left-img {
    left: 0;
    top: 20%;
  }

  .cards-decoration .right-img {
    right: 0;
    bottom: 20%;
  }

  .flip-card {
    background-color: transparent;
    perspective: 1000px;
    height: 350px; /* Hauteur augmentée pour mieux contenir le texte */
    z-index: 1;
  }

  .flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.8s;
    transform-style: preserve-3d;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  .flip-card:hover .flip-card-inner {
    transform: rotateY(10deg);
  }

  .flip-card.flipped .flip-card-inner {
    transform: rotateY(180deg);
  }

  .flip-card-front, .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 20px;
    overflow: hidden;
  }

  .flip-card-front {
    background-color: #FFF5EB;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .flip-card-front img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .flip-card-back {
    background-color: #FFF5EB;
    color: #7D6852;
    transform: rotateY(180deg);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .flip-card-back h3 {
    color: #BC163A;
    font-size: 1.5rem;
    margin-top: 0;
    margin-bottom: 15px;
    border-bottom: 2px solid #BC163A;
    padding-bottom: 10px;
  }

  .flip-card-back p {
    line-height: 1.6;
    margin-bottom: 0;
    font-size: 1rem; /* Taille de police ajustée */
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 6; /* Nombre de lignes maximum */
    -webkit-box-orient: vertical;
  }

  /* Positionnement organique des cartes */
  .flip-card:nth-child(1) {
    transform: rotate(-3deg);
  }

  .flip-card:nth-child(2) {
    transform: rotate(2deg) translateY(20px);
  }

  .flip-card:nth-child(3) {
    transform: rotate(-1deg) translateY(30px);
  }

  .flip-card:nth-child(4) {
    transform: rotate(4deg) translateY(10px);
  }

  .flip-card:hover {
    transform: translateY(-10px) !important;
    z-index: 2;
  }

  .flip-card.flipped {
    transform: none !important;
  }

  /* Responsive */
  @media (max-width: 900px) {
    .cards-container {
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      padding: 30px;
    }
    
    .flip-card {
      height: 300px;
    }
    
    .flip-card-back {
      padding: 15px;
    }
    
    .flip-card-back h3 {
      font-size: 1.3rem;
    }

    .flip-card-back p {
      font-size: 0.9rem;
      -webkit-line-clamp: 5;
    }

    .logo svg {
      width: 500px;
    }

    .title-section h1 {
      font-size: 2.5rem;
    }

    .cards-decoration img {
      width: 100px;
    }
  }

  @media (max-width: 600px) {
    .title-section h1 {
      font-size: 2rem;
    }
    
    .blob-section {
      padding: 60px 20px;
    }
    
    .cards-container {
      grid-template-columns: 1fr;
      padding: 20px;
    }
    
    .flip-card {
      transform: none !important;
      margin-bottom: 20px;
      height: 300px;
    }
    
    .flip-card:hover {
      transform: translateY(-5px) !important;
    }

    .logo svg {
      width: 300px;
    }

    .parallax-image,
    .content-parallax-image {
      width: 200px;
    }

    .cards-decoration img {
      display: none; /* Masquer les images décoratives sur mobile */
    }
  }
  .heritage-section {
  position: relative;
  width: 100%;
  padding: 100px 20px;
  background-color: #FFF5EB; /* Nouvelle couleur de fond */
  color: #BC163A; /* Texte dans l'ancienne couleur de fond */
  text-align: center;
  overflow: hidden;
}

.heritage-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 10%;
  height: 100%;
  background: url('/img/patterns/rouge/mur.png') no-repeat center center;
  background-size: cover;
  opacity: 0.1;
  pointer-events: none;
  z-index: 0;
}

.heritage-content {
  position: relative;
  max-width: 900px;
  margin: 0 auto;
  z-index: 1;
  font-size: 1.2rem;
  line-height: 1.8;
}

.quiz-intro {
  background-color: #FFF5EB;
  padding: 80px 20px;
  position: relative;
  z-index: 1;
  text-align: center;
  color: #BC163A;
  font-family: sans-serif;
}

.quiz-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 40px;
  position: relative;
  z-index: 1;
}

.quiz-card {
  background-color: #BC163A;
  color: #FFF5EB;
  display: flex;
  flex-wrap: nowrap;
  justify-content: flex-start;
  align-items: center;
  gap: 30px;
  padding: 30px;
  border-radius: 15px;
  max-width: 600px; /* ← Réduction ici */
  margin: 0 auto;
  position: relative;
  z-index: 1;
  text-align: left;
  overflow: visible;
}


.quiz-svg {
  position: absolute;
  right: -240px; /* ajuste selon le débordement souhaité */
  bottom: 0;
  width: 350px;
  height: auto;
  z-index: 0; /* derrière le texte si nécessaire */
}

.quiz-svg svg {
  width: 100%;
  height: auto;
}



.quiz-text {
  font-size: 1rem;
  line-height: 1.6;
  max-width: 500px;
}

.quiz-button {
  display: inline-block;
  margin-top: 20px;
  background-color: #FFF5EB;
  color: #BC163A;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: background-color 0.3s, color 0.3s;
}

.quiz-button:hover {
  background-color: #fce3d9;
  color: #8e0d2c;
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
  <!-- Section parallaxe avec logo et titre -->
  <section class="parallax-section">
    <div class="parallax-bg">
      <img src="/img/patterns/rouge/feuille.webp" alt="bg gauche" class="parallax-image left" id="bgLeft" />
      <img src="/img/patterns/rouge/feuille.png" alt="bg droite" class="parallax-image right" id="bgRight" />
    </div>

    <div class="logo1" aria-label="Logo Racines">
      <svg viewBox="0 0 514 124" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
        <path class="shake" d="M505.636 42.3642L507.784 62.6197L505.636 63.2335C495.508 51.1109 489.063 45.7401 480.47 45.7401C474.025 45.7401 469.882 48.8091 469.882 54.3334C469.882 62.7732 479.549 65.5353 490.444 70.1388C501.953 75.0492 513.308 81.4941 513.308 97.9133C513.308 116.481 497.963 124 479.396 124C469.575 124 459.754 121.852 452.542 118.936L450.24 95.9185L452.389 95.3047C462.516 109.729 471.723 118.015 483.386 118.015C491.825 118.015 494.741 113.872 494.741 108.962C494.741 100.829 485.841 97.6065 476.02 93.3098C464.358 88.0925 451.468 82.4148 451.468 65.2284C451.468 47.1212 466.813 39.7556 485.38 39.7556C492.746 39.7556 500.419 40.9832 505.636 42.3642Z"></path>
        <path class="shake" d="M421.233 104.972C430.133 104.972 435.964 102.21 441.028 97.453L442.869 98.6806C438.266 113.719 426.143 124 407.729 124C383.944 124 369.366 106.66 369.366 83.3355C369.366 57.7093 386.553 39.7556 409.11 39.7556C431.974 39.7556 443.79 58.7834 443.023 77.351H394.992C396.68 97.7599 407.422 104.972 421.233 104.972ZM408.803 45.7401C400.21 45.7401 394.839 57.7093 394.839 71.0595L418.931 70.4457V64.3077C418.931 51.2644 414.941 45.7401 408.803 45.7401Z"></path>
        <path class="shake" d="M275.855 121.545V121.238C278.924 112.798 280.612 105.893 280.612 91.0082V76.4304C280.612 63.3871 278.003 56.0214 271.098 48.6558V48.3489L304.857 38.9884V55.2542L305.625 55.4076C309.921 48.3489 319.435 39.7557 333.706 39.7557C348.437 39.7557 357.798 48.5023 357.798 62.4664V91.0082C357.798 105.586 359.179 112.798 362.248 121.238V121.545H328.335V121.238C331.404 112.798 332.785 105.433 332.785 91.3151V65.9957C332.785 59.3973 329.409 54.4869 321.277 54.4869C314.678 54.4869 309.001 57.8628 305.625 62.6198V91.1616C305.625 105.433 307.006 112.798 309.921 121.238V121.545H275.855Z"></path>
        <path class="shake" d="M230.974 121.545V121.238C233.583 112.798 235.577 105.586 235.577 91.0082V76.7373C235.577 63.8474 232.969 56.3283 226.063 48.9627V48.3489L260.59 38.9884V91.1616C260.59 105.433 261.971 112.798 264.886 121.238V121.545H230.974Z"></path>
        <path class="shake" d="M220.473 98.3737L222.467 99.6013C218.017 114.026 205.741 124 187.941 124C165.691 124 150.346 108.195 150.346 83.7959C150.346 58.0162 169.067 39.7556 195.153 39.7556C205.434 39.7556 212.647 41.9039 217.25 43.5918L219.705 66.456L217.71 67.0698C208.043 54.3334 199.91 45.7401 192.238 45.7401C183.338 45.7401 175.818 55.1006 175.818 73.3612C175.818 96.0719 187.174 105.432 201.138 105.432C209.271 105.432 216.483 102.67 220.473 98.3737Z"></path>
        <path class="shake" d="M85.6772 124C75.0891 124 66.9562 117.708 66.9562 106.507C66.9562 87.7856 90.1272 79.1924 109.002 75.0492V70.4457C109.002 60.6248 104.705 56.3282 93.1962 56.3282C86.291 56.3282 79.2322 57.8627 72.02 60.9317L70.1786 58.7834C78.0046 48.9626 91.2014 39.7556 108.541 39.7556C126.035 39.7556 134.014 49.2695 134.014 63.0801V100.215C134.014 105.126 135.702 108.501 140.919 108.501C143.835 108.501 146.29 107.581 148.745 105.279L150.587 106.813C148.132 112.645 142.3 123.847 127.723 123.847C117.135 123.847 111.303 118.015 109.769 110.65C105.012 117.555 96.2653 124 85.6772 124ZM88.8996 98.6806C88.8996 104.512 93.0428 108.808 99.7946 108.808C103.631 108.808 106.853 107.427 109.002 105.893V81.0338C96.1118 84.7166 88.8996 91.0081 88.8996 98.6806Z"></path>
        <path class="shake" d="M4.91042 121.545V121.238C7.82598 112.798 9.51394 105.586 9.51394 91.0082V76.7373C9.51394 63.8474 6.90528 56.3283 0 48.9627V48.3489L33.7591 38.9884V59.2439C41.2782 47.2747 49.8715 36.6866 65.8303 39.7557L61.6872 69.0647L60.4596 69.2182C54.6284 65.075 46.4956 62.1595 42.1989 62.1595C39.5903 62.1595 36.8282 64.0009 34.5264 66.9164V91.1616C34.5264 105.433 35.9075 112.798 38.823 121.238V121.545H4.91042Z"></path>
        <path class="shake" d="M242.236 1.65672C244.201 -0.55224 247.653 -0.552239 249.617 1.65672L251.037 3.254C251.913 4.23899 253.146 4.83293 254.463 4.90361L256.597 5.01822C259.549 5.17673 261.701 7.87551 261.199 10.7885L260.836 12.8949C260.612 14.1939 260.916 15.5284 261.682 16.6016L262.923 18.3418C264.639 20.7484 263.871 24.1137 261.28 25.5372L259.407 26.5666C258.252 27.2013 257.398 28.2716 257.037 29.5391L256.45 31.5945C255.639 34.4369 252.529 35.9347 249.8 34.7967L247.828 33.9739C246.611 33.4665 245.242 33.4665 244.026 33.9739L242.053 34.7967C239.325 35.9347 236.215 34.4369 235.403 31.5944L234.817 29.5391C234.455 28.2716 233.601 27.2013 232.446 26.5666L230.573 25.5372C227.982 24.1137 227.214 20.7484 228.93 18.3418L230.172 16.6016C230.937 15.5284 231.242 14.1939 231.018 12.8949L230.654 10.7885C230.152 7.8755 232.304 5.17673 235.256 5.01822L237.39 4.90361C238.707 4.83293 239.94 4.23899 240.816 3.254L242.236 1.65672Z"></path>
      </svg>
    </div>

  
  </section>

  <!-- Section de contenu avec nouveau fond parallaxe -->
<section class="content-section">
  <h1>Les arts et traditions</h1>
  <div class="content-parallax-bg">
    <img src="/img/patterns/rouge/feuille2.webp" alt="nouveau bg gauche" class="content-parallax-image left" id="contentBgLeft" />
    <img src="/img/patterns/rouge/motifs3.png" alt="nouveau bg droite" class="content-parallax-image right" id="contentBgRight" />
  </div>

    <!-- Section des cartes en forme de blob -->
    <section class="blob-section">
      <div class="blob-container">
        <div class="blob-background"></div>
        <!-- Images décoratives fixes -->
        <div class="cards-decoration">
          <img src="/img/patterns/rouge/vase1.png" alt="Décor gauche" class="left-img">
          <img src="/img/patterns/rouge/vase2.png" alt="Décor droite" class="right-img">
        </div>
        <div class="cards-container">
          <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="/img/jeu/lascaux.jpg" alt="Art préhistorique">
              </div>
              <div class="flip-card-back">
                <h3>Préhistoire</h3>
                <p>Peintures et gravures statuettes en os, pierre. Expression artistique primitive sur les parois des grottes, premières formes d'art rupestre.</p>
              </div>
            </div>
          </div>
          <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="/img/jeu/accordeon.jpg" alt="Art antique">
              </div>
              <div class="flip-card-back">
                <h3>Antiquité</h3>
                <p>Mosaïques, sculptures fêtes locales, artisanat. Développement des techniques artistiques et des célébrations culturelles.</p>
              </div>
            </div>
          </div>
          <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="/img/jeu/moyen_age.jpg" alt="Art médiéval">
              </div>
              <div class="flip-card-back">
                <h3>Moyen Âge</h3>
                <p>Poésie, théâtre cour royale, fêtes raffinées. Art au service de la religion et des cours princières.</p>
              </div>
            </div>
          </div>
          <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="/img/jeu/renaissance.jpg" alt="Art Renaissance">
              </div>
              <div class="flip-card-back">
                <h3>Renaissance</h3>
                <p>Décoration somptueuse fêtes de cour, ballets. Renouveau artistique et explosion des arts décoratifs.</p>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
  
  
  <section class="heritage-section">
  <div class="heritage-bg"></div>
  <div class="heritage-content">
    <p>
      <strong>Les arts et traditions, c'est pas juste des trucs vieux poussiéreux !</strong> 
      C'est un vrai trésor d'histoires folles, d'images incroyables, et de créativité qui traverse les siècles — 
      et ça continue de vivre aujourd'hui. Prêt·e à découvrir comment les ancêtres ont marqué notre culture 
      et comment ça influence ta musique, tes fringues, et même tes jeux ? 
      <strong>C'est parti !</strong>
    </p>
  </div>
</section>
<section class="quiz-intro">
  <h2 class="quiz-title">Teste tes connaissances</h2>
  <div class="quiz-card">
    
    <div class="quiz-svg">
      <!-- Le SVG directement intégré -->
      <svg width="100" height="100" viewBox="0 0 385 440" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M221.386 252.553C229.418 250.292 254.846 246.002 256.663 261.371C270.214 375.819 370.788 364.461 342.511 382.634C341.31 383.409 328.412 384.653 321.628 392C315.861 398.246 316.446 410.527 311.985 413.22C291.524 399.11 282.46 371.85 273.438 349.977C268.587 338.265 247.046 300.058 244.945 300.791C236.017 300.169 315.751 467.477 200.082 436.074C180.685 430.808 179.2 394.999 162.32 406.874C130.677 429.153 72.3638 413.125 103.28 388.796C128.739 368.756 133.679 326.786 129.261 334.961C128.665 336.073 92.1826 408.487 72.6798 403.274C61.8303 400.375 66.0121 389.028 55.2943 388.221C28.9816 386.234 28.9922 362.152 92.6829 311.965C137.661 276.519 140.905 257.661 146.614 261.788C173.496 275.982 199.766 258.62 221.386 252.548V252.553ZM18.5429 292.99C29.9613 284.388 43.7917 281.605 45.5403 282.101C46.4883 283.809 47.8103 285.843 49.6694 287.914C52.6135 291.188 55.7104 293.238 57.9803 294.482C42.0326 319.08 36.097 323.982 33.9166 322.954C32.779 322.416 32.6684 320.261 32.5525 317.14C32.1417 306.061 36.7975 298.451 35.781 297.829C34.1378 296.822 24.4996 318.063 9.91075 319.08C6.55583 319.317 -1.38643 318.448 0.209399 315.702C1.91056 312.782 9.1892 300.027 18.5429 292.99ZM357.869 56.6783C381.032 53.8005 385.008 104.979 306.676 155.968C285.894 169.508 269.019 180.698 258.87 189.321C259.634 191.619 273.311 249.301 243.528 243.166C206.223 235.482 127.122 295.235 145.777 219.29C143.902 223.659 80.9486 298.414 54.9625 284.452C53.788 283.83 50.2856 282.069 48.9215 278.411C43.9129 265.087 76.5351 252.248 93.6099 214.741C97.6232 205.934 98.2341 201.338 104.201 191.629C107.277 186.622 116.257 172.006 129.018 163.9C150.027 150.555 163.589 166.541 196.512 158.45C213.576 154.255 210.574 150.223 246.403 133.262C282.038 116.385 284.94 120.412 304.201 109.433C344.907 86.2364 348.831 57.8115 357.874 56.6836L357.869 56.6783ZM149.685 118.519C150.517 118.546 151.354 118.867 151.908 119.484C158.212 126.505 155.083 150.302 141.574 163.484C139.394 165.613 135.749 163.695 136.17 160.674C141.311 124.27 124.531 137.009 113.26 145.558C110.548 147.614 106.94 144.63 108.42 141.573C113.128 131.865 124.473 117.987 149.68 118.514L149.685 118.519ZM213.939 65.9653C212.754 61.9912 218.353 64.2312 225.173 70.4981C248.184 91.6388 232.11 134.289 211.021 135.749C204.796 136.176 201.984 132.872 198.924 132.766C199.076 133.894 200.462 146.934 199.967 149.137C199.008 150.254 192.014 155.899 184.467 157.196C177.088 158.461 169.172 155.409 166.512 154.697C166.433 148.995 166.354 143.286 166.275 137.41C167.903 137.035 229.323 117.608 213.939 65.96V65.9653ZM144.06 59.5139C157.948 47.6707 178.42 45.1196 197.365 52.2878C202.663 53.8795 207.914 55.9509 207.788 58.0645C207.751 58.6337 207.34 59.0343 207.229 59.1344C201.394 64.9533 213.339 90.6216 193.957 112.79C180.227 128.486 160.587 131.153 159.923 131.19C159.123 120.275 155.736 116.295 152.803 114.656C147.273 111.594 141.722 116 135.017 111.936C130.619 109.269 121.271 89.6939 131.957 79.2211C135.876 75.384 133.895 68.179 144.065 59.5087L144.06 59.5139ZM370.783 18.5079C381.501 -7.57155 383.834 0.592762 384.656 3.64977C385.514 6.91233 384.687 15.6775 383.065 27.252C381.085 41.4091 373.279 53.1258 371.689 54.006C369.809 53.4579 367.428 52.9677 364.647 52.8149C360.25 52.5777 356.605 53.3156 354.119 54.0377C347.967 25.3967 348.678 17.7489 350.954 16.9319C352.149 16.505 354.124 18.0493 354.546 21.1485C358.964 53.9428 370.33 19.6147 370.783 18.5079Z" fill="#BC163A"/>
<path d="M114.999 407.341H95.9833C92.8811 402.432 94.3952 396.128 103.279 389.137C107.818 385.564 111.701 381.292 114.999 376.734V407.341ZM114.999 360.839C102.478 381.528 84.4742 406.767 72.6796 403.615C61.8302 400.716 66.0115 389.368 55.2939 388.562C28.9812 386.574 28.9918 362.493 92.6825 312.306C101.671 305.222 108.991 298.8 114.999 293.093V360.839ZM18.5419 293.331C29.9602 284.729 43.7914 281.946 45.54 282.441C46.488 284.149 47.8098 286.184 49.6689 288.255C52.6128 291.528 55.7095 293.578 57.9794 294.822C42.0318 319.421 36.0964 324.323 33.9159 323.295C32.7783 322.757 32.6676 320.602 32.5517 317.481C32.1409 306.403 36.7963 298.792 35.7802 298.17C34.137 297.163 24.499 318.404 9.91009 319.421C6.55516 319.658 -1.38691 318.788 0.208914 316.042C1.91047 313.121 9.18872 300.367 18.5419 293.331ZM114.999 253.86C94.6661 273.359 69.2368 292.462 54.9618 284.792C53.7872 284.17 50.2848 282.41 48.9208 278.752C43.9126 265.428 76.5346 252.588 93.6093 215.082C97.6225 206.275 98.2339 201.678 104.201 191.97C105.974 189.083 109.71 183.006 114.999 176.822V253.86ZM114.999 144.585C114.408 145.029 113.826 145.469 113.26 145.898C110.547 147.954 106.939 144.971 108.419 141.914C109.89 138.88 112.011 135.439 114.999 132.157V144.585Z" fill="#FFF5EB"/>
      </svg>
    </div>

    <div class="quiz-text">
      <p>
        Bienvenue sur notre quizz !<br />
        Prêt·e à tester tes connaissances, deviner des traditions mystérieuses et associer des œuvres d'art à leurs époques ?<br />
        Ici, tu vas t'amuser tout en découvrant plein de choses fascinantes sur notre magnifique patrimoine !
      </p>
      <a href="https://racines.ralaikoa.com/jeu.html" class="quiz-button" target="_blank" rel="noopener noreferrer">
  Commencer le jeu
</a>

    </div>
  </div>
</section>

<!--footer-->
<div id="footer-placeholder"></div>

<script>
  fetch("/structure/footer.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("footer-placeholder").innerHTML = data;
    });
</script>
<script>
    window.addEventListener('scroll', function() {
  const scrolled = window.scrollY;
  document.querySelector('.quiz-bg-left').style.transform = `translateY(${scrolled * 0.5}px)`;
  document.querySelector('.quiz-bg-right').style.transform = `translateY(${scrolled * 1.2}px)`;
});

    // Animation des éléments de fond parallaxe
    const bgLeft = document.getElementById("bgLeft");
    const bgRight = document.getElementById("bgRight");
    const contentBgLeft = document.getElementById("contentBgLeft");
    const contentBgRight = document.getElementById("contentBgRight");

    window.addEventListener("scroll", () => {
      const scrollY = window.scrollY;
      
      // Animation pour la première section
      bgLeft.style.transform = `translateY(${scrollY * 0.5}px) translateX(${scrollY * 0.6}px)`;
      bgRight.style.transform = `translateY(${scrollY * 0.3}px) translateX(${-scrollY * 0.4}px)`;
      
      // Animation pour la deuxième section (plus rapide pour un effet différent)
      contentBgLeft.style.transform = `translateY(${scrollY * 0.7}px) translateX(${scrollY * 0.9}px)`;
      contentBgRight.style.transform = `translateY(${scrollY * 0.4}px) translateX(${-scrollY * 0.1}px)`;
    });

    // Animation au chargement pour les cartes
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.flip-card');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 200);
      });
    });
  </script>
</body>
</html>