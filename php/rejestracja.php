<?php 
include("config.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: text/html; charset=utf-8');

// sprawdzanie czy uytkonik jest zalogowany
if (isset($_SESSION['user'])) {
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}
// logika rejestracji
if (isset($_POST['submit'])) {
    // Odbieranie danych
    $alert = "";
    $nazwa = $_POST['nazwa'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $check_nazwa = '/^[A-Za-z0-9_-]{3,20}$/';
    $check_password = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*]).{8,64}$/";
    
    // Sprawdzanie poprawności adresu e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "Nieprawidłowy format adresu e-mail.";
    }
    // Sprawdzanie poprawności nazwy użytkownika
    elseif (!preg_match($check_nazwa, $nazwa)) {
        $alert = "Nieprawidłowa nazwa użytkownika! Musi mieć długość 3-20 i zawierać tylko dozwolone znaki: a-z, A-Z, 0-9, znaki specjalne '_-'";
    }
    // Sprawdzanie poprawności hasła
    elseif (!preg_match($check_password, $password)) {
        $alert = "Nieprawidłowe hasło! Musi mieć długość 8-64, zawierać co najmniej jedną małą i wielką literę, cyfrę i znak specjalny (dozwolone: !@#$%^&*)";
    } else {
        // Sprawdzamy czy użytkownik o podanej nazwie już istnieje
        $query = "SELECT * FROM uzytkownicy WHERE nazwa = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $nazwa);
        $stmt->execute();
        $result = $stmt->get_result();
        $db_user = $result->fetch_assoc();
        
        if (!empty($db_user)) {
            $alert = "Użytkownik już istnieje";
        } else {
            // Sprawdzamy czy adres e-mail już jest używany
            $query_email = "SELECT * FROM uzytkownicy WHERE email = ?";
            $stmt_email = $mysqli->prepare($query_email);
            $stmt_email->bind_param("s", $email);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            $db_email = $result_email->fetch_assoc();
            
            if (!empty($db_email)) {
                $alert = "Podany adres email jest już używany";
            } else {
                // Hashowanie hasła
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                // Tworzenie tokena
                $token = bin2hex(random_bytes(32));
                // Dodawanie użytkownika do bazy
                $add = "INSERT INTO uzytkownicy (nazwa, email, haslo, token, confirmed) VALUES (?, ?, ?, ?, 0)";
                $stmt_add = $mysqli->prepare($add);
                $stmt_add->bind_param("ssss", $nazwa, $email, $hashed_password, $token);
                if ($stmt_add->execute()) {
                    $to = $email;
                    $subject = "Potwierdź rejestrację";
                    $mail_message = "Cześć {$nazwa}, \n\nKliknij w poniższy link, aby potwierdzić swoją rejestrację:\n";
                    $mail_message .= "http://dvdrental.online/verify.php?token={$token}\n\nDziękujemy";
                    $headers = "From: noreply@dvdrental.com";
                    if (mail($to, $subject, $mail_message, $headers)) {
                        $alert = "Rejestracja przebiegła pomyślnie. Sprawdź swoją skrzynkę pocztową, aby potwierdzić rejestrację.";
                    } else {
                        $alert = "Rejestracja nie powiodła się. Nie udało się wysłać e-maila potwierdzającego.";
                    }
                } else {
                    $alert = "Wystąpił błąd podczas rejestracji. Spróbuj ponownie.";
                }
            }
        }
    }
    
    echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
    exit;
}
?>