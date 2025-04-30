<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
// Sprawdzanie, czy administrator jest już zalogowany
if (isset($_SESSION['admin'])) {
    header("Location: admin_panel.php"); // Przekierowanie na panel admina
    exit();
}

if (isset($_POST['email'])) {
    // Odbieranie danych z formularza
    $alert = "";
    $login = $_POST['email'];
    $password = $_POST['password'];

    // Sprawdzanie administratora w bazie danych
    $query = "SELECT * FROM `uzytkownicy` WHERE email = ? and admin = 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Sprawdzanie hasła
    if (!empty($admin)) {
        if (password_verify($password, $admin['haslo'])) {
            $_SESSION['admin'] = $admin['email']; // Tworzenie sesji
            header("Location: admin_panel.php");
            exit();
        } else {
            $alert = "Nieprawidłowe hasło";
        }
    } else {
        $alert = "Nie ma takiego administratora";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie do panelu admina</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<nav>
       
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
        <div class="header__login-form-box">
        <h1 class="header__login-h1">Logowanie do panelu admina</h1>
    <form class="header__login-form" action="admin.php" method="POST">
        <input type="email" id="email" name="email" placeholder="Email" required><br><br>

        <input type="password" id="password"  placeholder="Hasło" name="password" required><br><br>

        <input type="submit" value="Zaloguj">
    </form>
        </div>
    </div>
    <?php if (isset($alert)) { echo "<p>$alert</p>"; } ?>
    <script src="../js/index.js"></script>
</body>
</html>