<?php
session_start();
require __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$event_id = (int)$_GET['event_id'];

// سجل الحجز
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id, booked_at) 
    VALUES (:user_id, :event_id, NOW())");
$stmt->execute([
    'user_id'  => $_SESSION['user_id'],
    'event_id' => $event_id
]);

// نقص place
$stmt = $pdo->prepare("UPDATE events SET nbPlaces = nbPlaces - 1 
                        WHERE id = :id AND nbPlaces > 0");
$stmt->execute(['id' => $event_id]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Succès</title>
    <link rel="stylesheet" href="../css/success.css">
        <link rel="icon" type="image/png" href="../img/logo.png.jpg">



</head>
<body>
    <div class="page-wrapper">
        <div class="card">
            <span class="success-icon">✅</span>
            <h1>Réservation confirmée !</h1>
            <p>Merci pour votre réservation.</p>
            <a href="index.php" class="btn-back">← Retour aux events</a>
        </div>
    </div>

    <script src="main.js"></script>
</body>
</html>