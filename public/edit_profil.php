<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $errors[] = "Le nom est requis.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $name, 'id' => $_SESSION['user_id']]);
        $_SESSION['username'] = $name;
        $success = true;
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="../css/edit_profil.css">
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

    <div class="page-wrapper">
        <div class="card">
            <h2 class="card-title">✏️ Modifier Profil</h2>

            <?php if ($success): ?>
                <div class="alert alert-success">✅ Profil mis à jour!</div>
            <?php endif; ?>

            <?php foreach ($errors as $e): ?>
                <div class="alert alert-error"><?= $e ?></div>
            <?php endforeach; ?>

            <form method="post">
                <label>Nom</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
                <button type="submit">Enregistrer</button>
            </form>

            <a href="profil.php" class="back-link">← Retour</a>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>