<?php 
include("config.php"); // Połączenie z bazą danych 
session_start(); // Rozpoczęcie sesji 

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Przygotowanie zapytania, jeśli użytkownik jest zalogowany
if ($user) {
    $sql = "SELECT nazwa FROM `uzytkownicy` WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $dane = $result->fetch_assoc();
}
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
    <link rel="stylesheet" href="./css/main.css">
</head>
<body class="main-page">

    <nav>
        <div class="wrapper nav-wrapper">
            <div class="nav-leftside">
                <img src="./img/DVD.jpg" alt="" class="nav_logo">
                <div class="nav-search">
                    <form action="wyniki.php" method="GET" class="nav-search-bar">
                        <input type="text" placeholder="Co chciałbyś obejrzeć..." name="q" required>
                        <button type="submit"><img src="./img/icons8-search-50.png" alt=""></button>
                        <button id="filterButton"><img src="./img/filter-32.png" alt=""> </button>
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
                        echo '<a href="logout.php" >WYLOGUJ</a>'; // Przycisk wylogowania
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
        <section class="new" id="offer">
            <?php
                // Pobieramy zapytanie z formularza na początku
                $q = isset($_GET['q']) ? mysqli_real_escape_string($mysqli, $_GET['q']) : '';
            ?>

            <h2 class="new__heading section-heading2">
                WYNIKI WYSZUKIWANIA DLA: 
                <?php
                // Wyświetlamy zapytanie tylko, jeśli nie jest puste
                if (!empty($q)) {
                    echo htmlspecialchars($q);
                } else {
                    echo "WSZYSTKIE FILMY ";
                }
                ?>
            </h2>
            <div class="carousel-container">
                <button class="carousel-button left-button">&#10094;</button>
                <div class="carousel-wrapper">
                    <?php
                    if(!empty($q)){
                        $query = "SELECT * FROM `filmy` WHERE `tytul` LIKE '%$q%' GROUP BY `tytul`";
                        $result = mysqli_query($mysqli, $query);
                        if(mysqli_num_rows($result) > 0){
                            while ($film = mysqli_fetch_assoc($result)){
                                echo '<div class="carousel-item">';
                                if(!empty($film['okladka'])){
                                    echo '<a href="film.php?id='. htmlspecialchars($film['id']).'">';
                                    echo '<img src="'. htmlspecialchars($film['okladka']) .'" alt="'. htmlspecialchars($film['tytul']) .'" class="carousel-item-img">';
                                    echo '</a>';
                                }
                                echo '<p class="carousel-item-title">'. htmlspecialchars($film['tytul']).'</p>';
                                echo '<p class="carousel-item-director">' . htmlspecialchars($film['rezyser']) .'</p>';
                                if (isset($_SESSION['user'])) {
                                    // Użytkownik jest zalogowany
                                    echo '<button class="carousel-item-btn">WYPOŻYCZ</button>'; // Przycisk wypożycz
                                }
                                echo '</div>';
                            }
                        } else {
                            echo '<h2 class="new__heading section-heading3">Brak wyników do wyświetlenia</h2>';
                        }
                    } else {
                            $query = "SELECT * FROM `filmy`";
                            $result = mysqli_query($mysqli, $query);
                            if(mysqli_num_rows($result) > 0){
                                while ($film = mysqli_fetch_assoc($result)){
                                    echo '<div class="carousel-item">';
                                    if(!empty($film['okladka'])){
                                        echo '<a href="film.php?id='. htmlspecialchars($film['id']).'">';
                                        echo '<img src="'. htmlspecialchars($film['okladka']) .'" alt="'. htmlspecialchars($film['tytul']) .'" class="carousel-item-img">';
                                        echo '</a>';
                                    }
                                    echo '<p class="carousel-item-title">'. htmlspecialchars($film['tytul']).'</p>';
                                    echo '<p class="carousel-item-director">' . htmlspecialchars($film['rezyser']) .'</p>';
                                    if (isset($_SESSION['user'])) {
                                        // Użytkownik jest zalogowany
                                        echo '<button class="carousel-item-btn">WYPOŻYCZ</button>'; // Przycisk wypożycz
                                    }
                                    echo '</div>';
                                }
                        }
                    }
                    ?>
                </div>
                <button class="carousel-button right-button">&#10095;</button>
            </div>
        </section>
    </header>
    <section class="spacing"></section>
    <footer class="footer">
        <div class="wrapper"><img src="./img/DVD.jpg" alt="">
            <p class="footer__bottom-text">&copy; <span class="footer__year"></span> DVD RENTAL STORE</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="./js/index.js"></script>

</body>
</body>

</html>