<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Racines</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sora', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff5eb;
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
            background-color: #fff5eb;
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

        /* Boutons navigation carrousel */
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

        /* Boutons navigation événements */
        .events-navigation {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .event-nav-button {
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
        }

        .event-nav-button.next-event {
            border-radius: 40% 60% 70% 30% / 30% 60% 40% 70%;
        }

        .event-nav-button.prev-event {
            transform: rotate(180deg);
        }

        .event-nav-button:hover {
            transform: scale(1.1);
            background-color: #D91E4A;
        }

        .event-nav-button.prev-event:hover {
            transform: rotate(180deg) scale(1.1);
        }

        .event-article-container {
            position: relative;
            margin-top: 75px;
            width: 1300px;
            max-width: 1800px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 60px;
        }

        .event-article { 
            display: none;
            max-height: 1000px;
            padding: 40px;
            background-color: #BC163A;
            color: white;
            box-shadow: 0 10px 30px rgba(167, 38, 66, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
            border-radius: 25px;
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

        /* Boutons de navigation pour l'article */
        .article-nav-button {
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
        }

        .article-nav-button.prev-article {
            left: -80px;
            transform: translateY(-50%) rotate(180deg);
        }

        .article-nav-button.next-article {
            right: -80px;
            border-radius: 40% 60% 70% 30% / 30% 60% 40% 70%;
        }

        .article-nav-button:hover {
            transform: translateY(-50%) scale(1.1);
            background-color: #D91E4A;
        }

        .article-nav-button.prev-article:hover {
            transform: translateY(-50%) rotate(180deg) scale(1.1);
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

            .event-nav-button {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .content-wrapper {
                gap: 50px;
                padding-bottom: 80px;
            }

            .event-article {
                padding: 25px;
            }

            .article-nav-button {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .article-nav-button.prev-article {
                left: -70px;
            }

            .article-nav-button.next-article {
                right: -70px;
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
            }

            .content-wrapper {
                gap: 40px;
            }

            .event-nav-button {
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }

            .article-nav-button {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .article-nav-button.prev-article {
                left: -50px;
            }

            .article-nav-button.next-article {
                right: -50px;
            }
        }

        /* IMAGES DE FOND */
        .background-images img {
            position: absolute;
            pointer-events: none;
            user-select: none;
            opacity: 0.15;
            mix-blend-mode: multiply;
        }
        .img1 {
            top: 400px;
            left: 20px;
            width: 650px;
        }
        .img2 {
            top:1150px;
            right: 10px;
            width: 500px;
        }
        .img3 {
            top: 10px;
            right: 10%;
            width: 300px;
            transform: translate(-50%, -25%);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }

        .lightbox-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
            animation: zoom 0.3s ease;
        }

        @keyframes zoom {
            from {transform: scale(0.5);}
            to {transform: scale(1);}
        }

        .lightbox .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .lightbox .close:hover {
            color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="background-images">
        <img src="/img/patterns/rouge/motifs2.png" alt="Etoiles" class="img1" />
        <img src="/img/patterns/rouge/vase2.png" alt="Vase" class="img2" />
        <img src="/img/patterns/rouge/vague.png" alt="Cercle" class="img3" />
    </div>
    
    <!-- Header -->
    <div id="header-placeholder"></div>

    <!-- Main Content -->
    <main>
        <section class="events-section">
            <div class="events-container">
                <h1 class="events-title">Nos événements récents</h1>
            </div>
        </section>

        <div class="content-wrapper">
            <!-- Carrousel -->
            <div class="carousel-wrapper">
                <div class="carousel-container">
                    <button class="carousel-button prev" aria-label="Image précédente">‹</button>
                    <div class="carousel" aria-label="Galerie d'événements">
                        <div class="carousel-track" id="carousel-track"></div>
                    </div>
                    <button class="carousel-button next" aria-label="Image suivante">›</button>
                </div>
            </div>

            <!-- Articles des événements -->
            <div class="event-article-container" id="events-container">
                <!-- Boutons de navigation pour l'article -->
                <button class="article-nav-button prev-article" aria-label="Événement précédent">›</button>
                <button class="article-nav-button next-article" aria-label="Événement suivant">›</button>
            </div>
            
            <!-- Boutons de navigation pour les événements -->
            <div class="events-navigation">
                <button class="event-nav-button prev-event" aria-label="Événement précédent">›</button>
                <button class="event-nav-button next-event" aria-label="Événement suivant">›</button>
            </div>

            <!-- Lightbox -->
            <div id="lightbox" class="lightbox">
                <span class="close">&times;</span>
                <img class="lightbox-content" id="lightbox-img" alt="Image agrandie">
            </div>
        </div>
    </main>

    <!-- Footer -->
    <div id="bouton-placeholder"></div>
    <div id="footer-placeholder"></div>

    <script>
        // Données des événements (pourrait venir d'une API)
        const eventsData = [
            {
                id: 1,
                title: "Evènement \"La marinière en style? \"",
                description: "Une soirée haute en créativité consacrée à la réinvention de la marinière, ce vêtement emblématique de la tradition française. Sur scène, de jeunes créateurs, danseurs et stylistes ont exploré comment la marinière, autrefois symbole de la marine, est devenue une icône de mode urbaine et engagée. Le public a assisté à un défilé-performance mêlant streetwear, danse contemporaine et influences bretonnes, suivi d'un échange passionnant sur l'appropriation culturelle par les nouvelles générations.",
                date: "15 mars 2023",
                location: "Théâtre Municipal, Paris",
                participants: 215,
                image: "img/affiche/affiche1.png"
            },
            {
                id: 2,
                title: "Bourguignon de grand-mère",
                description: "Dans une ambiance chaleureuse et conviviale, cet atelier immersif a replongé les participants dans l'univers du savoir-faire culinaire d'antan. Autour du plat emblématique qu'est le bœuf bourguignon, jeunes et moins jeunes ont découvert les gestes, les histoires et les secrets transmis de génération en génération. Entre préparation collective, anecdotes familiales et dégustation finale, cet événement a célébré la richesse de la tradition culinaire comme un art vivant et partagé.",
                date: "22-23 avril 2023",
                location: "Atelier des Arts, Lyon",
                participants: 35,
                image: "img/affiche/affiche2.png"
            },
            {
                id: 3,
                title: "Evènement \"Folklore et Hip-Hop\"",
                description: "Quand les rythmes d'hier rencontrent les battements d'aujourd'hui. Cette soirée électrisante a fusionné les codes du hip-hop avec les sonorités du folklore local. Sur scène, trois collectifs ont mêlé breakdance, chant traditionnel, beatmaking et instruments anciens, créant un dialogue puissant entre générations. Le public a également pu explorer une exposition interactive retraçant l'évolution des instruments traditionnels dans la musique contemporaine.",
                date: "5 juin 2023",
                location: "Salle des Fêtes, Marseille",
                participants: 180,
                image: "img/affiche/affiche3.png"
            },
            {
                id: 4,
                title: "Concert \"Blairenoi\"",
                description: "Dans une ambiance à la fois rétro et avant-gardiste, Blairenoi a enflammé la scène avec un concert mêlant électro, chant en patois local et esthétique folklorique revisitée. Vêtu d'un béret traditionnel, l'artiste a fait vibrer le public en jouant sur les contrastes entre héritage et modernité. Le spectacle a offert une performance visuelle et sonore unique, où la culture populaire régionale s'est exprimée dans un style résolument actuel. Une soirée inattendue et captivante qui a bousculé les codes du concert classique.",
                date: "12 septembre 2023",
                location: "Université de Bordeaux",
                participants: 90,
                image: "img/affiche/Affiche4.png"
            },
            {
                id: 5,
                title: "Atelier \"Là où tu marches, c'est traditonnel \"",
                description: "Là où tu marches, c'est la tradition qui t'accueille. En parcourant les allées de ce festival, les visiteurs ont redécouvert des savoir-faire oubliés, transmis par les mains expertes d'artisans et de cuisiniers passionnés. Chaque atelier était une plongée dans l'essence d'une culture vivante : pâtes façonnées à la main, mijotés au feu de bois, épices contées comme des légendes. En t'immergeant dans les arômes et les gestes d'autrefois, tu tisses un lien intime avec les racines de ton territoire. Le concours final a célébré la créativité contemporaine au service de la mémoire culinaire.",
                date: "21 octobre 2023",
                location: "Place du Marché, Toulouse",
                participants: 350,
                image: "img/affiche/Affiche5.png"
            }
        ];

        // Variables globales
        let currentSlideIndex = 0;
        let currentEventIndex = 0;

        // Initialisation de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Chargement du header et footer
            loadHeaderFooter();
            
            // Initialisation du carrousel et des événements
            initCarousel();
            renderEvents();
            
            // Afficher le premier événement par défaut
            if (eventsData.length > 0) {
                showEventDetails(eventsData[0].id);
            }
            
            // Initialisation de la lightbox
            initLightbox();
            
            // Initialisation de la navigation des événements
            initEventsNavigation();
            
            // Initialisation de la navigation des articles
            initArticleNavigation();
        });

        // Fonction pour charger le header et footer
        function loadHeaderFooter() {
            // Header
            fetch("structure/header.php")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    document.getElementById("header-placeholder").innerHTML = data;
                })
                .catch(error => {
                    console.error('Error loading header:', error);
                    document.getElementById("header-placeholder").innerHTML = '<header>Navigation</header>';
                });

            // Footer
            fetch("structure/footer.php")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    document.getElementById("footer-placeholder").innerHTML = data;
                })
                .catch(error => {
                    console.error('Error loading footer:', error);
                    document.getElementById("footer-placeholder").innerHTML = '<footer>© Racines</footer>';
                });
        }

        // Fonction pour initialiser le carrousel
        function initCarousel() {
            const carouselTrack = document.getElementById('carousel-track');
            carouselTrack.innerHTML = '';
            
            // Création des slides du carrousel
            eventsData.forEach(event => {
                const slide = document.createElement('div');
                slide.className = 'carousel-slide';
                slide.dataset.event = event.id;
                slide.innerHTML = `
                    <img src="${event.image}" alt="${event.title}" width="600" height="800" loading="lazy">
                `;
                carouselTrack.appendChild(slide);
                
                // Ajout de l'événement click sur l'image
                slide.querySelector('img').addEventListener('click', function() {
                    showEventDetails(event.id);
                });
            });
            
            // Initialisation des variables pour la navigation
            const track = document.querySelector('.carousel-track');
            const slides = Array.from(track.querySelectorAll('.carousel-slide'));
            const nextButton = document.querySelector('.carousel-button.next');
            const prevButton = document.querySelector('.carousel-button.prev');
            
            // Fonction pour mettre à jour la position du slide
            function updateSlide(position) {
                currentSlideIndex = position;
                const slideWidth = slides[0].getBoundingClientRect().width;
                track.style.transform = `translateX(-${slideWidth * position}px)`;
                
                // Mise à jour de l'accessibilité
                slides.forEach((slide, index) => {
                    slide.setAttribute('aria-hidden', index !== position);
                });
                
                // Synchroniser avec l'affichage des événements
                const eventId = slides[position].getAttribute('data-event');
                showEventDetails(parseInt(eventId));
            }
            
            // Événements pour les boutons de navigation
            nextButton.addEventListener('click', function() {
                const newIndex = (currentSlideIndex + 1) % slides.length;
                updateSlide(newIndex);
            });
            
            prevButton.addEventListener('click', function() {
                const newIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
                updateSlide(newIndex);
            });
            
            // Navigation au clavier
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight') {
                    const newIndex = (currentSlideIndex + 1) % slides.length;
                    updateSlide(newIndex);
                } else if (e.key === 'ArrowLeft') {
                    const newIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
                    updateSlide(newIndex);
                }
            });
            
            // Adaptation responsive
            window.addEventListener('resize', function() {
                updateSlide(currentSlideIndex);
            });
            
            // Initialisation
            updateSlide(currentSlideIndex);
        }

        // Fonction pour afficher les événements
        function renderEvents() {
            const eventsContainer = document.getElementById('events-container');
            eventsContainer.innerHTML = '';
            
            // Création des articles pour chaque événement
            eventsData.forEach(event => {
                const article = document.createElement('article');
                article.className = 'event-article';
                article.id = `event-${event.id}`;
                
                article.innerHTML = `
                    <div class="event-content">
                        <img src="${event.image}" alt="${event.title}" class="event-image">
                        <div class="event-text">
                            <h2 class="event-title">${event.title}</h2>
                            <div class="event-meta">
                                <span class="event-date">${event.date}</span>
                                <span class="event-location">${event.location}</span>
                                <span class="event-participants">${event.participants} participants</span>
                            </div>
                            <p class="event-description">${event.description}</p>
                            <button class="detail-button" data-event="${event.id}">Détail de l'événement</button>
                        </div>
                    </div>
                `;
                
                eventsContainer.appendChild(article);
                
                // Ajout de l'événement click sur le bouton
                article.querySelector('.detail-button').addEventListener('click', function() {
                    // Ici vous pourriez rediriger vers une page de détails complète
                    alert(`Redirection vers les détails de l'événement ${event.id}`);
                });
            });
        }

        // Fonction pour initialiser la navigation des événements
        function initEventsNavigation() {
            const prevEventButton = document.querySelector('.prev-event');
            const nextEventButton = document.querySelector('.next-event');
            
            prevEventButton.addEventListener('click', function() {
                navigateEvents(-1);
            });
            
            nextEventButton.addEventListener('click', function() {
                navigateEvents(1);
            });
        }
        
        // Fonction pour initialiser la navigation des articles
        function initArticleNavigation() {
            const prevArticleButton = document.querySelector('.prev-article');
            const nextArticleButton = document.querySelector('.next-article');
            
            prevArticleButton.addEventListener('click', function() {
                navigateEvents(-1);
            });
            
            nextArticleButton.addEventListener('click', function() {
                navigateEvents(1);
            });
        }

        // Fonction pour naviguer entre les événements
        function navigateEvents(direction) {
            const newIndex = (currentEventIndex + direction + eventsData.length) % eventsData.length;
            showEventDetails(eventsData[newIndex].id);
        }

        // Fonction pour afficher les détails d'un événement spécifique
        function showEventDetails(eventId) {
            // Trouver l'index de l'événement
            currentEventIndex = eventsData.findIndex(event => event.id === eventId);
            if (currentEventIndex === -1) return;
            
            // Masquer tous les articles d'événements
            document.querySelectorAll('.event-article').forEach(article => {
                article.classList.remove('active');
            });
            
            // Afficher l'article correspondant
            const article = document.getElementById(`event-${eventId}`);
            if (article) {
                article.classList.add('active');
                
                // Faire défiler jusqu'à l'article
                article.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            
            // Mettre à jour le carrousel pour afficher le bon slide
            const track = document.querySelector('.carousel-track');
            if (track) {
                const slides = Array.from(track.querySelectorAll('.carousel-slide'));
                const slideIndex = slides.findIndex(slide => parseInt(slide.dataset.event) === eventId);
                if (slideIndex >= 0) {
                    currentSlideIndex = slideIndex;
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    track.style.transform = `translateX(-${slideWidth * slideIndex}px)`;
                }
            }
        }

        // Fonction pour initialiser la lightbox
        function initLightbox() {
            // Lightbox : ouverture sur clic d'image
            document.addEventListener('click', function(e) {
                const target = e.target;
                if (target.tagName === 'IMG' && target.closest('.carousel-slide, .event-article')) {
                    const src = target.getAttribute('src');
                    const lightbox = document.getElementById('lightbox');
                    const lightboxImg = document.getElementById('lightbox-img');
                    lightboxImg.src = src;
                    lightbox.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Empêcher le défilement
                }
            });

            // Fermer la lightbox
            document.querySelector('.lightbox .close').addEventListener('click', function() {
                document.getElementById('lightbox').style.display = 'none';
                document.body.style.overflow = ''; // Rétablir le défilement
            });

            // Fermer si on clique en dehors de l'image
            document.getElementById('lightbox').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    document.body.style.overflow = ''; // Rétablir le défilement
                }
            });
        }

        // Chargement des données depuis une API (exemple)
        function loadEventsFromAPI() {
            fetch('get_events.php')
                .then(res => res.json())
                .then(data => {
                    eventsData = data;
                    initCarousel();
                    renderEvents();
                    showEventDetails(eventsData[0].id);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                });
        }
    </script>
</body>
</html>