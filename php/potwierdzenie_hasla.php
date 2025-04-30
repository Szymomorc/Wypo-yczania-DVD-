<?php
include("config.php");
$alert = "";
header('Content-Type: text/html; charset=utf-8');
if (!isset($_GET['token']) || !isset($_GET['new_password'])) {
    die("Brak wymaganych parametrów.");
}

$token = $_GET['token'];
$new_password = $_GET['new_password'];

// Pobranie użytkownika na podstawie tokena
$query = "SELECT email FROM `uzytkownicy` WHERE token = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Błąd zapytania: " . $mysqli->error);
}

$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    // Hashujemy hasło
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Aktualizacja hasła w bazie
    $update_query = "UPDATE `uzytkownicy` SET haslo = ?, token = NULL WHERE email = ?";
    $stmt_update = $mysqli->prepare($update_query);

    if (!$stmt_update) {
        die("Błąd zapytania: " . $mysqli->error);
    }

    $stmt_update->bind_param("ss", $hashed_password, $email);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        $alert = "Twoje hasło zostało zmienione!";

        session_start();
        session_unset();
        session_destroy();

        echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
        exit();
    } else {
        $alert = "Wystąpił błąd. Spróbuj ponownie.";
    }
} else {
    $alert = "Nieprawidłowy token!";
}

if (!empty($alert)) {
    echo "<script>alert('$alert'); window.location.href='zmiana_hasla.php';</script>";
    exit();
}
?>
