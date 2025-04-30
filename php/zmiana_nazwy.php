<?php 
include("config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: zaloguj.html");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana nazwy</title>
    <link rel="stylesheet" href="./css/inne.css">
</head>
<body>
    <header class="header" id="header">
            <div class="header-bg container">
                <div class="container">
                    <div class="container__item container__item-one"></div>
                    <div class="container__item container__item-two"></div>
                    <div class="container__item container__item-three"></div>
                </div>
            </div>
        </header>
        <div class="container_other">
        <a class="carousel-item-btn box__film-btn" href="panel.php" style="margin-bottom: 2rem; display: inline-block;">
    Powrót do panelu</a>
        <h1 class="other_heading">Zmiana nazwy</h1>
        <form action="zmiana_nazwy.php" method="post" class="header__login-form form_other">
            <input type="text" name="nazwa" placeholder="Nowa nazwa użytkownika">
            <button type="submit" name="submit">Zmień dane</button>
        </form>
        </div>
</body>
</html>

<?php
// Odbiór danych z formularza
if (isset($_POST['submit'])) {
    if (!isset($mysqli)) {
        die("Błąd połączenia z bazą danych. Upewnij się, że config.php działa poprawnie.");
    }

    $new_name = $_POST['nazwa'];
    $user_email = $_SESSION['user']; // Pobieranie e-maila z sesji
    $check_nazwa = '/^[A-Za-z0-9_-]{3,20}$/';

    // Walidacja nazwy użytkownika
    if (empty($new_name)) {
        $alert = "Nazwa użytkownika nie może być pusta.";
    } elseif (!preg_match($check_nazwa, $new_name)) {
        $alert = "Nazwa użytkownika musi zawierać od 3 do 20 znaków (litery, cyfry, podkreślenia lub myślniki).";
    } else {
        // Pobieranie aktualnej nazwy użytkownika
        $query = "SELECT nazwa FROM `uzytkownicy` WHERE email=?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $user_email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $alert = "Użytkownik nie został znaleziony.";
            } else {
                $row = $result->fetch_assoc();
                $current_name = $row['nazwa'];

                // Sprawdzanie, czy nazwa się zmienia
                if ($current_name === $new_name) {
                    $alert = "Nowa nazwa jest taka sama jak aktualna.";
                } else {
                    // Aktualizacja nazwy użytkownika
                    $update_query = "UPDATE `uzytkownicy` SET nazwa=? WHERE email=?";
                    $stmt_update = $mysqli->prepare($update_query);

                    if ($stmt_update) {
                        $stmt_update->bind_param("ss", $new_name, $user_email);
                        $stmt_update->execute();

                        if ($stmt_update->affected_rows > 0) {
                            $alert = "Nazwa użytkownika została zmieniona pomyślnie.";

                            session_start();
                            session_unset();
                            session_destroy();

                            echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
                            exit();
                        } else {
                            $alert = "Nie wprowadzono żadnych zmian.";
                        }
                        $stmt_update->close();
                    } else {
                        $alert = "Błąd zapytania do bazy danych.";
                    }
                }
            }
            $stmt->close();
        } else {
            $alert = "Błąd zapytania do bazy danych.";
        }
    }
    
    // Wyświetlenie komunikatu i przekierowanie
    echo "<script>alert('$alert'); window.location.href='zmiana_nazwy.php';</script>";
}
?>
