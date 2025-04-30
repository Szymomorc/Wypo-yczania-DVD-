<?php
include("config.php"); // Połączenie z bazą danych
session_start(); // Rozpoczęcie sesji
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: zaloguj.html");
    exit();
}

$user = $_SESSION['user']; 
$alert = "";
 
// Sprawdzenie, czy użytkownik istnieje i czy jest administratorem
$sql = "SELECT id, admin FROM uzytkownicy WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$dane = $result->fetch_assoc();

// Jeśli użytkownik nie istnieje
if (!$dane) {
    $alert = "Użytkownik nie istnieje.";
    echo "<script>alert('$alert'); window.location.href='panel.php';</script>";
    exit();
}

// Jeśli użytkownik jest administratorem, nie może usunąć konta
if ($dane['admin'] == 1) {
    $alert = "Administrator nie może usunąć konta.";
    echo "<script>alert('$alert'); window.location.href='panel.php';</script>";
    exit();
}

$user_id = $dane['id'];

// Sprawdzenie, czy użytkownik ma wypożyczone filmy
$sql_check = "SELECT COUNT(*) AS active_rentals FROM wypozyczenia WHERE uzytkownik_id = ? AND zwrot=0";
$stmt_check = $mysqli->prepare($sql_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$wypozyczenia = $result_check->fetch_assoc();

if ($wypozyczenia['active_rentals'] > 0) {
    $alert = "Nie można usunąć konta, ponieważ masz wypożyczone filmy.";
    echo "<script>alert('$alert'); window.location.href='panel.php';</script>";
    exit();
}

// Usunięcie konta użytkownika
$sql_delete = "DELETE FROM uzytkownicy WHERE id = ?";
$stmt_delete = $mysqli->prepare($sql_delete);
$stmt_delete->bind_param("i", $user_id);

if ($stmt_delete->execute()) {
    session_destroy();
    $alert = "Konto zostało pomyśnie usuniętę";
    echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
    header("Location: zaloguj.html");
    exit();
} else {
    $alert = "Wystąpił błąd podczas usuwania konta.";
    echo "<script>alert('$alert'); window.location.href='panel.php';</script>";  
}