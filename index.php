<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet" />
  <title>Racines</title>
  <style>
    /* RESET */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      height: 500vh;
    }

    body {
      min-height: 100vh;
      background: #FFF5EB;
      font-family: 'Sora', sans-serif;
      overflow-x: hidden;
      transition: background 0.8s ease;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: none;
      z-index: 1000;
      opacity: 1;
      transition: opacity 0.5s ease 4.5s;
    }

    body.scroll-active::before {
      opacity: 0;
    }

    body.final-state {
      background: #BC163A;
    }

    /* BLOB */
    .blob {
      position: fixed;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 0;
      height: 0;
      background: #BC163A;
      border-radius: 43% 57% 71% 29% / 44% 38% 62% 56%;
      z-index: 1;
      filter: url('#goo');
      will-change: transform, width, height;
      transition: all 0.8s cubic-bezier(0.33, 1, 0.68, 1);
      animation: morph 8s ease-in-out infinite both;
    }

    .blob.initial-grow {
      width: 550px;
      height: 400px;
      transition: all 1.5s ease-out;
    }

    .blob.final-size {
      width: 300vw !important;
      height: 300vh !important;
      animation: none;
      transition: all 1.5s ease-out;
    }

    @keyframes morph {
      0%, 100% { border-radius: 43% 57% 71% 29% / 44% 38% 62% 56%; }
      33% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
      66% { border-radius: 40% 60% 70% 30% / 40% 70% 30% 60%; }
    }

    /* SCROLL INDICATOR */
    .scroll-indicator {
      position: fixed;
      bottom: 120px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 2;
      gap: 15px;
      transition: opacity 0.8s ease;
    }

    .scroll-img {
      width: 40px;
      opacity: 0;
      transform: translateY(20px);
      animation: 
        fadeInUp 0.8s ease-out 2.5s forwards,
        float 2s ease-in-out 3.5s infinite;
    }

    .scroll-text {
      color: #BC163A;
      font-weight: 700;
      font-size: 1.3rem;
      opacity: 0;
      animation: fadeIn 0.8s ease-out 3.5s forwards;
    }

    /* NEW CONTENT */
    .new-content {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10;
      opacity: 0;
      pointer-events: none;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 2rem;
      transition: opacity 1s ease 0.5s;
    }

    .final-state .new-content {
      opacity: 1;
      pointer-events: auto;
    }

    .racines-logo {
      width: 80%;
      max-width: 400px;
      opacity: 0;
      transform: translateY(30px);
      transition: all 1s ease;
    }

    /* Animation des lettres */
    .letter {
      opacity: 0;
      transform: translateX(100vw);
    }

    .final-state .racines-logo {
      opacity: 1;
    }

    .final-state .letter {
      animation: 
        letterSlideIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards,
        letterFloat 3s ease-in-out 0.8s infinite;
    }

    .final-state .letter-r { animation-delay: 0.1s; }
    .final-state .letter-a { animation-delay: 0.2s; }
    .final-state .letter-c { animation-delay: 0.3s; }
    .final-state .letter-i { animation-delay: 0.4s; }
    .final-state .letter-n { animation-delay: 0.5s; }
    .final-state .letter-e { animation-delay: 0.6s; }
    .final-state .letter-s { animation-delay: 0.7s; }

    .dot-i {
      transform-origin: center;
      opacity: 0;
      transform: scale(0);
    }

    .final-state .dot-i {
      animation: 
        dotPopIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 1.5s forwards,
        dotFloat 3s ease-in-out 2s infinite;
    }

    /* ANIMATIONS */
    @keyframes fadeInUp {
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-12px); }
    }

    @keyframes letterSlideIn {
      to { opacity: 1; transform: translateX(0); }
    }

    @keyframes letterFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }

    @keyframes dotPopIn {
      0% { opacity: 0; transform: scale(0); }
      80% { opacity: 1; transform: scale(1.2); }
      100% { opacity: 1; transform: scale(1); }
    }

    @keyframes dotFloat {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-8px) scale(1.05); }
    }
  </style>
