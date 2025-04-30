<?php
include("../config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
if(!isset($_SESSION['admin'])) {
    header("Location: ../admin.php"); // Przekierowanie na stronę główną
    exit();
}
//Sprawdzmy, czy formularz został wysłany
if($_SERVER['REQUEST_METHOD'] === "POST"){
    $tytul = $_POST['tytul'];
    $rezyser = $_POST['rezyser'];
    $rok_premiery = $_POST['rok_premiery'];
    $gatunek = $_POST['gatunek'];
    if(empty($tytul || $rezyser || $rok_premiery || $gatunek)){
        $alert = "Wypełnij wszystkie pola";
    }elseif(!is_numeric($rok_premiery) || strlen($rok_premiery) != 4){
        $alert = "Rok premiery musi być liczbą czterocyfrową";
    }else{
        $query_check = "SELECT * FROM `filmy` WHERE tytul = ? AND rezyser = ? AND rok_premiery = ? AND gatunek = ?";
        $stmt_check = $mysqli->prepare($query_check);
        $stmt_check->bind_param("ssis", $tytul, $rezyser, $rok_premiery, $gatunek);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if($result_check->num_rows > 0){
            $alert = "Film o podanych danych już istnieje";
        } else{
            $upload_dir = "../img/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
        }
            if(!empty($_FILES['okladka']['name'])){
            $okladka = $_FILES['okladka'];
            $okladka_name = $okladka['name'];
            $okladka_tmp = $okladka['tmp_name'];
            $okladka_size = $okladka['size'];
            $okladka_error = $okladka['error'];
            $okladka_ext = explode('.', $okladka_name);
            $okladka_ext = strtolower(end($okladka_ext));
            $allowed = ['jpg', 'jpeg', 'png'];
            if(in_array($okladka_ext, $allowed)){
                if($okladka_error === 0){
                    if($okladka_size <= 8000000){
                        $okladka_name_new = uniqid('', true) . '.' . $okladka_ext;
                        $okladka_destination = $upload_dir . $okladka_name_new;
                        if(move_uploaded_file($okladka_tmp, $okladka_destination)){
                            $okladka_url = 'http://dvdrental.online/img/' . $okladka_name_new;
                            $query = "INSERT INTO `filmy` (tytul, rezyser, rok_premiery, gatunek, okladka) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param("ssiss", $tytul, $rezyser, $rok_premiery, $gatunek, $okladka_url);
                            if($stmt->execute()){
                                echo "<script>alert('Film został pomyślnie dodany'); window.location.href='add_film.php';</script>";
                            }else{
                                $alert = "Błąd podczas dodawania filmu";
                            }
                        }else{
                            $alert = "Błąd podczas przesyłania okładki";
                        }
                    }else{
                        $alert = "Okładka jest za duża (max 8MB)";
                    }
                }else{
                    $alert = "Błąd podczas przesyłania okładki";
                }
            }else{
                $alert = "Nieprawidłowy format okładki (dozwolone: jpg, jpeg, png)";
            }
        
        }
    }
}
// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='add_film.php';</script>";
}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj film</title>
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
                        echo '<a class="admin-link" href="../logout.php">Wyloguj</a>'; // Przycisk wylogowania
                    } else {
                        // Użytkownik nie jest zalogowany
                        echo '<a href="admin.php">LOGOWANIE</a>'; // Przycisk logowania
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

    <div class="container-add">
    <a class="carousel-item-btn box__film-btn" href="films.php">Powrót do filmów</a>
    <form class="addfilm" method="POST" action="add_film.php" enctype="multipart/form-data">
        <h1>Dodaj film</h1>
        <div class="addfilm">
            <input type="text" name="tytul" placeholder="Tytuł" required>
            <input type="text" name="rezyser" placeholder="Reżyser" required>
            <input type="number" name="rok_premiery" placeholder="Rok premiery" required>
            <input type="text" name="gatunek" placeholder="Gatunek" required>
            <input type="file" name="okladka" accept="image/*" required>
            <button type="submit">Dodaj film</button>
        </div>
    </form>
    </div>
    <script src="../js/index.js"></script>
</body>
</html>