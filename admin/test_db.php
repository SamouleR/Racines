<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Racines</title>

    <!-- Fonts & Styles -->
    <link rel="stylesheet" href="/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sora', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Section Événements */
        .events-section {
            padding: 60px 20px 40px;
            position: relative;
            margin-bottom: 40px;
            overflow: hidden;
        }

        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .events-title {
            font-size: 2.5rem;
            text-align: center;
            margin: 0;
            color: #A72642;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2;
        }

        
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 60px;
            padding-bottom: 100px;
        }

        /* Style du carrousel */
        .carousel-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .carousel-container {
            flex-grow: 1;
            max-width: 800px;
            overflow: hidden;
            position: relative;
            margin-bottom: 40px;
        }

        .carousel {
            overflow: hidden;
            border-radius: 12px;
            background-color: white;
            padding: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            min-width: 100%;
            position: relative;
        }

        .carousel-slide img {
            width: 100%;
            height: auto;
            max-height: 60vh;
            object-fit: contain;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .carousel-slide img:hover {
            transform: scale(1.02);
        }

        /* Boutons navigation */
        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #BC163A;
            color: white;
            border: none;
            font-size: 1.5rem;
            width: 60px;
            height: 60px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            z-index: 20;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            filter: drop-shadow(2px 2px 3px rgba(0,0,0,0.2));
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            margin: 0 20px;
        }

        .carousel-button.prev {
            left: 0;
        }

        .carousel-button.next {
            right: 0;
            border-radius: 40% 60% 70% 30% / 30% 60% 40% 70%;
        }

        .carousel-button:hover {
            transform: translateY(-50%) scale(1.1);
            background-color: #D91E4A;
        }

        .event-article {
            display: none;
            max-width: 1300px;
            margin: 0 auto;
            padding: 40px;
            background-color: #BC163A;
            color: white;
            box-shadow: 0 10px 30px rgba(167, 38, 66, 0.3);
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
            border-radius: 51% 49% 48% 52% / 62% 44% 56% 38%;
            animation: blob-morph 12s ease-in-out infinite alternate;
        }

        @keyframes blob-morph {
            0% { border-radius: 51% 49% 48% 52% / 62% 44% 56% 38%; }
            25% { border-radius: 53% 47% 46% 54% / 60% 46% 54% 40%; }
            50% { border-radius: 49% 51% 52% 48% / 58% 42% 58% 42%; }
            75% { border-radius: 47% 53% 50% 50% / 55% 48% 52% 45%; }
            100% { border-radius: 52% 48% 49% 51% / 65% 40% 60% 35%; }
        }

        .event-article::before {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 150px;
            height: 150px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            z-index: 0;
        }

        .event-article::after {
            content: "";
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 200px;
            height: 200px;
            background-color: rgba(255,255,255,0.05);
            border-radius: 50%;
            z-index: 0;
        }

        .event-article.active {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .event-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
            position: relative;
            z-index: 1;
        }

        .event-text {
            padding: 15px;
        }

        .event-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transform: rotate(-2deg);
            transition: transform 0.3s ease;
        }

        .event-image:hover {
            transform: rotate(0deg) scale(1.02);
        }

        .event-title {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 15px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }

        .event-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(167, 38, 66, 0.5);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .event-meta span::before {
            content: "";
            display: inline-block;
            width: 16px;
            height: 16px;
            background-size: contain;
            background-repeat: no-repeat;
            filter: brightness(0) invert(1);
        }

        .event-date::before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/></svg>');
        }

        .event-location::before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>');
        }

        .event-participants::before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>');
        }

        .event-description {
            line-height: 1.7;
            color: rgba(255,255,255,0.9);
            font-size: 1.05rem;
        }

        .detail-button {
            background-color: white;
            color: #BC163A;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            align-self: flex-start;
        }

        .detail-button:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Version desktop */
        @media (min-width: 768px) {
            .event-content {
                flex-direction: row;
                align-items: flex-start;
            }
            
            .event-image {
                max-width: 450px;
                margin-right: 30px;
            }

            .event-article {
                padding: 70px;
                border-radius: 60% 40% 40% 60% / 60% 40% 60% 40%;
                animation: blob-morph-desktop 15s ease-in-out infinite alternate;
            }

            @keyframes blob-morph-desktop {
                0% { border-radius: 60% 40% 40% 60% / 60% 40% 60% 40%; }
                33% { border-radius: 55% 45% 50% 50% / 55% 45% 55% 45%; }
                66% { border-radius: 65% 35% 45% 55% / 65% 35% 65% 35%; }
                100% { border-radius: 50% 50% 45% 55% / 70% 30% 70% 30%; }
            }

            .forme-decoration {
                width: 400px;
                height: 300px;
                right: 5%;
            }
        }

        /* Version tablette */
        @media (max-width: 768px) {
            .events-title {
                font-size: 2rem;
            }
            
            .carousel-slide img {
                max-height: 50vh;
            }
            
            .carousel-button {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                margin: 0 10px;
            }

            .content-wrapper {
                gap: 50px;
                padding-bottom: 80px;
            }

            .event-article {
                margin-bottom: 50px;
                padding: 25px;
                border-radius: 60% 40% 50% 50% / 60% 50% 50% 40%;
            }

            .forme-decoration {
                width: 250px;
                height: 180px;
                right: -300px;
                opacity: 0.8;
            }
        }

        /* Version mobile */
        @media (max-width: 480px) {
            .event-meta {
                flex-direction: column;
                gap: 10px;
            }

            .event-article {
                padding: 20px;
                border-radius: 55% 45% 50% 50% / 50% 50% 50% 50%;
                margin-bottom: 40px;
            }

            .content-wrapper {
                gap: 40px;
            }

            .forme-decoration {
                width: 180px;
                height: 120px;
                right: -50px;
                opacity: 0.6;
            }
        }
    </style>
</head>
<body>
    <!-- Placeholder pour le header -->
    <div id="header-placeholder"></div>

    <!-- Votre contenu existant -->

<script>
async function loadHeader() {
  try {
    const response = await fetch("/structure/header(1).php");
    if (!response.ok) throw new Error('Erreur HTTP');
    
    const html = await response.text();
    const placeholder = document.getElementById("header-placeholder");
    
    // Injection robuste qui préserve les événements
    placeholder.innerHTML = html;
    
    // Extraction et exécution des scripts
    const scripts = placeholder.querySelectorAll('script');
    scripts.forEach(script => {
      const newScript = document.createElement('script');
      if (script.src) {
        newScript.src = script.src;
      } else {
        newScript.textContent = script.textContent;
      }
      document.body.appendChild(newScript).remove();
    });
    
    window.headerLoadedExternally = true;
    
    // Initialisation garantie
    if (typeof window.initNavbar === 'function') {
      setTimeout(() => window.initNavbar(), 50);
    } else {
      console.error('Erreur: initNavbar non trouvée');
    }
    
  } catch (error) {
    console.error('Erreur de chargement:', error);
    // Fallback minimaliste
    document.getElementById("header-placeholder").innerHTML = `
      <nav style="background:#BC163A;padding:1rem;color:white;">
        <a href="/" style="color:#FFD9A8;">Accueil</a> |
        <a href="/contact.php" style="color:#FFD9A8;">Contact</a>
      </nav>`;
  }
}

// Lancement au chargement complet
if (document.readyState === 'complete') {
  loadHeader();
} else {
  window.addEventListener('load', loadHeader);
}
</script>
</body>
</html>

