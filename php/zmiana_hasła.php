<?php 
include("config.php");
session_start();
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
</head>
<body>
    <h1>Zmiana hasła i e-mail</h1>
    <form action="zmiana_hasla.php" method="post">
    <label for="stare_haslo">Stare hasło:</label>
        <input type="password" name="old_password" required><br>

        <label for="nowe_haslo">Nowe hasło:</label>
        <input type="password" name="new_password"required><br>

        <label for="potwierdz">Potwierdź hasło</label>
        <input type="password" name="confirm_password" required>
        
        <input type="submit" name="submit" value="Zmień hasło">
</body>
</html>
<?php
if (isset($_POST['submit'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_email = $_SESSION['user']; // Email użytkownika zapisany w sesji
    
    $alert = "";

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $alert = "Wszystkie pola są wymagane!";
    } elseif ($new_password !== $confirm_password) {
        $alert = "Nowe hasło i potwierdzenie muszą być identyczne!";
    } else {
        // Pobranie starego hasła z bazy
        $query = "SELECT haslo FROM `uzytkownicy` WHERE email = '$user_email'";
        $result = mysqli_query($mysqli, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            // Sprawdzanie poprawności starego hasła
            if (!password_verify($old_password, $row['haslo'])) {
                $alert = "Stare hasło jest niepoprawne!";
            } else {
                // Hashujemy nowe hasło
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(32));

                // Aktualizacja tokena w bazie
                $update_query = "UPDATE `uzytkownicy` SET token='$token' WHERE email = '$user_email'";
                $update_result = mysqli_query($mysqli, $update_query);

                if ($update_result) {
                    // Wysyłanie e-maila z linkiem do potwierdzenia zmiany hasła
                    $to = $user_email;
                    $subject = "Potwierdzenie zmiany hasła";
                    $message = "Kliknij poniższy link, aby potwierdzić zmianę hasła:\n\n";
                    $message .= "https://twojadomena.pl/potwierdzenie_hasla.php?token=$token&new_password=$hashed_password";
                    $headers = "From: no-reply@twojadomena.pl\r\n";
                    $headers .= "Reply-To: kontakt@twojadomena.pl\r\n";
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
        echo "<p>".$alert."</p>";
    }
}
?>
