<?php
include("config.php");

if(isset($_GET['token'])){
    $token = mysqli_real_escape_string($mysqli, $_GET['token']); // Zabezpieczenie tokena

    // Pobranie użytkownika z tokenem
    $query = "SELECT id, email, new_email FROM `uzytkownicy` WHERE token='$token' AND confirmed=0";
    $res = mysqli_query($mysqli, $query);

    if($user = mysqli_fetch_assoc($res)){
        $user_id = $user['id'];
        $new_email = $user['new_email'];

        // Aktualizacja e-maila, usunięcie tokena i ustawienie jako potwierdzony
        $update = "UPDATE `uzytkownicy` SET email='$new_email', token=NULL, confirmed=1, new_email=NULL WHERE id='$user_id'";
        if (mysqli_query($mysqli, $update)) {
            echo "Adres e-mail został pomyślnie zmieniony. Możesz się teraz zalogować.";
        } else {
            echo "Wystąpił błąd podczas potwierdzania adresu e-mail.";
        }
    } else {
        echo "Nieprawidłowy lub wygasły token.";
    }
} else {
    echo "Brak tokena.";
}
?>
