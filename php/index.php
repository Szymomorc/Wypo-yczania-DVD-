<?php 
include("config.php"); // Połączenie z bazą danych 
session_start(); // Rozpoczęcie sesji 
$user = $_SESSION['user'];

$query = "SELECT COUNT(*) as zalegle 
          FROM wypozyczenia 
          JOIN uzytkownicy ON wypozyczenia.uzytkownik_id = uzytkownicy.id 
          WHERE uzytkownicy.email = ? 
            AND wypozyczenia.zwrot = 0 
            AND wypozyczenia.data_zwrotu < CURDATE()";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$maZalegle = $data['zalegle'] > 0;
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
    <?php if ($maZalegle): ?>
        <!-- Duży modal na środek -->
        <div id="zalegleModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Uwaga!</h2>
            <p>Masz zaległe filmy do zwrotu. Sprawdź swoje wypożyczenia!</p>
        </div>
        </div>

        <!-- Mały powiadomieniowy box w rogu -->
        <div id="zaleglePopup" class="popup hidden">
        <span class="popup-close">&times;</span>
        <p>Masz zaległe filmy!</p>
        <a href="panel.php">Zobacz</a>
        </div>
    <?php endif; ?>

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

        <div class="wrapper header__wrapper">

            <div class="header__heading">
                <h2 class="header__heading-name">Odkryj magię kina z</h2>
                <h1 class="header__heading-main">Wypożyczalnią DVD</h1>
                <div class="header__heading-line"></div>
                <div class="header__heading-description">
                    <p>Nasza wypożyczalnia DVD to miejsce, gdzie pasja do kina spotyka się z wygodą i szerokim wyborem
                        tytułów.</p>
                </div>
            </div>
        </div>


    </header>
    
    <section class="new" id="offer">
        <h2 class="new__heading section-heading">OSTATNIO DODANE FILMY</h2>
        <div class="carousel-container">
            <button class="carousel-button left-button">&#10094;</button>
            <div class="carousel-wrapper">
                <?php
                
                $query = "SELECT * FROM `filmy` ORDER by data_dodania DESC";
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
                            echo '<button onclick="wypozyczFilm(' . htmlspecialchars($film['id']) . ')" class="carousel-item-btn">WYPOŻYCZ</button>'; // Przycisk wyporzyć
                        }
                        echo '</div>';
                    }
                }else{
                    echo "<script>alert('$alert'); window.location.href='index.php';</script>";
                }
                ?>
            </div>
            <button class="carousel-button right-button">&#10095;</button>
        </div>
    </section>

    <section class="new2">
        <h2 class="new2__heading section-heading">NAJCZĘŚCIEJ WYPOŻYCZANE FILMY</h2>
        <div class="carousel-container">
            <button class="carousel-button left-button2">&#10094;</button>
            <div class="carousel-wrapper2">
                <?php
                // Wykonanie zapytania w bazie danych
                $query = "SELECT filmy.*, COUNT(wypozyczenia.film_id) AS wypozyczenia_count FROM filmy LEFT JOIN wypozyczenia ON filmy.id = wypozyczenia.film_id GROUP BY filmy.id ORDER BY wypozyczenia_count DESC";
                $result = mysqli_query($mysqli, $query);

                // Jeśli wynik jest większy od 0 to:
                if(mysqli_num_rows($result) > 0){
                    while ($film = mysqli_fetch_assoc($result)){
                        echo '<div class="carousel-item">';
                        // pokazuje zdjęcie jeśli jest
                        // jak nie ma pokazuje tytul
                        if(!empty($film['okladka'])){
                            echo '<a href="film.php?id='. htmlspecialchars($film['id']).'">';
                            echo '<img src="'. htmlspecialchars($film['okladka']) .'" alt="'. htmlspecialchars($film['tytul']) .'" class="carousel-item-img">';
                            echo '</a>';
                        }
                        echo '<p class="carousel-item-title">'. htmlspecialchars($film['tytul']).'</p>';
                        echo '<p class="carousel-item-director">' . htmlspecialchars($film['rezyser']) .'</p>';
                        if (isset($_SESSION['user'])) {
                            // Użytkownik jest zalogowany
                            echo '<button onclick="wypozyczFilm(' . htmlspecialchars($film['id']) . ')" class="carousel-item-btn">WYPOŻYCZ</button>'; // Przycisk wyporzyć
                        }
                        echo '</div>';
                    }
                }else{
                    echo "<script>alert('$alert'); window.location.href='index.php';</script>";
                }
                ?>
            </div>
            <button class="carousel-button right-button2">&#10095;</button>
        </div>
    </section>

    <section class="contact" id="contact">

        <div class="wrapper">
            <div class="contact__box">

                <div class="contact__box-info">
                    <h2 class="contact__box-heading section-heading">Skontaktuj się z nami!</h2>
                    <p class="contact__box-info-description"> Masz jakieś pytania lub coś nie jest jasne? Napisz do nas
                        wiadomość lub skontaktuj się telefonicznie pod numerem <a
                            href="tel:+48123123123" class="contact__box-info-description--number">123-123-123</a>.</p>

                    <div class="contact__box-info-media">
                        <a href="#" class="contact__box-info-media-icon"><i class="fa-brands fa-square-github"></i></a>
                        <a href="#" class="contact__box-info-media-icon"><i
                                class="fa-brands fa-square-facebook"></i></a>
                        <a href="#" class="contact__box-info-media-icon"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>

                <div class="contact__box-line"></div>

                <img src="./img/iPhone-14-Pro-Mockup-Space-Black.png" alt="" class="contact__box-img">


            </div>

        </div>
    </section>

    <footer class="footer">
        <div class="wrapper"><img src="./img/DVD.jpg" alt="">
            <p class="footer__bottom-text">&copy; <span class="footer__year"></span> DVD RENTAL STORE</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="./js/index.js"></script>
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
            alert(data); // Wyświetlenie odpowiedzi z serwera
        })
        .catch(error => {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas wypożyczania filmu.');
        });
    }
    </script>
              
   <script>
        document.addEventListener("DOMContentLoaded", function () {
        const hasSeen = localStorage.getItem('zalegleModalSeen');
        const modal = document.getElementById('zalegleModal');
        const popup = document.getElementById('zaleglePopup');

        if (!hasSeen && modal) {
            modal.classList.remove('hidden');

            modal.querySelector(".close-btn").addEventListener("click", () => {
            modal.classList.add('hidden');
            localStorage.setItem('zalegleModalSeen', 'true');
            });
        } else if (popup) {
            popup.classList.remove('hidden');

            popup.querySelector(".popup-close").addEventListener("click", () => {
            popup.classList.add('hidden');
            // Nie zapisujemy nic do localStorage – pojawi się znów przy kolejnym wejściu
            });
        }
        });
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