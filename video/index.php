<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'u237218091_racine';
$username = 'u237218091_racine';
$password = 'racineSSJJ1234';

// Gestion des commentaires
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_comment') {
    header('Content-Type: application/json');
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $videoId = filter_input(INPUT_POST, 'videoId', FILTER_VALIDATE_INT);
        $userName = "Invité"; // Vous pouvez personnaliser ceci
        $commentText = filter_input(INPUT_POST, 'commentText', FILTER_SANITIZE_STRING);

        if (!$videoId || !$commentText) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO comments (video_id, user_name, comment_text) VALUES (:video_id, :user_name, :comment_text)");
        $stmt->execute([
            ':video_id' => $videoId,
            ':user_name' => $userName,
            ':comment_text' => $commentText
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Commentaire enregistré']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        exit;
    }
}

// Récupération des commentaires
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_comments') {
    header('Content-Type: application/json');
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $videoId = filter_input(INPUT_GET, 'videoId', FILTER_VALIDATE_INT);

        if (!$videoId) {
            echo json_encode([]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM comments WHERE video_id = :video_id ORDER BY created_at DESC");
        $stmt->execute([':video_id' => $videoId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($comments);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nos Vidéos - Racines</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #fff5eb;
    }

    .container {
      display: flex;
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      gap: 20px;
    }

    .video-section {
      flex: 3;
    }

    #mainVideoContainer {
      position: relative;
      background: black;
      height: 400px;
      border-radius: 8px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 15px;
    }

    #videoOverlay {
      color: white;
      font-size: 20px;
    }

    .video-info {
      background: #b92a3a;
      color: white;
      padding: 15px;
      border-radius: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .search-container {
      margin: 20px auto;
      max-width: 1200px;
      padding: 0 20px;
      position: relative;
    }

    #searchInput {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 25px;
      font-size: 14px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .playlist-container {
      flex: 1;
      background: #f8f8f8;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-height: 600px;
      display: flex;
      flex-direction: column;
    }

    .playlist-header {
      background-color: #BC163A;
      color: white;
      padding: 10px;
      text-align: center;
      font-weight: bold;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .playlist-content {
      flex: 1;
      overflow-y: auto;
    }

    .video-item {
      display: flex;
      align-items: center;
      padding: 10px;
      margin-bottom: 10px;
      background: white;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }

    .video-item:hover {
      background: #f0f0f0;
    }

    .video-item.active {
      background: rgba(188, 22, 58, 0.1);
      border-left: 3px solid #BC163A;
    }

    .video-thumbnail {
      width: 80px;
      height: 50px;
      background-color: #eee;
      background-size: cover;
      background-position: center;
      margin-right: 10px;
      border-radius: 4px;
    }

    .video-title {
      flex: 1;
      font-size: 14px;
      font-weight: bold;
    }

    .video-duration {
      font-size: 12px;
      color: #666;
    }

    .comments-section {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .comments-section h3 {
      color: #b92a3a;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .comment {
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .comment-user {
      font-weight: bold;
      color: #b92a3a;
    }

    .comment-date {
      font-size: 12px;
      color: #999;
    }

    #commentForm {
      margin-top: 20px;
    }

    #commentInput {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      min-height: 80px;
      margin-bottom: 10px;
    }

    #submitComment {
      background: #b92a3a;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
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

  <!-- Barre de recherche -->
  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Rechercher une vidéo...">
  </div>

  <!-- Contenu principal -->
  <div class="container">
    <div class="video-section">
      <div id="mainVideoContainer">
        <div id="videoOverlay">Sélectionnez une vidéo dans la playlist</div>
      </div>
      
      <div class="video-info" id="videoInfo">
        <div><strong>Titre :</strong> <span id="videoTitle">-</span></div>
        <div><strong>Vues :</strong> <span id="videoViews">-</span></div>
        <div><strong>Durée :</strong> <span id="videoDuration">-</span></div>
        <div style="flex: 1 1 100%;"><strong>Description :</strong> <span id="videoDescription">-</span></div>
      </div>
    </div>

    <!-- Playlist -->
    <div class="playlist-container">
      <div class="playlist-header">Playlist Vidéos</div>
      <div class="playlist-content" id="playlistContent">
        <!-- Les vidéos seront chargées ici par JavaScript -->
      </div>
    </div>
  </div>

  <!-- Commentaires -->
  <div class="comments-section">
    <h3>Commentaires</h3>
    <div id="commentsContainer">
      <!-- Les commentaires seront chargés ici -->
    </div>
    
    <form id="commentForm">
      <textarea id="commentInput" placeholder="Ajouter un commentaire..." required></textarea>
      <button type="button" id="submitComment">Publier</button>
    </form>
  </div>
<!--footer-->
<script src="https://cdn.userway.org/widget.js" data-account="wLQmjoDJXO"></script>
<div id="footer-placeholder"></div>

<script>
  fetch("/structure/footer.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("footer-placeholder").innerHTML = data;
    });
</script>
<script>
// Données des vidéos
const videoDatabase = [
  {
    id: 1,
    title: "Présentation du thème",
    src: "les-arts-et-traditions-populaires.mp4",
    thumbnail: "racines_page.png",
    views: "",
    duration: "02:23",
    description: "Présentation du thème arts et traditions populaires",
    upload_date: "2025-06-04"
  },
  {
    id: 2,
    title: "Traditions populaires de Bretagne",
    src: "/video/traditions-populaires-de-bretagne.mp4",
    thumbnail: "racines_page.png",
    views: "89 765",
    duration: "1:37",
    description: "Découverte des traditions bretonnes",
    upload_date: "2023-04-22"
  },
  {
    id: 3,
    title: "Le béret, traditionnel ?",
    src: "/video/RACINES-Audiovisuel.mp4",
    thumbnail: "racines_page.png",
    views: "124444",
    duration: "01:12",
    description: "Le béret dans la culture française",
    upload_date: "2023-03-10"
  },
  {
    id: 4,
    title: "Vidéo de droit Racines",
    src: "/video/SAE202-Racines-Droit.mp4",
    thumbnail: "racines_page.png",
    views: "42 897",
    duration: "09:24",
    description: "Aspects juridiques du projet",
    upload_date: "2025-06-04"
  }
];

// Variables globales
let currentVideoId = null;
let currentUser = "Invité";

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
  loadVideos();
  setupEventListeners();
  
  if (videoDatabase.length > 0) {
    selectVideo(videoDatabase[0]);
  }
});

