<?php 
include("config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
//Przekierowanie na stronę logowanie gdy uzytkownik nie jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: zaloguj.html");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana hasła i e-mail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/c876edd7c5.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/inne.css">
</head>

<body class="main-page">

<header class="header" id="header">
        <div class="header-bg container">
            <div class="container">
                <div class="container__item container__item-one"></div>
                <div class="container__item container__item-two"></div>
                <div class="container__item container__item-three"></div>
            </div>
        </div>
    </header>
    <div class="container_other">
    <a class="carousel-item-btn box__film-btn" href="panel.php" style="margin-bottom: 2rem; display: inline-block;">
    Powrót do panelu</a>
    <h1 class="other_heading">Zmiana hasła</h1>
    <form class="header__login-form form_other" action="zmiana_hasla.php" method="post">
        <div class="password-container">
            <input placeholder="Stare hasło" type="password" name="old_password" id="old_password" required>
            <button type="button" class="toggle-password" onclick="togglePassword('old_password', this)">
                <img src="./img/eye.png" alt="Pokaż hasło">
            </button>
        </div>
        <br>

        <div class="password-container">
            <input placeholder="Nowe hasło" type="password" name="new_password" id="new_password" required>
            <button type="button" class="toggle-password" onclick="togglePassword('new_password', this)">
                <img src="./img/eye.png" alt="Pokaż hasło">
            </button>
        </div>
        <br>

        <div class="password-container">
            <input placeholder="Potwierdź hasło" type="password" name="confirm_password" id="confirm_password" required>
            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">
                <img src="./img/eye.png" alt="Pokaż hasło">
            </button>
        </div>
        <br>

        <button type="submit" name="submit">Zmień hasło</button>
    </div>

<script>
        function togglePassword(fieldId, button) {
            let passwordField = document.getElementById(fieldId);
            let img = button.querySelector("img");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                img.src = "./img/eye-closed.png"; // Dodaj drugą ikonę np. zamknięte oko
            } else {
                passwordField.type = "password";
                img.src = "./img/eye.png";
            }
        }
</script>
</body>
</html>
<?php
if (isset($_POST['submit'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_email = $_SESSION['user']; // Email użytkownika zapisany w sesji
    $check_password = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*]).{8,64}$/";
    $alert = "";

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $alert = "Wszystkie pola są wymagane!";
    } elseif ($new_password !== $confirm_password) {
        $alert = "Nowe hasło i potwierdzenie muszą być identyczne!";
    } elseif (preg_match($check_password, $password)){
        $alert = "Nieprawidłowe hasło";
    }else{
        // Pobranie starego hasła z bazy
        $query = "SELECT haslo FROM `uzytkownicy` WHERE email=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Sprawdzanie poprawności starego hasła
            if (!password_verify($old_password, $row['haslo'])) {
                $alert = "Stare hasło jest niepoprawne!";
            } else {
                // Hashujemy nowe hasło
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(32));

                // Aktualizacja tokena w bazie
                $update_query = "UPDATE `uzytkownicy` SET token=? WHERE email=?";
                $stmt_update = $mysqli->prepare($update_query);
                $stmt_update->bind_param("ss", $token, $user_email);
                $stmt_update->execute();

                if ($stmt_update->affected_rows > 0) {
                    // Wysyłanie e-maila z linkiem do potwierdzenia zmiany hasła
                    $to = $user_email;
                    $subject = "Potwierdzenie zmiany hasła";
                    $message = "Kliknij poniższy link, aby potwierdzić zmianę hasła:\n\n";
                    $message .= "http://dvdrental.online/potwierdzenie_hasla.php?token=$token&new_password=$new_password";
                    $headers = "From: noreply@dvdrental.online\r\n";
                    $headers .= "Reply-To: kontakt@dvdrental.online\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    if (mail($to, $subject, $message, $headers)) {
                        $alert = "Na Twój e-mail wysłaliśmy link do potwierdzenia zmiany hasła.";
                    } else {
                        $alert = "Błąd podczas wysyłania e-maila.";
                    }
                } else {
                    $alert = "Nie udało się zapisać zmian w bazie danych.";
                }
            }
        } else {
            $alert = "Nie znaleziono użytkownika.";
        }
    }
    if (isset($alert) && $alert != "") {
        echo "<script>alert('$alert'); window.location.href='zmiana_hasla.php';</script>";
    }
}
?>