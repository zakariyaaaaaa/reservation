<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM bookings WHERE user_id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — Events</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="icon" type="image/png" href="../img/logo.png.jpg">

</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            <span class="navbar-icon">✦</span>
            Events
        </a>
        <button class="theme-toggle" id="toggleBtn">🌙 Dark</button>
    </nav>

    <div class="profil-wrap">
        <div class="profil-card">

            <div class="avatar">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>

            <h2><?= htmlspecialchars($user['name']) ?></h2>

            <div class="badge">
                🎫 <?= $total ?> Réservation<?= $total > 1 ? 's' : '' ?>
            </div>

            <div class="info">
                <p>👤 Nom: <span><?= htmlspecialchars($user['name']) ?></span></p>
                <p>✉️ Email: <span><?= htmlspecialchars($user['email']) ?></span></p>
            </div>

            <div class="btns">
                <a href="edit_profil.php" class="btn btn-edit">✏️ Modifier profil</a>
                <a href="tickets.php"     class="btn btn-tickets">🎫 Mes tickets</a>
                <a href="logout.php"      class="btn btn-logout">⏻ Logout</a>
            </div>

        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>