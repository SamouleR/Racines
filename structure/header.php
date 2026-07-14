<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Sora', sans-serif;
    }

    .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2.5rem;
    background-color: #bc163a;
    color: #fff5eb;
    position: relative;
    box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.6);
}
    .logo {
        width: 150px;
        height: auto;
    }

    .logo svg {
        height: 40px;
        width: auto;
        fill: #fff5eb;
    }

    .hamburger {
        display: none;
        flex-direction: column;
        justify-content: space-around;
        width: 30px;
        height: 21px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        z-index: 20;
        position: relative;
    }

    .hamburger:focus {
        outline: none;
    }

    .bar {
        width: 100%;
        height: 3px;
        background-color: #fff5eb;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .nav-menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .nav-list {
        display: flex;
        gap: 2rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-link, header .fas {
        color: #fff5eb;
        text-decoration: none;
        font-size: 1.1rem;
        transition: color 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
    }

    .nav-link:hover {
        color: white;
    }

    /* Styles pour les menus déroulants desktop */
    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 8px;
        top: 100%;
        left: 0;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        color: #a72642;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    .dropdown-content a:hover {
        background-color: #a72642;
        color: white;
    }

    /* Styles pour mobile */
    @media (max-width: 768px) {
        .hamburger {
            display: flex;
        }

        .nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100vh;
            background-color: #bc163a;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            transition: right 0.3s ease-in-out;
            z-index: 10;
            padding: 80px 2rem 2rem;
        }

        .nav-list {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            width: 100%;
            padding: 0;
        }

        .dropdown {
            width: 100%;
        }

        .dropdown-content {
            position: relative;
            display: none;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: none;
            border-radius: 4px;
            margin-top: 0.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown.active .dropdown-content {
            display: block;
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        .dropdown .nav-link {
            width: 100%;
            justify-content: space-between;
        }

        .dropdown .nav-link::after {
            content: "+";
            font-size: 1.2rem;
        }

        .dropdown.active .nav-link::after {
            content: "-";
        }

        .dropdown-content a {
            color: #fff5eb;
            padding-left: 1.5rem;
        }

        .dropdown-content a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .nav-menu.active {
            right: 0;
        }

        .hamburger.active {
            position: fixed;
            right: 2rem;
            top: 1.5rem;
        }

        .hamburger.active .bar:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger.active .bar:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active .bar:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }
    }
