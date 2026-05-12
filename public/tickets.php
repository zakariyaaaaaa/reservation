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

$stmt = $pdo->prepare("
    SELECT bookings.id as booking_id, bookings.booked_at,
           events.title, events.date_event, events.location
    FROM bookings
    JOIN events ON bookings.event_id = events.id
    WHERE bookings.user_id = :user_id
    ORDER BY bookings.booked_at DESC
");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tickets — Events</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/tickets.css">
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

    <div class="tickets-wrap">

        <div class="page-header">
            <h1>Mes Tickets</h1>
            <p>Bonjour, <strong><?= htmlspecialchars($user['name']) ?></strong></p>
        </div>

        <div class="tickets">
            <?php if (empty($bookings)): ?>
                <div class="no-tickets">
                    <p>Aucun ticket pour le moment.</p>
                    <a href="index.php" class="btn">Voir les events</a>
                </div>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <?php
                        $qr_data = "Booking #" . $booking['booking_id'] .
                                   " | Event: " . $booking['title'] .
                                   " | User: " . $user['name'];
                        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($qr_data);
                    ?>
                    <div class="ticket">
                        <h2>✅ <?= htmlspecialchars($booking['title']) ?></h2>

                        <div class="ticket-info">
                            <p>📅 Date: <span><?= htmlspecialchars($booking['date_event']) ?></span></p>
                            <p>📍 Lieu: <span><?= htmlspecialchars($booking['location']) ?></span></p>
                            <p>👤 Nom: <span><?= htmlspecialchars($user['name']) ?></span></p>
                            <p>🕐 Réservé le: <span><?= htmlspecialchars($booking['booked_at']) ?></span></p>
                        </div>

                        <img src="<?= $qr_url ?>" alt="QR Code">

                        <div class="booking-id">Booking #<?= $booking['booking_id'] ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="back-btn">
            <a href="index.php" class="btn">← Retour aux events</a>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>