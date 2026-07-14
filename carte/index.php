<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Événements en France</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora&display=swap" rel="stylesheet" />
  <style>
  body {
  font-family: 'Sora', sans-serif;
  padding: 20px;
  background: #fff5eb;
}

h1 {
  text-align: left;
  margin-bottom: 15px;
  color: #BC163A;
  font-weight: 700;
  font-size: 28px;
}

#filtersBar {
  display: flex;
  gap: 12px;
  max-width: 800px;
  margin: 0 0 30px 0;
  align-items: center;
  flex-wrap: wrap;
  justify-content: flex-start;
}

#searchInput {
  padding: 6px 12px;
  font-size: 14px;
  border: none;
  border-radius: 16px;
  box-sizing: border-box;
  background-color: #7D6852;
  color: #000;
  width: 200px;
  outline: none;
  font-family: 'Sora', sans-serif;
  transition: box-shadow 0.3s ease;
}

#searchInput::placeholder {
  color: #000000a0;
}

#searchInput:focus {
  box-shadow: 0 0 6px #fff5eb;
}

select {
  padding: 5px 10px;
  border-radius: 16px;
  border: none;
  font-size: 13px;
  box-sizing: border-box;
  background-color: #7D6852;
  color: #fff5eb;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  min-width: max-content;
  font-family: 'Sora', sans-serif;
  background-image: url("data:image/svg+xml;charset=US-ASCII,%3csvg%20width%3d%2210%22%20height%3d%227%22%20viewBox%3d%220%200%2010%207%22%20xmlns%3d%22http%3a//www.w3.org/2000/svg%22%3e%3cpolygon%20points%3d%220%200%205%207%2010%200%22%20fill%3d%22%23fff5eb%22/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 10px 7px;
}

select:focus {
  outline: none;
  box-shadow: 0 0 6px #fff5eb;
}

label {
  font-weight: 600;
  color: #fff5eb;
  margin-right: 6px;
  user-select: none;
  font-family: 'Sora', sans-serif;
  font-size: 13px;
}

.filter-group {
  display: flex;
  align-items: center;
  background-color: #7D6852;
  padding: 4px 10px;
  border-radius: 16px;
  min-width: max-content;
}

#eventsContainer {
  max-width: 1100px;
  margin: 0;
}

.event-card {
  display: flex;
  background: t;
  border-radius: 8px;
  margin-bottom: 20px;
  overflow: hidden;
  align-items: center;
  box-shadow: none;
}

.event-image {
  width: 200px;
  height: 170px;
  object-fit: cover;
  flex-shrink: 0;
  border-radius: 12px;
}

.event-details {
  padding: 15px 20px;
  flex-grow: 1;
  color: #BC163A;
  font-family: 'Sora', sans-serif;
  text-align: left;
}

.event-title {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #BC163A;
}

.event-info {
  font-size: 14px;
  margin-bottom: 5px;
  color: #BC163A;
}

.event-info strong {
  color: #BC163A;
}

p {
  text-align: left;
  font-style: italic;
  color: #888;
  font-family: 'Sora', sans-serif;
}
 

  </style>
</head>



<body>

  <h1>Événements en France</h1>

  <div id="filtersBar">
    <input type="text" id="searchInput" placeholder="Rechercher" />

    <div class="filter-group">
      <label for="activityFilter">Type d'activité :</label>
      <select id="activityFilter">
        <option value="all">Tous</option>
        <option value="musique">Musique</option>
        <option value="cinema">Cinéma</option>
        <option value="exposition">Exposition</option>
        <option value="festival">Festival</option>
      </select>
    </div>

    <div class="filter-group">
      <label for="regionFilter">Région :</label>
      <select id="regionFilter">
        <option value="all">Toutes</option>
      </select>
    </div>
  </div>

