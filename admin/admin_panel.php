<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");
session_start();
// Sprawdzanie, czy administrator jest zalogowany
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php"); // Przekierowanie na stronę logowania
    exit();
}

$user = $_SESSION['admin'];

// Przygotowanie zapytania
$sql = "SELECT nazwa FROM `uzytkownicy` WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$dane = $result->fetch_assoc();


// Tutaj umieść kod panelu admina
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel admina</title>
</head>
<body>
    <h1>Witaj w panelu admina, <?php echo htmlspecialchars($dane['nazwa']); ?> </h1>
    <a href="../logout.php">Wyloguj</a>
</body>
</html>
