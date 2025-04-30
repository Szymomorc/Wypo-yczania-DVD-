<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset hasła</title>
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
        <h1 class="other_heading">Reset hasła</h1>
    <form class="header__login-form form_other" action="reset_password.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <button type="submit">Resetuj haslo</button>
    </form>
    </div>
</body>
</html>
<?php
include("config.php");
$alert ="";
if (isset($_POST['email'])){
    $email = $_POST['email'];
    
    $stmt = $mysqli->prepare("SELECT id FROM `uzytkownicy` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt = $mysqli->prepare("UPDATE `uzytkownicy` SET token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        $reset_link = "http://dvdrental.online/new_password.php?token=$token";
        $to = $email;
        $subject = "Resetowanie hasła";
        $message = "Kliknij w link, aby zresetować hasło:";
        $message .= $reset_link;
        $headers = "FROM no-reply@dvdrental.online";
        if (mail($to, $subject, $message, $headers)){
            $alert = "Wysłano link resetujący hasło na podany adres email";
        } else {
            $alert = "Wystąpił błąd podczas wysyłania linku resetującego hasło";
        }
    }else{
        $alert = "Nie ma takiego emaila w bazie";
    }
}
// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
}
?>