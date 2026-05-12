<?php
session_start();
require '../config/db.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_SESSION['user_id'])){
        header('Location: login.php');
        exit;
    }else{
        $eventid = (int)$_POST['id'];
        header('Location: payment.php?id=' . $eventid);
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM events WHERE date_event >= CURDATE()");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <link rel="stylesheet" href="../css/style.css">
     <link rel="icon" type="image/png" href="../img/logo.png.jpg">
</head>
<body>
    <div class="navbar">
    <h1>✨ Events</h1>
    <div class="right">
        <button class="theme-toggle" id="toggleBtn">🌙 Dark</button>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profil.php"><?= htmlspecialchars($_SESSION['username']) ?></a>
            <a href="tickets.php">🎫 Tickets</a>
            <a href="logout.php">⏻ Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
<div class='container'>

    <?php foreach($events as $event): ?>
        <div class='card'>
            <h4><?= htmlspecialchars($event['title']) ?></h4>
            <p>Date: <?= htmlspecialchars($event['date_event']) ?></p>
            <p>Places disponibles: <?= (int)$event['nbPlaces'] ?></p>

            <form method='post'>
                <input type='hidden' name='id' value='<?= (int)$event['id'] ?>'>

                <?php if($event['nbPlaces'] > 0): ?>
                    <button class="btn btn-primary">Réserver</button>
                <?php else: ?>
                    <span class="sold">SOLD OUT</span>
                <?php endif; ?>

            </form>
        </div>
    <?php endforeach; ?>
</div>
<script src="script.js"></script>
</body>
</html>