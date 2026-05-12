<?php
session_start();
require __DIR__ . '/../config/db.php';
require '../vendor/autoload.php';

// ila ma connectéch → login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// jib event id mn URL
$event_id = (int)($_GET['event_id'] ?? 0);
// jib details dyal event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute(['id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// ila event ma kaynch
if (!$event) {
    die("Event introuvable.");
}

// ila sold out
if ($event['nbPlaces'] <= 0) {
    die("Plus de places disponibles.");
}

\Stripe\Stripe::setApiKey('sk_test_51TOagtBLRMTX4QBlssAcnT5x5neZvW4G2pjZ8IJ6ncOXdWRhXaOhUM5VV316jXPCldLU8xOlV2c78xoPcrzDfTri00T9nZSBNE'); // ← bdl hada

// créer checkout session
$checkout = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency'     => 'mad',
            'unit_amount'  => $event['price'] * 100, // Stripe ktkhdam b centimes
            'product_data' => [
                'name' => $event['title'],
            ],
        ],
        'quantity' => 1,
    ]],
    'mode'        => 'payment',
        'success_url' => 'http://localhost/gestion_projet/public/success.php?event_id=' . $event_id,
'cancel_url'  => 'http://localhost/gestion_projet/public/index.php',
]);

header('Location: ' . $checkout->url);
exit;