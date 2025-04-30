<?php
// users.php
// Ustawienia błędów
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
// Sprawdzanie, czy administrator jest zalogowany
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php"); // Przekierowanie na stronę logowania
    exit();
}
$ja = $_SESSION['admin'];
// Sprawdzanie, czy użytkownik jest administratorem
$sql = "SELECT * FROM `uzytkownicy` where not email = '$ja'";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVD Rental</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/c876edd7c5.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<nav>
        <div class="wrapper nav-wrapper">
            <div class="nav-leftside">
                <a href="./admin_panel.php"><img src="../img/DVD.jpg" alt="" class="nav_logo"></a>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="admin_panel.php">Home</a>
                </li>
                <li>
                <a class="admin-link" href="users.php">Użytkownicy</a>
                </li>
                <li>
                <a class="admin-link" href="films.php">Filmy</a>
                </li>
                <li>
                <?php
                    //Sprawdza czy jest zalogowany jeśli jest to:
                    if (isset($_SESSION['admin'])) {
                        // Użytkownik jest zalogowany
                        echo '<a href="#" onclick="logoutUser()" >WYLOGUJ</a>'; // Przycisk wylogowania
                    }
                    ?>
                </li>
            </ul>
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
</nav>
<header class="header" id="header">
        <div class="header-bg container">
            <div class="container">
                <div class="container__item container__item-one"></div>
                <div class="container__item container__item-two"></div>
                <div class="container__item container__item-three"></div>
            </div>
        </div>
    </header>
    <div class="adminsraczka">
    <h1 class="section-heading heading-admin">Zarządzaj Użytkownikami</h1>
    <?php
if ($result->num_rows > 0) {
    echo '<div class="zalegle-box container-zalegajacy">';
    while ($user = $result->fetch_assoc()) {
        echo '<div class="zalegajacy">';
        echo '<p>ID: ' . htmlspecialchars($user['id']) . '</p>';
        echo '<p>Nazwa: ' . htmlspecialchars($user['nazwa']) . '</p>';
        echo '<p>Email: ' . htmlspecialchars($user['email']) . '</p>';
        echo '<p>Ban: ' . htmlspecialchars($user['ban']) . '</p>';
        echo '<p>Admin: ' . htmlspecialchars($user['admin']) . '</p>';
        echo '<div class="user-actions">';
        echo '<a href="ban.php?id=' . htmlspecialchars($user['id']) . '" class="carousel-item-btn">Zablokuj</a>';
        echo '<a href="unban.php?id=' . htmlspecialchars($user['id']) . '" class="carousel-item-btn">Odblokuj</a>';
        echo '</div>'; // Zamknięcie user-actions
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Brak użytkowników w bazie.</p>';
}
?>
    </div>
    <script src="../js/index.js"></script>
</body>
</html>