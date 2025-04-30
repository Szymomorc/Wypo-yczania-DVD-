<?php
include("config.php");
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}

$film_id = $_GET['id'];

// Pobieranie danych filmu
$query = "SELECT filmy.*, COUNT(wypozyczenia.id) AS liczba_wypozyczen
          FROM filmy
          LEFT JOIN wypozyczenia ON filmy.id = wypozyczenia.film_id
          WHERE filmy.id = ?
          GROUP BY filmy.id";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    $alert = "Nie znaleziono filmu";
    echo "<script>alert('$alert'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['tytul']); ?></title>
    <link rel="stylesheet" href="./css/film.css">
</head>
<body>
<nav>
        <div class="wrapper nav-wrapper">
            <div class="nav-leftside">
                <a href="./index.php"><img src="./img/DVD.jpg" alt="" class="nav_logo"></a>
                <div class="nav-search">
                    <form action="wyniki.php" method="GET" class="nav-search-bar">
                        <input type="text" placeholder="Co chciałbyś obejrzeć..." name="q">
                        <button type="submit"><img src="./img/icons8-search-50.png" alt=""></button>
                        <button type="submit"><img src="./img/filter-32.png" alt=""></button>
                    </form>
                </div>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="panel.php">Panel</a>
                </li>
                <li>
                <?php
                    //Sprawdza czy jest zalogowany jeśli jest to:
                    if (isset($_SESSION['user'])) {
                        // Użytkownik jest zalogowany
                        echo '<a href="#" onclick="logoutUser()" >WYLOGUJ</a>'; // Przycisk wylogowania
                    } else {
                        // Użytkownik nie jest zalogowany
                        echo '<a href="zaloguj.html">LOGOWANIE</a>'; // Przycisk logowania
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
<div class="wrapper-film">
    <div class="box__film">
        <h1><?php echo htmlspecialchars($film['tytul']); ?></h1>
    <p>Gatunek: <?php echo htmlspecialchars($film['gatunek']); ?></p>
    <p>Reżyser: <?php echo htmlspecialchars($film['rezyser']); ?></p>
    <p>Rok premiery: <?php echo htmlspecialchars($film['rok_premiery']); ?></p>
    <p>Data dodania: <?php echo htmlspecialchars($film['data_dodania']); ?></p>
    <p>Ilość wypożyczeń: <?php echo htmlspecialchars($film['liczba_wypozyczen']); ?></p>
    <?php if (!empty($film['okladka'])): ?>
    <img src="<?php echo htmlspecialchars($film['okladka']); ?>" alt="<?php echo htmlspecialchars($film['tytul']); ?>">
    <?php endif; ?>
    <?php if (isset($_SESSION['user'])): ?>
    <button onclick="wypozyczFilm('<?php echo htmlspecialchars($film['id']); ?>')" class="carousel-item-btn">WYPOŻYCZ</button>
    <?php endif; ?>
    </div>
</div>
<div class="box__btn">
     <a class="carousel-item-btn box__film-btn" href="index.php">Powrót do strony głównej</a>
</div>
<script>
    function wypozyczFilm(filmID) {
        fetch('wyporzycz.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'film_id=' + encodeURIComponent(filmID)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas wypożyczania filmu.');
        });
    }
</script>
    <script> //Popup po zalogowaniu
        function logoutUser() {
            localStorage.removeItem('zalegleModalSeen');
            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 100);
        }
    </script>
</body>
</html>