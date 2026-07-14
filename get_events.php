<?php


        
$pdo = new PDO("mysql:host=localhost;dbname=u237218091_racine;charset=utf8mb4", "u237218091_racine", "racineSSJJ1234");
$stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
