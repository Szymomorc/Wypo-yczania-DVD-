<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config.php");
session_start();

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
</head>
<body>
    <h1>Logowanie do panelu admina</h1>
    <form action="admin.php" method="POST">
        <label for="login">Login:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Zaloguj">
    </form>
    <?php if (isset($alert)) { echo "<p>$alert</p>"; } ?>
</body>
</html>
