
<!DOCTYPE html>
<html lang="pl">
<?php 
//wazne jest tylko część kodu z php
include("config.php"); // Połączenie z bazą danych 
session_start(); // Rozpoczęcie sesji 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>

        <nav class="nav">
            <div class="nav-container">
                <div class="nav_left">
                    <img src="./DVD 2.jpg" alt="Logo strony" class="nav_left-logo">

                    <div class="nav_left-search">
                        <form action="szukaj.php" method="POST" class="nav_left-search-bar">
                            <input type="text" placeholder="Co chciałbyś obejrzeć..." name="q">
                            <button type="submit"><img src="./icons8-search-50 (1).png" alt=""></button>
                        </form>
                    </div>

                </div>
                <div class="nav_right">
                    <div class="nav_right-btns">
                        <a href="" class="nav_right-btns-btn">Strona główna</a>
                        <a href="zmiana_nazwy.php" class="nav_right-btns-btn">Kokpit</a>
                        <?php
                        //Sprawdza czy jest zalogowany jeśli jest to:
                        if (isset($_SESSION['user'])) {
                            // Użytkownik jest zalogowany
                            echo '<a href="logout.php" class="nav_right-btns-btn nav_right-btns-login">WYLOGUJ</a>'; // Przycisk wylogowania
                        } else {
                            // Użytkownik nie jest zalogowany
                            echo '<a href="zaloguj.html" class="nav_right-btns-btn nav_right-btns-login">LOGOWANIE</a>'; // Przycisk logowania
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>

    <header class="header">
        <div class="header_box">
            <div class="header_box-left">
                <h1>Odkryj magię kina z naszą wypożyczalnią DVD!</h1>
                <p>Szukasz wyjątkowego filmu na wieczór?</p>
                <p>Nasza wypożyczalnia DVD to miejsce, gdzie pasja do kina spotyka się z wygodą i szerokim wyborem
                    tytułów.
                </p>
                <p>Oferujemy setki filmów – od klasyków światowego kina, przez najnowsze hity, aż po rzadkie perełki dla
                    prawdziwych koneserów.</p>
                    <button class="header_box-left-btn">PANEL</button>
            </div>
            <div class="header_box-right"></div>
        </div>
    </header>

    <section class="new">
        <div class="new_container">
            <div class="new_container-textbox">
                <h3>OSTATNIO DODANE FILMY</h3>
            </div>
            <div class="new_container-films">
                <?php
                    //Wykonanie zapytania w bazie danych
                    $query = "SELECT * FROM `filmy` ORDER by data_dodania DESC LIMIT 4";
                    $result = mysqli_query($mysqli, $query);
                    //Jeśli wynik jest większy od 0 to:
                    if(mysqli_num_rows($result) > 0){
                        while ($film = mysqli_fetch_assoc($result)){
                            echo '<div class="new_container-films-box">';
                                echo '<div class="new_container-films-box-img">';
                                //pokazuje zdjęcie jeśli jest
                                // jak nie ma pokazuje tytul
                                if(!empty($film['okladka'])){
                                    echo '<img src="'. htmlspecialchars($film['okladka']).'" alt="'.htmlspecialchars($film['tytul']).'" class="nav_left-logo">';
                                }
                                echo '</div>';
                                echo '<p class="new_container-films-box-title">'. htmlspecialchars($film['tytul']).'</p>';
                                echo '<p class="new_container-films-box-director">' . htmlspecialchars($film['rezyser']) .'</p>';
                                if (isset($_SESSION['user'])) {
                                    // Użytkownik jest zalogowany
                                    // Przycisk brak
                                } else {
                                // Użytkownik nie jest zalogowany
                                echo '<button class="new_container-films-box-btn">WYPOŻYCZ</button>'; // Przycisk wyporzyć
                                }
                            echo '</div>';
                        }
                    }else{
                        echo "Brak filmów w bazie.";
                    }
                ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer_up">
            <div class="footer_left">
                <a href=""><img src="./DVD.jpg" alt="logo firmy" class="footer_left-logo"></a>
                <p class="footer_left-text">DVD RENTAL <br> Store</p>
            </div>
            <div class="footer_right">
                <div class="footer_right-btns">
                    <a href="" class="footer_right-btns-btn">Strona główna</a>
                    <a href="" class="footer_right-btns-btn">Kokpit</a>
                </div>
                <div class="footer_right-phone">
                    <img src="./icons8-phone-50.png" alt="zadzwoń" class="footer_right-phone-img">
                    <p class="footer_right-zadzwon">Zadzwoń do Nas:<br><a href="tel:517115353"
                            class="footer_right-phone-tel">517 115 353</a></p>
                </div>
            </div>
        </div>
        <div class="footer_autors">
            <p class="footer_autors-text">autorzy</p>
        </div>
    </footer>
</body>

</html>