</style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo"><a href="/index.html">
                <svg width="200" viewBox="0 0 514 124" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M505.636 42.3642L507.784 62.6197L505.636 63.2335C495.508 51.1109 489.063 45.7401 480.47 45.7401C474.025 45.7401 469.882 48.8091 469.882 54.3334C469.882 62.7732 479.549 65.5353 490.444 70.1388C501.953 75.0492 513.308 81.4941 513.308 97.9133C513.308 116.481 497.963 124 479.396 124C469.575 124 459.754 121.852 452.542 118.936L450.24 95.9185L452.389 95.3047C462.516 109.729 471.723 118.015 483.386 118.015C491.825 118.015 494.741 113.872 494.741 108.962C494.741 100.829 485.841 97.6065 476.02 93.3098C464.358 88.0925 451.468 82.4148 451.468 65.2284C451.468 47.1212 466.813 39.7556 485.38 39.7556C492.746 39.7556 500.419 40.9832 505.636 42.3642Z"></path>
                    <path d="M421.233 104.972C430.133 104.972 435.964 102.21 441.028 97.453L442.869 98.6806C438.266 113.719 426.143 124 407.729 124C383.944 124 369.366 106.66 369.366 83.3355C369.366 57.7093 386.553 39.7556 409.11 39.7556C431.974 39.7556 443.79 58.7834 443.023 77.351H394.992C396.68 97.7599 407.422 104.972 421.233 104.972ZM408.803 45.7401C400.21 45.7401 394.839 57.7093 394.839 71.0595L418.931 70.4457V64.3077C418.931 51.2644 414.941 45.7401 408.803 45.7401Z"></path>
                    <path d="M275.855 121.545V121.238C278.924 112.798 280.612 105.893 280.612 91.0082V76.4304C280.612 63.3871 278.003 56.0214 271.098 48.6558V48.3489L304.857 38.9884V55.2542L305.625 55.4076C309.921 48.3489 319.435 39.7557 333.706 39.7557C348.437 39.7557 357.798 48.5023 357.798 62.4664V91.0082C357.798 105.586 359.179 112.798 362.248 121.238V121.545H328.335V121.238C331.404 112.798 332.785 105.433 332.785 91.3151V65.9957C332.785 59.3973 329.409 54.4869 321.277 54.4869C314.678 54.4869 309.001 57.8628 305.625 62.6198V91.1616C305.625 105.433 307.006 112.798 309.921 121.238V121.545H275.855Z"></path>
                    <path d="M230.974 121.545V121.238C233.583 112.798 235.577 105.586 235.577 91.0082V76.7373C235.577 63.8474 232.969 56.3283 226.063 48.9627V48.3489L260.59 38.9884V91.1616C260.59 105.433 261.971 112.798 264.886 121.238V121.545H230.974Z"></path>
                    <path d="M220.473 98.3737L222.467 99.6013C218.017 114.026 205.741 124 187.941 124C165.691 124 150.346 108.195 150.346 83.7959C150.346 58.0162 169.067 39.7556 195.153 39.7556C205.434 39.7556 212.647 41.9039 217.25 43.5918L219.705 66.456L217.71 67.0698C208.043 54.3334 199.91 45.7401 192.238 45.7401C183.338 45.7401 175.818 55.1006 175.818 73.3612C175.818 96.0719 187.174 105.432 201.138 105.432C209.271 105.432 216.483 102.67 220.473 98.3737Z"></path>
                    <path d="M85.6772 124C75.0891 124 66.9562 117.708 66.9562 106.507C66.9562 87.7856 90.1272 79.1924 109.002 75.0492V70.4457C109.002 60.6248 104.705 56.3282 93.1962 56.3282C86.291 56.3282 79.2322 57.8627 72.02 60.9317L70.1786 58.7834C78.0046 48.9626 91.2014 39.7556 108.541 39.7556C126.035 39.7556 134.014 49.2695 134.014 63.0801V100.215C134.014 105.126 135.702 108.501 140.919 108.501C143.835 108.501 146.29 107.581 148.745 105.279L150.587 106.813C148.132 112.645 142.3 123.847 127.723 123.847C117.135 123.847 111.303 118.015 109.769 110.65C105.012 117.555 96.2653 124 85.6772 124ZM88.8996 98.6806C88.8996 104.512 93.0428 108.808 99.7946 108.808C103.631 108.808 106.853 107.427 109.002 105.893V81.0338C96.1118 84.7166 88.8996 91.0081 88.8996 98.6806Z"></path>
                    <path d="M4.91042 121.545V121.238C7.82598 112.798 9.51394 105.893 9.51394 91.0082V76.4304C9.51394 63.3871 6.90528 56.0214 0 48.6558V48.3489L33.7591 38.9884V59.2439C41.2782 47.2747 49.8715 36.6866 65.8303 39.7557L61.6872 69.0647L60.4596 69.2182C54.6284 65.075 46.4956 62.1595 42.1989 62.1595C39.5903 62.1595 36.8282 64.0009 34.5264 66.9164V91.1616C34.5264 105.433 35.9075 112.798 38.823 121.238V121.545H4.91042Z"></path>
                    <path d="M242.236 1.65672C244.201 -0.55224 247.653 -0.552239 249.617 1.65672L251.037 3.254C251.913 4.23899 253.146 4.83293 254.463 4.90361L256.597 5.01822C259.549 5.17673 261.701 7.87551 261.199 10.7885L260.836 12.8949C260.612 14.1939 260.916 15.5284 261.682 16.6016L262.923 18.3418C264.639 20.7484 263.871 24.1137 261.28 25.5372L259.407 26.5666C258.252 27.2013 257.398 28.2716 257.037 29.5391L256.45 31.5945C255.639 34.4369 252.529 35.9347 249.8 34.7967L247.828 33.9739C246.611 33.4665 245.242 33.4665 244.026 33.9739L242.053 34.7967C239.325 35.9347 236.215 34.4369 235.403 31.5944L234.817 29.5391C234.455 28.2716 233.601 27.2013 232.446 26.5666L230.573 25.5372C227.982 24.1137 227.214 20.7484 228.93 18.3418L230.172 16.6016C230.937 15.5284 231.242 14.1939 231.018 12.8949L230.654 10.7885C230.152 7.8755 232.304 5.17673 235.256 5.01822L237.39 4.90361C238.707 4.83293 239.94 4.23899 240.816 3.254L242.236 1.65672Z"></path>
                </svg>
            </a></div>
            
            <button class="hamburger" aria-label="Menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            
            <div class="nav-menu">
                <ul class="nav-list">
                    <li><a href="/index.html" class="nav-link">Accueil</a></li>
                    
                    <li class="dropdown">
                        <a href="/evenements.php" class="nav-link">Evènements
                            <span class="dropdown-arrow"></span>
                        </a>
                        <div class="dropdown-content">
                             <a href="/video/index.php">Video</a>
                            <a href="/calendrier.php">Calendrier</a>
                            <a href="/carte2.php">Carte</a>
                           
                        </div>
                    </li>
                    
                    <li class="dropdown">
                        <a href="/traditions.php" class="nav-link">Traditions
                            <span class="dropdown-arrow"></span>
                        </a>
                        <div class="dropdown-content">
                            <a href="/traditions.php">Présentation</a>
                            <a href="/jeu.html">Jeux</a>
                            
                        </div>
                    </li>
                    
                    <?php if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in']) : ?>
                        <li><a href="/membre/member_dashboard.php?id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">Mon compte</a></li>
                    <?php else : ?>
                        <li><a href="/membre/membre.php" class="nav-link">Membres</a></li>
                    <?php endif; ?>

                    <li class="dropdown">
                        <a href="#" class="nav-link">A propos de nous
                            <span class="dropdown-arrow"></span>
                        </a>
                        <div class="dropdown-content">
                            <a href="/agence.html">Agence</a>
                            <a href="/agence.html#partenaires">Partenaires</a>
                            <a href="/contact.php">Contact</a>
                        </div>
                    </li>
                    
                    <li>
                        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) : ?>
                            <a href="/admin/admin_dashboard.php">
                        <?php else : ?>
                            <a href="/admin/admin_login.php">
                        <?php endif; ?>
                            <i class='fas fa-user-cog'></i></a>
                    </li>
                    
                    
                </ul>
            </div>
        </nav>
    </header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const dropdowns = document.querySelectorAll('.dropdown');

    // Gestion du clic sur le hamburger
    hamburger.addEventListener('click', function(e) {
        e.stopPropagation(); // Empêche la propagation du clic
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        
        // Fermer tous les dropdowns quand on ouvre/ferme le menu
        if (!navMenu.classList.contains('active')) {
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });

    // Gestion des dropdowns en mobile
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('.nav-link');
        
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation(); // Empêche la propagation du clic
                dropdown.classList.toggle('active');
                
                // Fermer les autres dropdowns ouverts
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
            }
        });
    });

    // Fermer le menu quand on clique sur un lien non-dropdown
    const simpleLinks = document.querySelectorAll('.nav-list > li:not(.dropdown) > .nav-link');
    simpleLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    });

    // Fermer le menu et les dropdowns quand on clique en dehors
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar') && window.innerWidth <= 768) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });

    // Fermer le menu quand on redimensionne la fenêtre au-dessus de 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });
});
</script>
</body>
</html>