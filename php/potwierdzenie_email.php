<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$alert="";
$token=$_GET['token'];
include("config.php");
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET['token'])){
    $token = mysqli_real_escape_string($mysqli, $_GET['token']);

    // Pobranie użytkownika na podstawie tokena
    $query = "SELECT id, new_email FROM `uzytkownicy` WHERE token=? AND confirmed=0";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        $user_id = $user['id'];
        $new_email = $user['new_email'];

        if (!empty($new_email)) {
            // Aktualizacja: zmiana emaila, usunięcie tokena i ustawienie new_email na NULL
            $update = "UPDATE `uzytkownicy` SET email=?, token=NULL, confirmed=1, new_email=NULL WHERE id=?";
            $stmt_update = $mysqli->prepare($update);
            $stmt_update->bind_param("si", $new_email, $user_id);

            if ($stmt_update->execute()) {
                $alert="Adres e-mail został pomyślnie zmieniony. Możesz się teraz zalogować.";

                session_start();
                session_unset();
                session_destroy();

                echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
                exit();
            } else {
                $alert="Wystąpił błąd podczas potwierdzania adresu e-mail.";
            }
        } else {
            $alert="Nie znaleziono nowego adresu e-mail do aktualizacji.";
        }
    } else {
        $alert="Nieprawidłowy lub wygasły token.";
    }
} else {
    $alert="Brak tokena.";
}
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='zmiana_email.php';</script>";
} 
?>
