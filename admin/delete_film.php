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
}else{
    // Usuwanie powiązanych rekordów w tabeli wypozyczenia
    $query = "DELETE FROM `wypozyczenia` WHERE film_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $film_id);
    $stmt->execute();

    // Usuwanie filmu z bazy
    $query = "DELETE FROM `filmy` WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $film_id);
    $stmt->execute();

    // Usuwanie pliku z serwera
    $okladka = $film['okladka'];
    $okladka_path = "http://dvdrental.online/img/" . basename($okladka);
    if (file_exists($okladka_path)) {
        unlink($okladka_path);
    }

    echo "<script>alert('Film został usunięty'); window.location.href='films.php';</script>";
    $stmt->close();
    $mysqli->close();
}
