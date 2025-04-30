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
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="panel.php">Panel</a>
                </li>
                <li>
                <?php
                    //Sprawdza czy jest zalogowany jeśli jest to:
                    if (isset($_SESSION['user'])) {
                        // Użytkownik jest zalogowany
                        echo '<a <a href="#" onclick="logoutUser()">WYLOGUJ</a>'; // Przycisk wylogowania
                    } else {
                        // Użytkownik nie jest zalogowany
                        echo '<a href="zaloguj.html">LOGOWANIE</a>'; // Przycisk logowania
                    }
                    ?>
                </li>
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
                <a href="#" onclick="logoutUser()" class="panel__box-sidebar-option">Wyloguj</a>
                <a href="./delete.php" class="panel__box-sidebar-option">Usuń konto</a>
            </div>
            <div class="carousel-container">
    <div class="carousel-header ">
        <h1 class="section-heading" style="color: white">Moje wypożyczone filmy:</h1>
        <?php
        $query = 'SELECT filmy.*, wypozyczenia.data_zwrotu 
                  FROM wypozyczenia 
                  JOIN filmy ON wypozyczenia.film_id = filmy.id 
                  WHERE wypozyczenia.uzytkownik_id = (SELECT id FROM uzytkownicy WHERE email = ?) 
                  AND wypozyczenia.zwrot = 0
                  ORDER BY wypozyczenia.data_zwrotu < CURDATE() DESC, wypozyczenia.data_zwrotu ASC;';

        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, 's', $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            echo '<div class="container-zalegajacy" style="display: flex; flex-wrap: wrap; gap: 1rem;">';
            while ($row = mysqli_fetch_assoc($result)) {
                $czyPrzeterminowany = strtotime($row['data_zwrotu']) < time();
                $kolor = $czyPrzeterminowany ? ' style="color:red;"' : '';

                echo '<div class="carousel-item">';
                if (!empty($row['okladka'])) {
                    echo '<a href="film.php?id=' . htmlspecialchars($row['id']) . '">';
                    echo '<img src="' . htmlspecialchars($row['okladka']) . '" alt="' . htmlspecialchars($row['tytul']) . '" class="carousel-item-img">';
                    echo '</a>';
                }
                echo '<p class="carousel-item-title"' . $kolor . '>' . htmlspecialchars($row['tytul']) . '</p>';
                if ($czyPrzeterminowany) {
                    echo '<p class="carousel-item-date" style="color:red;">Ten film miał być zwrócony do: ' . htmlspecialchars($row['data_zwrotu']) . '</p>';
                } else {
                    echo '<p class="carousel-item-date">Oczekiwana data zwrotu: ' . htmlspecialchars($row['data_zwrotu']) . '</p>';
                }
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p style="color:white;">Brak aktywnych wypożyczeń.</p>';
        }
        ?>
    </div>

    <div class="carousel-header">
        <h1 class="section-heading" style="color: white">Dawniej wypożyczone filmy:</h1>
        <?php
        $query = 'SELECT filmy.* 
                  FROM wypozyczenia 
                  JOIN filmy ON wypozyczenia.film_id = filmy.id 
                  WHERE wypozyczenia.uzytkownik_id = (SELECT id FROM uzytkownicy WHERE email = ?) 
                  AND wypozyczenia.zwrot = 1;';
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, 's', $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            echo '<div class="container-zalegajacy" style="display: flex; flex-wrap: wrap; gap: 1rem;">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="carousel-item">';
                if (!empty($row['okladka'])) {
                    echo '<a href="film.php?id=' . htmlspecialchars($row['id']) . '">';
                    echo '<img src="' . htmlspecialchars($row['okladka']) . '" alt="' . htmlspecialchars($row['tytul']) . '" class="carousel-item-img">';
                    echo '</a>';
                }
                echo '<p class="carousel-item-title">' . htmlspecialchars($row['tytul']) . '</p>';
                echo '<p class="carousel-item-director">Reżyser: ' . htmlspecialchars($row['rezyser']) . '</p>';
                echo '<p class="carousel-item-year">Rok: ' . htmlspecialchars($row['rok_premiery']) . '</p>';
                echo '<p class="carousel-item-genre">Gatunek: ' . htmlspecialchars($row['gatunek']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p style="color:white;">Brak wcześniejszych wypożyczeń.</p>';
        }
        ?>
    </div>
</div>

    </section>
    
    <footer class="footer panel-footer">
        <div class="wrapper"><img src="./img/DVD.jpg" alt="">
            <p class="footer__bottom-text">&copy; <span class="footer__year"></span> DVD RENTAL STORE</p>
        </div>
    </footer>

    <script>
        function logoutUser() {
            localStorage.removeItem('zalegleModalSeen');
            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 100);
        }
    </script>
</body>
</html>