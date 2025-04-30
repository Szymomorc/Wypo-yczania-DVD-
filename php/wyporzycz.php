<?php
include("config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
if (!isset($_SESSION['user'])) {
    die("Musisz być zalogowany, aby wypożyczyć film.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['film_id'])) {
    if (!filter_var($_POST['film_id'], FILTER_VALIDATE_INT)) {
        die("Nieprawidłowy identyfikator filmu.");
    }

    $user = $_SESSION['user'];
    $film_id = intval($_POST['film_id']);
    
    $checkQuery = "SELECT wypozyczenia.* FROM `wypozyczenia` join uzytkownicy ON wypozyczenia.uzytkownik_id = uzytkownicy.id where uzytkownicy.email = ? and film_id = ? and (data_zwrotu IS NULL OR data_zwrotu > NOW());";
    $stmt = $mysqli->prepare($checkQuery);
    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $mysqli->error);
    }
    $stmt->bind_param('si', $user, $film_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo htmlspecialchars("Już wypożyczyłeś ten film.") ;
    } else {
        $userQuery = "SELECT id FROM `uzytkownicy` WHERE email = ?";
        $stmt = $mysqli->prepare($userQuery);
        if(!$stmt) {
            die("Błąd przygotowania zapytania: " . $mysqli->error);
        }
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $userResult = $stmt->get_result();
        if ($userResult->num_rows > 0) {
            $user_id = $userResult->fetch_assoc()['id'];
        } else {
            die("Nie znaleziono użytkownika.");
        }
        $insertQuery = "INSERT INTO wypozyczenia (uzytkownik_id, film_id, data_wypozyczenia, data_zwrotu) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY));";
        $stmt = $mysqli->prepare($insertQuery);
        if (!$stmt) {
            die("Błąd przygotowania zapytania: " . $mysqli->error);
        }
        $stmt->bind_param('ii', $user_id, $film_id);
        
        if ($stmt->execute()) {
            echo htmlspecialchars("Film został wypożyczony.") ;
        } else {
            echo htmlspecialchars("Błąd przy wypożyczaniu filmu.") ;
       }
    }
    $stmt->close();
}
$mysqli->close();
?>