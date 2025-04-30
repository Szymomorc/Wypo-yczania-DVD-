<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: films.php");
    exit();
}

$film_id = $_GET['id'];

// Pobieranie danych filmu
$query = "SELECT * FROM `filmy` WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    echo "<script>alert('Nie znaleziono filmu'); window.location.href='films.php';</script>";
    exit();
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tytul = $_POST['tytul'];
    $rezyser = $_POST['rezyser'];
    $rok_premiery = $_POST['rok_premiery'];
    $gatunek = $_POST['gatunek'];

    if (empty($tytul) || empty($rezyser) || empty($rok_premiery) || empty($gatunek)) {
        echo "<script>alert('Wypełnij wszystkie pola');</script>";
    } elseif (!is_numeric($rok_premiery) || strlen($rok_premiery) != 4) {
        echo "<script>alert('Rok premiery musi być liczbą czterocyfrową');</script>";
    } elseif (!empty($_FILES['okladka']['name'])) {
        $okladka = $_FILES['okladka'];
        $okladka_name = $okladka['name'];
        $okladka_tmp = $okladka['tmp_name'];
        $okladka_size = $okladka['size'];
        $okladka_error = $okladka['error'];
        $okladka_ext = strtolower(pathinfo($okladka_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($okladka_ext, $allowed)) {
            if ($okladka_error === 0) {
                if ($okladka_size <= 8000000) {
                    $okladka_name_new = uniqid('', true) . '.' . $okladka_ext;
                    $okladka_destination = '../img/' . $okladka_name_new;
                    if (move_uploaded_file($okladka_tmp, $okladka_destination)) {
                        $okladka_url = 'http://dvdrental.online/img/' . $okladka_name_new;
                        $query = "UPDATE `filmy` SET tytul = ?, rezyser = ?, rok_premiery = ?, gatunek = ?, okladka = ? WHERE id = ?";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param("ssissi", $tytul, $rezyser, $rok_premiery, $gatunek, $okladka_destination, $film_id);
                        if ($stmt->execute()) {
                            echo "<script>alert('Film został zaktualizowany.'); window.location.href='films.php';</script>";
                            exit();
                        } else {
                            echo "<script>alert('Błąd podczas aktualizacji filmu.');</script>";
                        }
                    } else {
                        echo "<script>alert('Błąd podczas przesyłania pliku.');</script>";
                    }
                } else {
                    echo "<script>alert('Plik jest za duży (max 8MB).');</script>";
                }
            } else {
                echo "<script>alert('Wystąpił błąd podczas przesyłania pliku.');</script>";
            }
        } else {
            echo "<script>alert('Nieprawidłowy format pliku (dozwolone: jpg, jpeg, png).');</script>";
        }
    } else {
        $query = "UPDATE `filmy` SET tytul = ?, rezyser = ?, rok_premiery = ?, gatunek = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssisi", $tytul, $rezyser, $rok_premiery, $gatunek, $film_id);
        if ($stmt->execute()) {
            echo "<script>alert('Film został zaktualizowany.'); window.location.href='films.php';</script>";
            exit();
        } else {
            echo "<script>alert('Błąd podczas aktualizacji filmu.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['tytul']); ?></title>
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
<div class="box__btn">
     <a class="carousel-item-btn box__film-btn" href="admin_panel.php">Powrót do strony głównej</a>
     <a class="carousel-item-btn box__film-btn" href="films.php">Powrót do filmów</a>
</div>
<form class="addfilm" method="POST" action="" enctype="multipart/form-data">
    <div class="addfilm">
        <h1>Edycja filmu</h1>
        <input type="text" name="tytul" value="<?php echo htmlspecialchars($film['tytul']); ?>" placeholder="Tytuł filmu" required>
        <input type="text" name="rezyser" value="<?php echo htmlspecialchars($film['rezyser']); ?>" placeholder="Reżyser" required>
        <input type="number" name="rok_premiery" value="<?php echo htmlspecialchars($film['rok_premiery']); ?>" placeholder="Rok premiery" required>
        <input type="text" name="gatunek" value="<?php echo htmlspecialchars($film['gatunek']); ?>" placeholder="Gatunek" required>
        <input type="file" name="okladka" accept="image/*">
        <button type="submit">Zapisz zmiany</button>
    </div>
</form>
</div>
    <script src="./js/index.js"></script>
</body>
</html>