// Charger les vidéos dans la playlist
function loadVideos(filteredVideos = null) {
  const playlistContent = document.getElementById('playlistContent');
  playlistContent.innerHTML = '';
  
  const videosToDisplay = filteredVideos || videoDatabase;
  
  if (videosToDisplay.length === 0) {
    playlistContent.innerHTML = '<p>Aucune vidéo trouvée</p>';
    return;
  }
  
  videosToDisplay.forEach(video => {
    const videoItem = document.createElement('div');
    videoItem.className = 'video-item';
    videoItem.innerHTML = `
      <div class="video-thumbnail" style="background-image: url('${video.thumbnail}')"></div>
      <div>
        <div class="video-title">${video.title}</div>
        <div class="video-duration">${video.duration}</div>
      </div>
    `;
    
    videoItem.addEventListener('click', () => selectVideo(video));
    playlistContent.appendChild(videoItem);
  });
}

// Sélectionner une vidéo
function selectVideo(video) {
  currentVideoId = video.id;
  
  // Mettre à jour la sélection visuelle
  document.querySelectorAll('.video-item').forEach(item => {
    item.classList.remove('active');
    if (item.querySelector('.video-title').textContent === video.title) {
      item.classList.add('active');
    }
  });
  
  // Mettre à jour le lecteur vidéo
  const videoContainer = document.getElementById('mainVideoContainer');
  videoContainer.innerHTML = `
    <video controls autoplay width="100%" height="100%">
      <source src="${video.src}" type="video/mp4">
      Votre navigateur ne supporte pas la lecture de vidéos.
    </video>
  `;
  
  // Mettre à jour les infos de la vidéo
  document.getElementById('videoTitle').textContent = video.title;
  document.getElementById('videoViews').textContent = video.views;
  document.getElementById('videoDuration').textContent = video.duration;
  document.getElementById('videoDescription').textContent = video.description;
  
  // Charger les commentaires
  loadComments(video.id);
}

// Charger les commentaires
function loadComments(videoId) {
  fetch(`?action=get_comments&videoId=${videoId}`)
    .then(response => response.json())
    .then(comments => {
      const commentsContainer = document.getElementById('commentsContainer');
      
      if (!comments || comments.length === 0) {
        commentsContainer.innerHTML = '<p>Aucun commentaire pour cette vidéo. Soyez le premier à commenter !</p>';
        return;
      }
      
      if (comments.error) {
        commentsContainer.innerHTML = `<p>Erreur: ${comments.error}</p>`;
        return;
      }
      
      commentsContainer.innerHTML = comments.map(comment => `
        <div class="comment">
          <div>
            <span class="comment-user">${comment.user_name}</span>
            <span class="comment-date">${formatDate(comment.created_at)}</span>
          </div>
          <p>${comment.comment_text}</p>
        </div>
      `).join('');
    })
    .catch(error => {
      console.error('Erreur:', error);
      document.getElementById('commentsContainer').innerHTML = '<p>Erreur lors du chargement des commentaires</p>';
    });
}

// Formater la date
function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

// Configurer les écouteurs d'événements
function setupEventListeners() {
  // Recherche
  document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const filteredVideos = videoDatabase.filter(video => 
      video.title.toLowerCase().includes(searchTerm) || 
      video.description.toLowerCase().includes(searchTerm)
    );
    loadVideos(filteredVideos);
  });
  
  // Soumission de commentaire
  document.getElementById('submitComment').addEventListener('click', function() {
    const commentText = document.getElementById('commentInput').value.trim();
    
    if (!commentText) {
      alert('Veuillez écrire un commentaire');
      return;
    }
    
    if (!currentVideoId) {
      alert('Veuillez sélectionner une vidéo avant de commenter');
      return;
    }
    
    const formData = new FormData();
    formData.append('action', 'save_comment');
    formData.append('videoId', currentVideoId);
    formData.append('commentText', commentText);
    
    fetch('', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        loadComments(currentVideoId);
        document.getElementById('commentInput').value = '';
      } else {
        alert('Erreur: ' + (data.message || 'Erreur inconnue'));
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      alert('Une erreur est survenue');
    });
  });
}
</script>
</body>
</html>