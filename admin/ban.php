<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: text/html; charset=utf-8');
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}
$user_id = $_GET['id'];
// Pobieranie danych użytkownika
$query = "SELECT * FROM `uzytkownicy` WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user){
    echo "<script>alert('Nie znaleziono użytkownika'); window.location.href='users.php';</script>";
    exit();
}else{
    if ($user['ban'] == 0){
        $ban = 1;
        $query = "UPDATE `uzytkownicy` SET ban = ? where id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $ban, $user_id);
        $stmt->execute();
        echo "<script>alert('Użytkownik został zablokowany'); window.location.href='users.php';</script>";
        exit();
    }
}

