<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
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
    <title>Zmiana e-mail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/c876edd7c5.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
    <h1 class="other_heading">Zmiana e-mail</h1>
    <form action="zmiana_email.php" method="post" class="header__login-form form_other">
        <input type="email" name="email" placeholder="Nowy adres e-mail">
        <button type="submit" name="submit">Zmień dane</button>
    </form>
    </div>
</body>
</html>
<?php
if(isset($_POST['submit'])){
    $new_email = $_POST['email'];
    $user_email = $_SESSION['user']; 
    $alert = "";

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
        echo "<p>Podano nieprawidłowy adres e-mail.</p>";
        exit();
    }

    // Sprawdzenie, czy użytkownik istnieje
    $query = "SELECT id FROM `uzytkownicy` WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()){
        $user_id = $user['id'];
        $token = bin2hex(random_bytes(32));

        // Aktualizacja new_email i tokena
        $query = "UPDATE `uzytkownicy` SET new_email=?, token=?, confirmed=0 WHERE id=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssi", $new_email, $token, $user_id);

        if($stmt->execute() && $stmt->affected_rows > 0){
            $to = $new_email;
            $subject = "Potwierdzenie zmiany adresu e-mail";
            $message = "Kliknij w poniższy link, aby potwierdzić zmianę adresu e-mail:\n\n";
            $message .= "http://dvdrental.online/potwierdzenie_email.php?token=$token";
            $headers = "From: noreply@dvdrental.online\r\n";
            $headers .= "Reply-To: kontakt@dvdrental.online\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if(mail($to, $subject, $message, $headers)){
                $alert="Na nowy adres e-mail został wysłany link potwierdzający.";
            } else {
                $alert="Wystąpił błąd podczas wysyłania e-maila.";
            }
        } else {
            $alert="Nie wprowadzono żadnych zmian.";
        }
    } else {
        $alert="Nie znaleziono użytkownika.";
    }
}
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='zmiana_email.php';</script>";
}  
?>
