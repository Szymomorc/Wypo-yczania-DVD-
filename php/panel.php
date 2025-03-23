<?php 
include("config.php"); // Połączenie z bazą danych 
session_start(); // Rozpoczęcie sesji 

// Sprawdzanie sesji
if (!isset($_SESSION['user'])) {
    header("Location: zaloguj.html");
    exit(); // Upewnij się, że używasz exit() po header
}

$user = $_SESSION['user'];

// Przygotowanie zapytania
$sql = "SELECT nazwa FROM `uzytkownicy` WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$dane = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVD Rental</title>
    <link rel="stylesheet" href="./css/main.css">
</head>
<body class="main-page">
    <nav>
        <div class="wrapper nav-wrapper">
            <div class="nav-leftside">
                <img src="./img/DVD.jpg" alt="" class="nav_logo">
                <div class="nav-search">
                   <form action="wyniki.php" method="GET" class="nav-search-bar">
                        <input type="text" placeholder="Co chciałbyś obejrzeć..." name="q">
                        <button type="submit"><img src="./img/icons8-search-50.png" alt=""></button>
                        <button type="submit"><img src="./img/filter-32.png" alt=""></button>
                    </form>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Panel</a></li>
            </ul>
        </div>
    </nav>
    <header class="header panel-header" id="header">
    <div class="header-bg container">
            <div class="container">
                <div class="container__item container__item-one"></div>
                <div class="container__item container__item-two"></div>
                <div class="container__item container__item-three"></div>
            </div>
        </div>
    </header>
    <section class="panel" id="panel">
        <div class="panel__box">
            <div class="panel__box-sidebar">
                <div class='panel__box-sidebar-nick'>Witaj, <?php echo htmlspecialchars($dane['nazwa']); ?></div>
                <a href="./zmiana_email.php" class="panel__box-sidebar-option">Zmiana maila</a>
                <a href="./zmiana_hasla.php" class="panel__box-sidebar-option">Zmiana hasła</a>
                <a href="./zmiana_nazwy.php" class="panel__box-sidebar-option">Zmiana nazwy</a>
                <a href="./logout.php" class="panel__box-sidebar-option">Wyloguj</a>
                <a href="./delete.php" class="panel__box-sidebar-option">Usuń konto</a>
            </div>
            <div class="panel__box-main">
                Moje wypożyczone filmy:
                <br><br>
                <br><br>
                Dawniej wypożyczone filmy:
            </div>
        </div>
    </section>
    <footer class="footer panel-footer">
        <div class="wrapper"><img src="./img/DVD.jpg" alt="">
            <p class="footer__bottom-text">&copy; <span class="footer__year"></span> DVD RENTAL STORE</p>
        </div>
    </footer>
</body>
</html>
