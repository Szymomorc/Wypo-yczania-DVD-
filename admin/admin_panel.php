<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
// Sprawdzanie, czy administrator jest zalogowany
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php"); // Przekierowanie na stronę logowania
    exit();
}

$user = $_SESSION['admin'];

// Przygotowanie zapytania
$sql = "SELECT nazwa FROM `uzytkownicy` WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$dane = $result->fetch_assoc();


// Sprawdzanie, kto zalega z odaniem filmów 
$sql = "SELECT * FROM `wypozyczenia`, `uzytkownicy`, `filmy` WHERE uzytkownik_id=uzytkownicy.id and film_id=filmy.id and data_zwrotu <= CURRENT_DATE and zwrot = 0;";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$zaleglosci = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel admina</title>
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
                    <a class="admin-link" href="users.php">Użytkownicy</a><br>
                </li>
                <li>
                    <a class="admin-link" href="films.php">Filmy</a><br>
                </li>
                <li>
                <?php
                    //Sprawdza czy jest zalogowany jeśli jest to:
                    if (isset($_SESSION['admin'])) {
                        // Użytkownik jest zalogowany
                        echo '<a class="admin-link" href="../logout.php">WYLOGUJ</a>'; // Przycisk wylogowania
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
<h1 class="section-heading heading-admin section-zalegajace">Witaj w panelu admina,  <?php echo htmlspecialchars($dane['nazwa']); ?> </h1>
<?php
if ($result->num_rows > 0) {
    echo '<div class="zalegle-box">';
    echo '<h2 class="section-heading heading-admin">Zaległości w zwrocie filmów</h2>';
    echo '</div>';
    echo '<div class="container-zalegajacy">';
    foreach ($zaleglosci as $zalega) {
        echo "<div class='zalegajacy'>";
        echo "<h3>Użytkownik: " . htmlspecialchars($zalega['nazwa']) . "</h3>";
        echo "<p><strong>E-mail:</strong> " . htmlspecialchars($zalega['email']) . "</p>";
        echo "<p><strong>Data wypożyczenia:</strong> " . htmlspecialchars($zalega['data_wypozyczenia']) . "</p>";
        echo "<p><strong>Film:</strong> " . htmlspecialchars($zalega['tytul']) . "</p>";
        echo "<p><strong>Data zwrotu:</strong> " . htmlspecialchars($zalega['data_zwrotu']) . "</p>";
        echo "</div>";
    }
    echo '</div>';
} else {
    echo '<h2 class="section-heading heading-admin zalegle">Brak zaległości w zwrocie filmów</h2>';
}
?>
</div>
    <script src="../js/index.js"></script>
</body>
</html>