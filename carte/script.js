const eventsByRegion = {
  "idf": [
    { title: "Rock en Seine", date: "22/08/2025", price: "59€", location: "Domaine de Saint-Cloud", organizer: "We Love Green", activityType: "musique", image: "https://upload.wikimedia.org/wikipedia/commons/6/67/Rock_En_Seine_2012_-_Black_Keys_02.jpg" },
    { title: "Jazz à La Villette", date: "01/09/2025", price: "35€", location: "La Villette, Paris", organizer: "Paris Jazz Club", activityType: "musique", image: "https://upload.wikimedia.org/wikipedia/commons/d/d3/La_Villette_-_Philharmonie_de_Paris_1.jpg" }
  ],
  "auvergne": [
    { title: "Festival International du Court Métrage", date: "02/02/2025", price: "Gratuit", location: "Clermont-Ferrand", organizer: "Sauve Qui Peut le Court Métrage", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/0/03/Clermont-Ferrand_vue_du_Puy_de_Dome.jpg" },
    { title: "Europavox Festival", date: "28/06/2025", price: "70€", location: "Clermont-Ferrand", organizer: "Europavox", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/6/64/Europavox.jpg" }
  ],
  "na": [
    { title: "Francofolies de La Rochelle", date: "10/07/2025", price: "150€", location: "La Rochelle", organizer: "Francofolies", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/b/be/Francofolies.jpg" },
    { title: "Festival du Périgord Noir", date: "05/08/2025", price: "25€", location: "Montignac", organizer: "Les Amis de la Musique", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/4/4b/Montignac_Vue.jpg" }
  ],
  "occitanie": [
    { title: "Festival de Carcassonne", date: "01/07/2025", price: "60€", location: "Carcassonne", organizer: "Ville de Carcassonne", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/d/db/Carcassonne_Cité.jpg" },
    { title: "Rio Loco", date: "12/06/2025", price: "20€", location: "Toulouse", organizer: "Mairie de Toulouse", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/6/61/Rio_Loco_Toulouse.jpg" }
  ],
  "bretagne": [
    { title: "Festival Interceltique de Lorient", date: "08/08/2025", price: "55€", location: "Lorient", organizer: "Ville de Lorient", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/7/7d/Lorient_Festival_Interceltique_2011.jpg" },
    { title: "Les Tombées de la Nuit", date: "15/07/2025", price: "Gratuit", location: "Rennes", organizer: "Ville de Rennes", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/5/54/Tomb%C3%A9es_de_la_nuit_Rennes_2018.jpg" }
  ],
  "paca": [
    { title: "Festival de Cannes", date: "12/05/2025", price: "Invitation", location: "Cannes", organizer: "Festival de Cannes", activityType: "cinema", image: "https://upload.wikimedia.org/wikipedia/commons/a/a7/Cannes_panoramic_view.jpg" },
    { title: "Les Nuits d'Azur", date: "22/07/2025", price: "30€", location: "Nice", organizer: "Ville de Nice", activityType: "musique", image: "https://upload.wikimedia.org/wikipedia/commons/9/9a/Nice_Festival_Music.jpg" }
  ],
  "bfc": [
    { title: "Festival International de Musique de Besançon", date: "03/10/2025", price: "40€", location: "Besançon", organizer: "Ville de Besançon", activityType: "musique", image: "https://upload.wikimedia.org/wikipedia/commons/3/3e/Besançon_-_Place_de_la_République.jpg" },
    { title: "Festival Lumière", date: "10/10/2025", price: "Gratuit", location: "Lyon", organizer: "Institut Lumière", activityType: "cinema", image: "https://upload.wikimedia.org/wikipedia/commons/2/29/Institut_Lumière_Lyon_2014.jpg" }
  ],
  "centre": [
    { title: "Festival de Loire", date: "20/09/2025", price: "20€", location: "Orléans", organizer: "Ville d'Orléans", activityType: "festival", image: "https://upload.wikimedia.org/wikipedia/commons/9/95/Orleans_Loire_Festival_2018.jpg" },
    { title: "Les Rencontres d'Arles", date: "05/07/2025", price: "15€", location: "Arles", organizer: "Les Rencontres d'Arles", activityType: "exposition", image: "https://upload.wikimedia.org/wikipedia/commons/0/06/Arles_-_Amphitheatre_01.jpg" }
  ]
};

const regionFilter = document.getElementById("regionFilter");
const activityFilter = document.getElementById("activityFilter");
const searchInput = document.getElementById("searchInput");
const eventsContainer = document.getElementById("eventsContainer");

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

// Remplir le filtre régions (sauf "all" qui est géré dans le select HTML)
for (const [key, name] of Object.entries(regions)) {
  if (key === "all") continue;
  const option = document.createElement("option");
  option.value = key;
  option.textContent = name;
  regionFilter.appendChild(option);
}

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

function getAllEvents() {
  let allEvents = [];
  for (const regionEvents of Object.values(eventsByRegion)) {
    allEvents = allEvents.concat(regionEvents);
  }
  return allEvents;
}

function filterEvents() {
  const searchText = searchInput.value.trim().toLowerCase();
  const selectedActivity = activityFilter.value;
  const selectedRegion = regionFilter.value;

  // Récupérer les événements selon la région choisie
  let eventsToFilter = selectedRegion === "all" ? getAllEvents() : eventsByRegion[selectedRegion] || [];

  // Filtrer par recherche texte + activité
  const filtered = eventsToFilter.filter(event => {
    const matchesSearch =
      event.title.toLowerCase().includes(searchText) ||
      event.location.toLowerCase().includes(searchText);

    const matchesActivity = selectedActivity === "all" || event.activityType === selectedActivity;

    return matchesSearch && matchesActivity;
  });

  displayEvents(filtered);
}

// Écouteurs d'événements
searchInput.addEventListener("input", filterEvents);
activityFilter.addEventListener("change", filterEvents);
regionFilter.addEventListener("change", filterEvents);

// Afficher tous les événements au chargement
displayEvents(getAllEvents());
