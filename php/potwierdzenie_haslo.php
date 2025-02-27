<?php
include("config.php");

if (isset($_GET['token']) && isset($_GET['new_password'])) {
    $token = $_GET['token'];
    $new_password = $_GET['new_password'];

    // Pobranie użytkownika na podstawie tokena
    $query = "SELECT email FROM `uzytkownicy` WHERE token = '$token'";
    $result = mysqli_query($mysqli, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        //hashujemy hasło
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Aktualizacja hasła w bazie
        $update_query = "UPDATE `uzytkownicy` SET password = '$hashed_password', token = NULL WHERE email = '$email'";
        $update_result = mysqli_query($mysqli, $update_query);

        if ($update_result) {
            echo "<p>Twoje hasło zostało zmienione!</p>";
        } else {
            echo "<p>Wystąpił błąd. Spróbuj ponownie.</p>";
        }
    } else {
        echo "<p>Nieprawidłowy token!</p>";
    }
}
?>