<div id="eventsContainer"></div>
  <script>

      const eventsByRegion = {
  "idf": [
    { title: "Rock en Seine", date: "22/08/2025", price: "59€", location: "Domaine de Saint-Cloud", organizer: "We Love Green", activityType: "musique", image: "/carte/img/rockenseine.jpg" },
    { title: "Jazz à La Villette", date: "01/09/2025", price: "35€", location: "La Villette, Paris", organizer: "Paris Jazz Club", activityType: "musique", image: "/carte/img/jazzalavillette.png" }
  ],
  "auvergne": [
    { title: "Festival International du Court Métrage", date: "02/02/2025", price: "Gratuit", location: "Clermont-Ferrand", organizer: "Sauve Qui Peut le Court Métrage", activityType: "festival", image: "/carte/img/court.jpg" },
    { title: "Europavox Festival", date: "28/06/2025", price: "70€", location: "Clermont-Ferrand", organizer: "Europavox", activityType: "festival", image: "/carte/img/europavox.jpg" }
  ],
  "na": [
    { title: "Francofolies de La Rochelle", date: "10/07/2025", price: "150€", location: "La Rochelle", organizer: "Francofolies", activityType: "festival", image: "/carte/img/francofolie.jpg" },
    { title: "Festival du Périgord Noir", date: "05/08/2025", price: "25€", location: "Montignac", organizer: "Les Amis de la Musique", activityType: "festival", image: "/carte/img/perigor.jpg" }
  ],
  "occitanie": [
    { title: "Festival de Carcassonne", date: "01/07/2025", price: "60€", location: "Carcassonne", organizer: "Ville de Carcassonne", activityType: "festival", image: "/carte/img/carcasonne.png" },
    { title: "Rio Loco", date: "12/06/2025", price: "20€", location: "Toulouse", organizer: "Mairie de Toulouse", activityType: "festival", image: "/carte/img/rioloco.jpg" }
  ],
  "bretagne": [
    { title: "Festival Interceltique de Lorient", date: "08/08/2025", price: "55€", location: "Lorient", organizer: "Ville de Lorient", activityType: "festival", image: "/carte/img/Interceltique.jpg" },
    { title: "Les Tombées de la Nuit", date: "15/07/2025", price: "Gratuit", location: "Rennes", organizer: "Ville de Rennes", activityType: "festival", image: "/carte/img/nuit.jpg" }
  ],
  "grandest": [
    { title: "Festival de Musique de Strasbourg", date: "15/07/2025", price: "45€", location: "Strasbourg", organizer: "Ville de Strasbourg", activityType: "musique", image: "/carte/img/strasbourg.jpg" },
    { title: "Foire de Metz", date: "25/09/2025", price: "10€", location: "Metz", organizer: "Ville de Metz", activityType: "foire", image: "/carte/img/foiredemetz.jpg" }
  ],
  "hautsdefrance": [
    { title: "Festival de la Côte d’Opale", date: "10/08/2025", price: "25€", location: "Boulogne-sur-Mer", organizer: "Ville de Boulogne", activityType: "festival", image: "/carte/img/opale.jpg" },
    { title: "Lille Piano(s) Festival", date: "05/09/2025", price: "50€", location: "Lille", organizer: "Ville de Lille", activityType: "musique", image: "/carte/img/lille.png" }
  ],
  "normandie": [
    { title: "Festival Beauregard", date: "03/07/2025", price: "60€", location: "Hérouville-Saint-Clair", organizer: "Festival Beauregard", activityType: "festival", image: "/carte/img/beauregard.jpg" },
    { title: "Festival du Cinéma de Deauville", date: "05/09/2025", price: "80€", location: "Deauville", organizer: "Festival du Cinéma", activityType: "cinema", image: "/carte/img/deauville.jpg" }
  ],
  "paysdelaloire": [
    { title: "Hellfest Festival", date: "18/06/2025", price: "120€", location: "Clisson", organizer: "Hellfest", activityType: "musique", image: "/carte/img/hellfest.jpg" },
    { title: "Festival d’Anjou", date: "15/07/2025", price: "40€", location: "Angers", organizer: "Festival d’Anjou", activityType: "festival", image: "/carte/img/anjou.jpg" }
  ],
  "corse": [
    { title: "Festival de Musique de Calvi", date: "08/08/2025", price: "50€", location: "Calvi", organizer: "Ville de Calvi", activityType: "musique", image: "/carte/img/calvi.jpg" },
    { title: "Fiera di u Casgiu", date: "12/10/2025", price: "Gratuit", location: "Venaco", organizer: "Organisateurs locaux", activityType: "foire", image: "/carte/img/fiera.jpg" }
  ],
  "paca": [
    { title: "Festival de Cannes", date: "12/05/2025", price: "Invitation", location: "Cannes", organizer: "Festival de Cannes", activityType: "cinema", image: "/carte/img/cannes.png" },
    { title: "Les Nuits d'Azur", date: "22/07/2025", price: "30€", location: "Nice", organizer: "Ville de Nice", activityType: "musique", image: "/carte/img/azur.jpg" }
  ],
  "bfc": [
    { title: "Festival International de Musique de Besançon", date: "03/10/2025", price: "40€", location: "Besançon", organizer: "Ville de Besançon", activityType: "musique", image: "/carte/img/besancon.jpg" },
    { title: "Festival Lumière", date: "10/10/2025", price: "Gratuit", location: "Lyon", organizer: "Institut Lumière", activityType: "cinema", image: "/carte/img/lumiere.jpg" }
  ],
  "centre": [
    { title: "Festival de Loire", date: "20/09/2025", price: "20€", location: "Orléans", organizer: "Ville d'Orléans", activityType: "festival", image: "/carte/img/loire.webp" },
    { title: "Les Rencontres d'Arles", date: "05/07/2025", price: "15€", location: "Arles", organizer: "Les Rencontres d'Arles", activityType: "exposition", image: "/carte/img/arles.jpg" }
  ]
};


    // Construction de la liste des régions pour le filtre
    const regionFilter = document.getElementById("regionFilter");
    const regions = {
      "all": "Toutes",
      "idf": "Île-de-France",
      "auvergne": "Auvergne",
      "na": "Nouvelle-Aquitaine",
      "occitanie": "Occitanie",
      "bretagne": "Bretagne",
      "paca": "Provence-Alpes-Côte d'Azur",
      "bfc": "Bourgogne-Franche-Comté",
      "centre": "Centre-Val de Loire"
    };

    // Remplir la sélection des régions
    for (const [key, name] of Object.entries(regions)) {
      if (key === "all") continue; // 'Toutes' est déjà en place
      const option = document.createElement("option");
      option.value = key;
      option.textContent = name;
      regionFilter.appendChild(option);
    }

    const eventsContainer = document.getElementById("eventsContainer");
    const searchInput = document.getElementById("searchInput");
    const activityFilter = document.getElementById("activityFilter");

    // Fonction pour afficher les événements filtrés
    function displayEvents(filteredEvents) {
      eventsContainer.innerHTML = "";
      if (filteredEvents.length === 0) {
        eventsContainer.innerHTML = "<p>Aucun événement trouvé.</p>";
        return;
      }

      for (const event of filteredEvents) {
        const eventCard = document.createElement("div");
        eventCard.className = "event-card";

        const img = document.createElement("img");
        img.className = "event-image";
        img.src = event.image;
        img.alt = event.title;

        const details = document.createElement("div");
        details.className = "event-details";

        const title = document.createElement("h2");
        title.className = "event-title";
        title.textContent = event.title;

        const infoDate = document.createElement("p");
        infoDate.className = "event-info";
        infoDate.innerHTML = `<strong>Date :</strong> ${event.date}`;

        const infoPrice = document.createElement("p");
        infoPrice.className = "event-info";
        infoPrice.innerHTML = `<strong>Prix :</strong> ${event.price}`;

        const infoLocation = document.createElement("p");
        infoLocation.className = "event-info";
        infoLocation.innerHTML = `<strong>Lieu :</strong> ${event.location}`;

        const infoOrganizer = document.createElement("p");
        infoOrganizer.className = "event-info";
        infoOrganizer.innerHTML = `<strong>Organisateur :</strong> ${event.organizer}`;

        details.append(title, infoDate, infoPrice, infoLocation, infoOrganizer);
        eventCard.append(img, details);
        eventsContainer.appendChild(eventCard);
      }
    }

    // Fonction pour récupérer tous les événements sous forme de tableau
    function getAllEvents() {
      let allEvents = [];
      for (const regionEvents of Object.values(eventsByRegion)) {
        allEvents = allEvents.concat(regionEvents);
      }
      return allEvents;
    }

    // Fonction pour filtrer les événements selon les critères
    function filterEvents() {
      const searchText = searchInput.value.trim().toLowerCase();
      const selectedActivity = activityFilter.value;
      const selectedRegion = regionFilter.value;

      let eventsToFilter = selectedRegion === "all" ? getAllEvents() : eventsByRegion[selectedRegion] || [];

      const filtered = eventsToFilter.filter(event => {
        const matchesSearch =
          event.title.toLowerCase().includes(searchText) ||
          event.location.toLowerCase().includes(searchText);

        const matchesActivity = selectedActivity === "all" || event.activityType === selectedActivity;

        return matchesSearch && matchesActivity;
      });

      displayEvents(filtered);
    }

    // Événements d'écoute pour filtrage dynamique
    searchInput.addEventListener("input", filterEvents);
    activityFilter.addEventListener("change", filterEvents);
    regionFilter.addEventListener("change", filterEvents);

    // Affichage initial
    displayEvents(getAllEvents());
  </script>

</body>
</html>