</head>
<body>
  <svg style="visibility: hidden; position: absolute;">
    <defs>
      <filter id="goo">
        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -10" result="goo" />
        <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
      </filter>
    </defs>
  </svg>

  <div class="blob"></div>
  
  <div class="scroll-indicator">
    <img src="img/scroll.png" alt="Scroll" class="scroll-img">
    <p class="scroll-text">Plonge dans tes racines</p>
  </div>

  <div class="new-content">
    <svg class="racines-logo" viewBox="0 0 367 89" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path class="letter letter-r" d="M77.6106 28.6326C90.1174 28.6327 95.8219 35.4354 95.8219 45.3094V71.8586C95.8219 75.3693 97.0291 77.7834 100.759 77.7835C102.844 77.7835 104.599 77.1252 106.354 75.4796L107.671 76.5764C105.915 80.7455 101.747 88.7546 91.3244 88.7547C83.7543 88.7547 79.5851 84.5851 78.488 79.319C75.087 84.256 68.8334 88.8639 61.2633 88.8639C53.6933 88.8639 47.879 84.366 47.879 76.3571C47.8791 62.9725 64.4447 56.8288 77.9391 53.8666V50.5751C77.9391 43.5536 74.8675 40.4813 66.6393 40.4813C61.7023 40.4813 56.6554 41.5786 51.499 43.7728L50.1829 42.2372C55.7781 35.2157 65.2132 28.6326 77.6106 28.6326Z" fill="#FFF5EB"/>
      <path class="letter letter-a" d="M139.531 28.6326C146.881 28.6326 152.038 30.1683 155.329 31.3751L157.084 47.7224L155.659 48.1611C148.747 39.0552 142.932 32.9118 137.446 32.9117C131.083 32.9117 125.707 39.604 125.707 52.6596C125.707 68.8965 133.826 75.5886 143.809 75.5888C149.624 75.5888 154.781 73.6143 157.633 70.5424L159.059 71.4199C155.878 81.7327 147.101 88.8639 134.374 88.8639C118.466 88.8639 107.496 77.564 107.496 60.12C107.496 41.6886 120.88 28.6326 139.531 28.6326Z" fill="#FFF5EB"/>
      <path class="letter letter-c" d="M292.502 28.6326C308.849 28.6326 317.297 42.2373 316.748 55.5124H282.409C283.616 70.1036 291.295 75.2602 301.169 75.2603C307.532 75.2603 311.701 73.2852 315.322 69.8843L316.639 70.7618C313.348 81.5134 304.68 88.8638 291.515 88.8639C274.51 88.8639 264.087 76.4666 264.087 59.7905C264.088 41.4691 276.375 28.6328 292.502 28.6326Z" fill="#FFF5EB"/>
      <path class="letter letter-i" d="M24.1362 42.5668C29.5121 34.0093 35.6565 26.4394 47.0664 28.6336L44.1035 49.5875L43.226 49.6977C39.057 46.7355 33.2422 44.6513 30.1703 44.6513C28.3053 44.6514 26.3307 45.9676 24.6851 48.0519V65.3868C24.6852 75.5896 25.6728 80.8556 27.7573 86.8896V87.109H3.51085V86.8896C5.59534 80.8556 6.80234 75.9185 6.80234 65.2766V54.8543C6.80234 45.5288 4.937 40.2623 0 34.9962V34.7768L24.1362 28.0847V42.5668Z" fill="#FFF5EB"/>
      <path class="letter letter-n" d="M186.313 65.3868C186.313 75.5896 187.301 80.8557 189.385 86.8896V87.109H165.139V86.8896C167.004 80.8555 168.43 75.6992 168.43 65.2766V55.0736C168.43 45.858 166.566 40.4816 161.629 35.2155V34.7768L186.313 28.0847V65.3868Z" fill="#FFF5EB"/>
      <path class="letter letter-e" d="M217.965 39.714L218.514 39.8232C221.586 34.7767 228.388 28.6338 238.591 28.6336C249.123 28.6336 255.815 34.8869 255.815 44.8706V65.2766C255.815 75.6992 256.804 80.8555 258.998 86.8896V87.109H234.751V86.8896C236.946 80.8556 237.933 75.5894 237.933 65.496V47.3938C237.933 42.6763 235.519 39.1652 229.705 39.1651C224.988 39.1651 220.928 41.5789 218.514 44.9798V65.3868C218.514 75.5896 219.502 80.8556 221.586 86.8896V87.109H197.23V86.8896C199.424 80.8555 200.632 75.9186 200.632 65.2766V54.8543C200.632 45.5288 198.766 40.2623 193.829 34.9962V34.7768L217.965 28.0847V39.714Z" fill="#FFF5EB"/>
      <path class="letter letter-s" d="M347.032 28.6326C352.298 28.6326 357.784 29.5103 361.514 30.4977L363.05 44.9798L361.514 45.4185C354.273 36.7517 349.665 32.9119 343.522 32.9117C338.914 32.9117 335.951 35.1056 335.951 39.0549C335.951 45.089 342.863 47.0644 350.653 50.3558C358.881 53.8665 367 58.4742 367 70.2128C367 83.4878 356.029 88.8639 342.754 88.8639C335.732 88.8639 328.71 87.3284 323.554 85.2439L321.908 68.7875L323.444 68.3488C330.685 78.6613 337.268 84.5858 345.606 84.5858C351.64 84.5857 353.725 81.6235 353.725 78.1130C353.725 72.2983 347.361 69.9943 340.34 66.9224C332.002 63.1922 322.786 59.1325 322.786 46.8449C322.786 33.8991 333.757 28.6327 347.032 28.6326Z" fill="#FFF5EB"/>
      <path class="dot-i" d="M173.192 1.39333C174.597 -0.185892 177.065 -0.185702 178.47 1.39333L179.485 2.53546C180.111 3.23964 180.993 3.66408 181.934 3.71467L183.46 3.79706C185.571 3.91042 187.109 5.84008 186.75 7.92275L186.49 9.42843C186.33 10.3571 186.549 11.3111 187.096 12.0783L187.983 13.3224C189.21 15.043 188.661 17.4499 186.808 18.4677L185.47 19.203C184.644 19.6568 184.033 20.4225 183.774 21.3287L183.355 22.7983C182.775 24.8303 180.552 25.9009 178.601 25.0877L177.19 24.4997C176.321 24.1369 175.342 24.137 174.473 24.4997L173.062 25.0877C171.111 25.9011 168.888 24.8303 168.308 22.7983L167.888 21.3287C167.629 20.4225 167.019 19.6568 166.193 19.203L164.854 18.4677C163.001 17.4499 162.452 15.043 163.679 13.3224L164.567 12.0783C165.114 11.3111 165.332 10.357 165.172 9.42843L164.912 7.92275C164.553 5.84005 166.092 3.91039 168.203 3.79706L169.728 3.71467C170.669 3.66414 171.551 3.23966 172.177 2.53546L173.192 1.39333Z" fill="#FFF5EB"/>
    </svg>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const blob = document.querySelector('.blob');
      const indicator = document.querySelector('.scroll-indicator');
      const body = document.body;
      const newContent = document.querySelector('.new-content');
      let allowScroll = false;
      let animationCompleted = false;

      // Animation initiale: le blob grandit de 0px à sa taille normale
      setTimeout(() => {
        blob.classList.add('initial-grow');
      }, 100);

      // Désactive le scroll pendant 5 secondes
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        document.body.style.overflow = '';
        allowScroll = true;
        document.body.classList.add('scroll-active');
      }, 5000);

      window.addEventListener('scroll', () => {
        if (!allowScroll || animationCompleted) return;

        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = window.scrollY;
        let progress = Math.min(scrolled / (scrollable * 0.8), 1);

        if (progress < 0.3) {
          progress = progress * 0.3;
        } else {
          progress = 0.09 + (progress - 0.3) * 0.7;
        }

        const scale = 1 + progress * 8;
        
        blob.style.width = `${450 * scale}px`;
        blob.style.height = `${300 * scale}px`;
        
        indicator.style.opacity = `${1 - progress * 2}`;

        if (scrolled > scrollable * 0.8) {
          animationCompleted = true;
          blob.classList.add('final-size');
          body.classList.add('final-state');
          indicator.style.display = 'none';
          
          // Après que le blob ait atteint sa taille finale
          setTimeout(() => {
            // Faire disparaître le logo en fondu après 3 secondes
            setTimeout(() => {
              newContent.style.transition = 'opacity 1s ease';
              newContent.style.opacity = '0';
              
              // Redirection après 2 secondes supplémentaires
              setTimeout(() => {
                window.location.href = 'https://racines.ralaikoa.com/index.html';
              }, 2000);
            }, 3000);
          }, 1500);
        }
      });

      // Bloque les autres méthodes de scroll
      window.addEventListener('wheel', (e) => {
        if (!allowScroll) e.preventDefault();
      }, { passive: false });
    });
  </script>
</body>
</html>