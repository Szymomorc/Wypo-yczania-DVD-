<?php 
include("config.php"); 
session_start(); 
header('Content-Type: text/html; charset=utf-8');
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;


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
    <link rel="stylesheet" href="./css/wyniki.css">
</head>
<body class="main-page">

    <nav>
        <div class="wrapper nav-wrapper">
            <div class="nav-leftside">
                <a href="./index.php"><img src="./img/DVD.jpg" alt="" class="nav_logo"></a>
                <div class="nav-search">
                    <form action="wyniki.php" method="GET">
                        <div class="nav-search-bar">
                            <input type="text" placeholder="Co chciałbyś obejrzeć..." name="q" 
                                value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <button type="submit">
                                <img src="./img/icons8-search-50.png" alt="">
                                <button type="button" id="resetFilters"><img src="./img/Reset.png" alt=""> </button>
                            </button>
                        </div>
                        <div class="search-filters">
                            <select name="rezyser">
                                <option value="">Wybierz reżysera</option>
                                <?php
                                $query = "SELECT DISTINCT rezyser FROM filmy ORDER BY rezyser";
                                $result = mysqli_query($mysqli, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = (isset($_GET['rezyser']) && $_GET['rezyser'] == $row['rezyser']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['rezyser']) . '" ' . $selected . '>' . htmlspecialchars($row['rezyser']) . '</option>';
                                }
                                ?>
                            </select>
                            <select name="gatunek">
                                <option value="">Wybierz gatunek</option>
                                <?php
                                $query = "SELECT DISTINCT gatunek FROM filmy ORDER BY gatunek";
                                $result = mysqli_query($mysqli, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = (isset($_GET['gatunek']) && $_GET['gatunek'] == $row['gatunek']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['gatunek']) . '" ' . $selected . '>' . htmlspecialchars($row['gatunek']) . '</option>';
                                }
                                ?>
                            </select>
                            <input type="number" name="rok_premiery" placeholder="Rok premiery" 
                                value="<?php echo isset($_GET['rok_premiery']) ? htmlspecialchars($_GET['rok_premiery']) : ''; ?>">
                        </div>
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
    <div class="new" id="offer">
                <?php
                    $q = isset($_GET['q']) ? mysqli_real_escape_string($mysqli, $_GET['q']) : '';
                    $rezyser = isset($_GET['rezyser']) ? mysqli_real_escape_string($mysqli, $_GET['rezyser']) : '';
                    $rok_premiery = isset($_GET['rok_premiery']) ? mysqli_real_escape_string($mysqli, $_GET['rok_premiery']) : '';
                    $gatunek = isset($_GET['gatunek']) ? mysqli_real_escape_string($mysqli, $_GET['gatunek']) : '';
                ?>
                <h2 class="new__heading section-heading2">
                    WYNIKI WYSZUKIWANIA DLA: 
                    <?php
                    // Tworzymy zmienną, która będzie przechowywać wszystkie elementy do wyświetlenia
                    $search_terms = [];

                    // Sprawdzamy, czy tytuł jest wprowadzony
                    if (!empty($q)) {
                        $search_terms[] = htmlspecialchars($q);
                    }

                    // Sprawdzamy, czy reżyser jest wprowadzony
                    if (!empty($rezyser)) {
                        $search_terms[] = htmlspecialchars($rezyser);
                    }

                    // Sprawdzamy, czy rok premiery jest wprowadzony
                    if (!empty($rok_premiery)) {
                        $search_terms[] = htmlspecialchars($rok_premiery);
                    }

                    // Sprawdzamy, czy gatunek jest wprowadzony
                    if (!empty($gatunek)) {
                        $search_terms[] = htmlspecialchars($gatunek);
                    }

                    // Łączymy wszystkie elementy w jeden ciąg, oddzielając je przecinkami
                    if (count($search_terms) > 0) {
                        echo implode(', ', $search_terms);
                    } else {
                        echo "WSZYSTKIE FILMY"; // Jeśli nic nie jest wprowadzone, wyświetlamy "WSZYSTKIE FILMY"
                    }
                    ?>
                </h2>

                <div class="carousel-container">
                    <button class="carousel-button left-button">&#10094;</button>
                    <div class="carousel-wrapper">
                        <?php
                            $query = "SELECT * FROM `filmy` WHERE 1"; 

                            if (!empty($q)) {
                                $query .= " AND `tytul` LIKE '%$q%'";
                            }
                            if (!empty($rezyser)) {
                                $query .= " AND `rezyser` LIKE '$rezyser'";
                            }

                            if (!empty($rok_premiery)) {
                                $query .= " AND `rok_premiery` = '$rok_premiery%'";
                            }

                            if (!empty($gatunek)) {
                                $query .= " AND `gatunek` = '$gatunek'";
                            }
                            $query .= " GROUP BY `tytul`";

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
                                    echo '<p class="carousel-item-gatunek">' . htmlspecialchars($film['gatunek']) .'</p>';
                                    echo '<p class="carousel-item-rok">' . htmlspecialchars($film['rok_premiery']) .'</p>';
                                    if (isset($_SESSION['user'])) {
                                        // Użytkownik jest zalogowany
                                        echo '<button onclick="wypozyczFilm(' . htmlspecialchars($film['id']) . ')" class="carousel-item-btn">WYPOŻYCZ</button>'; // Przycisk wypożycz
                                    }
                                    echo '</div>';
                                }
                            } else {
                                echo '<h2 class="new__heading section-heading3">Brak wyników do wyświetlenia</h2>';
                            }
                            
                        ?>
                    </div>
                    <button class="carousel-button right-button">&#10095;</button>
                </div>
    </div>
    
    <section class="spacing"></section>
    <footer class="footer">
        <div class="wrapper"><img src="./img/DVD.jpg" alt="">
            <p class="footer__bottom-text">&copy; <span class="footer__year"></span> DVD RENTAL STORE</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/reset-filters.js"></script>
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
    <script> //Popup po zalogowaniu
        function logoutUser() {
            localStorage.removeItem('zalegleModalSeen');
            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 100);
        }
    </script>
</body>
</body>

</html>