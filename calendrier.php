<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=u237218091_racine;charset=utf8', 'u237218091_racine', 'racineSSJJ1234');

$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

$startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
$endDate = date("Y-m-t", strtotime($startDate));

$stmt = $pdo->prepare("SELECT * FROM evenements WHERE date BETWEEN ? AND ?");
$stmt->execute([$startDate, $endDate]);

$events = [];
$promos = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $day = (int) date('j', strtotime($row['date']));
    $events[$day][] = $row;
    $promos[$row['id']] = strtoupper(substr(hash('sha256', $row['title'] . $row['date']), 0, 10));
}

$daysInMonth = date('t', strtotime($startDate));
$firstDay = date('N', strtotime($startDate));
$monthName = strftime('%B', strtotime($startDate));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Calendrier des événements - Racines</title>
  <style>
    body {
      background: #fdf1e8;
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      text-align: center;
      scroll-behavior: smooth;
    }

    .parallax {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      width: 100%;
      opacity: 0.5;
    }

    .parallax1 { background-image: url('/img/patterns/rouge/soleil1.png'); height: 100px; margin-bottom: 10px; }
    .parallax2 { background-image: url('/img/patterns/rouge/vase.png'); height: 300px; }

    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 12px;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .day {
      background-color: #fff0da;
      border-radius: 16px;
      height: 140px;
      padding: 10px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }

    .day-number {
      font-weight: bold;
      background: #f3c38c;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      margin: 0 auto 5px;
      line-height: 26px;
      font-size: 0.85em;
    }

    .event {
      font-size: 0.75em;
      margin: 3px 0;
      padding: 3px 6px;
      background-color: #fcd2a5;
      border-radius: 6px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .event a {
      text-decoration: none;
      color: #333;
    }

    .calendar-nav {
      margin: 5px auto;
    }

    .calendar-nav a {
      padding: 10px 20px;
      margin: 0 10px;
      background: #d6a66f;
      color: white;
      text-decoration: none;
      border-radius: 8px;
    }

    .calendar-nav a:hover {
      background: #c08b50;
    }

    .promo-container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 10px;
    }

    #filterInput {
      padding: 10px;
      width: 90%;
      max-width: 400px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .promo-table {
      width: 100%;
      border-collapse: collapse;
      background: #fffaf1;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .promo-table th {
      background: #f2c48d;
      color: #333;
      padding: 12px;
      font-weight: bold;
    }

    .promo-table td {
      padding: 10px;
      border: 1px solid #e5e5e5;
    }

    .promo-table a {
      color: #c57c28;
      text-decoration: none;
      font-weight: bold;
    }

    .promo-table tr:hover {
      background: #fff1d6;
    }

    #header-placeholder {
      margin-bottom: 10px; /* Réduction de l'espace */
    }

  </style>
</head>
<body>

<!-- header -->
<div id="header-placeholder"></div>
<script>
  fetch("/structure/header.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("header-placeholder").innerHTML = data;
    });
</script>

<h1>Calendrier - <?= ucfirst(strftime('%B %Y', strtotime($startDate))) ?></h1>

<!-- Navigation par mois -->
<div class="calendar-nav">
  <a href="?month=<?= $month == 1 ? 12 : $month - 1 ?>&year=<?= $month == 1 ? $year - 1 : $year ?>">← Mois précédent</a>
  <a href="?month=<?= $month == 12 ? 1 : $month + 1 ?>&year=<?= $month == 12 ? $year + 1 : $year ?>">Mois suivant →</a>
</div>

<!-- Calendrier -->
<div class="calendar">
  <?php for ($i = 1; $i < $firstDay; $i++): ?>
    <div class="day"></div>
  <?php endfor; ?>
  <?php for ($d = 1; $d <= $daysInMonth; $d++): ?>
    <div class="day">
      <div class="day-number"><?= $d ?></div>
      <div class="events">
        <?php if (isset($events[$d])):
          foreach ($events[$d] as $ev): ?>
            <div class="event">
              <a href="evenement.php?id=<?= $ev['id'] ?>">
                <?= htmlspecialchars($ev['title']) ?>
              </a>
            </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  <?php endfor; ?>
</div>

<!-- Parallaxe 1 déplacée ici et réduite -->
<div class="parallax parallax1"></div>

<!-- Parallaxe 2 -->
<div class="parallax parallax2"></div>

<!-- Codes promos -->
<div class="promo-container">
  <h2>Codes Promos</h2>
  <input type="text" id="filterInput" placeholder="Rechercher un événement...">
  <table class="promo-table" id="promoTable">
    <thead>
      <tr>
        <th>Événement</th>
        <th>Date</th>
        <th>Code Promo</th>
        <th>Voir</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($events as $eventList): foreach ($eventList as $ev): ?>
        <tr>
          <td><?= htmlspecialchars($ev['title']) ?></td>
          <td><?= htmlspecialchars($ev['date']) ?></td>
          <td><?= $promos[$ev['id']] ?></td>
          <td><a href="evenement.php?id=<?= $ev['id'] ?>">Détails</a></td>
        </tr>
      <?php endforeach; endforeach; ?>
    </tbody>
  </table>
</div>

<!-- footer -->
<div id="footer-placeholder"></div>
<script>
  fetch("/structure/footer.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("footer-placeholder").innerHTML = data;
    });

  document.getElementById("filterInput").addEventListener("keyup", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#promoTable tbody tr");

    rows.forEach(row => {
      const eventTitle = row.cells[0].textContent.toLowerCase();
      row.style.display = eventTitle.includes(filter) ? "" : "none";
    });
  });
</script>

</body>
</html>
