<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
// Sprawdzanie, czy administrator jest zalogowany
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php"); // Przekierowanie na stronę logowania
    exit();
}

$sql = "SELECT * FROM `filmy`";
$result = $mysqli->query($sql);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                    <a class="admin-link admin-link-inne" href="add_film.php" class="carousel-add-btn">Dodaj film</a>
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
    <h1 class="section-heading heading-admin">Zarządzaj Filmami</h1>
    <div class="carousel-container">
        <div class="carousel-header">
            
        <?php
        if ($result->num_rows > 0) {
            echo '<div class="container-zalegajacy">';
            while ($film = $result->fetch_assoc()) {
                echo '<div class="carousel-item">';
                if (!empty($film['okladka'])) {
                    echo '<a href="/film.php?id=' . htmlspecialchars($film['id']) . '">';
                    echo '<img src="' . htmlspecialchars($film['okladka']) . '" alt="' . htmlspecialchars($film['tytul']) . '" class="carousel-item-img">';
                    echo '</a>';
                }
                echo '<p class="carousel-item-id">ID: ' . htmlspecialchars($film['id']) . '</p>';
                echo '<p class="carousel-item-title">' . htmlspecialchars($film['tytul']) . '</p>';
                echo '<p class="carousel-item-director">' . htmlspecialchars($film['rezyser']) . '</p>';
                echo '<p class="carousel-item-year">Rok: ' . htmlspecialchars($film['rok_premiery']) . '</p>';
                echo '<p class="carousel-item-genre">Gatunek: ' . htmlspecialchars($film['gatunek']) . '</p>';
                echo '<p class="carousel-item-description">' . htmlspecialchars($film['data_dodania']) . '</p>';
                echo '<div class="carousel-item-actions">';
                echo '<a href="edit_film.php?id=' . htmlspecialchars($film['id']) . '" class="carousel-item-btn">Edytuj</a>';
                echo '<a href="delete_film.php?id=' . htmlspecialchars($film['id']) . '" class="carousel-item-btn">Usuń</a>';
                echo '</div>';
                echo '</div>';
            }
                echo '</div>';
        } else {
            echo "Brak filmów w bazie.";
        }
        ?>
    </div>
    </div>
    <script src="../js/index.js"></script>
</body>
</html>