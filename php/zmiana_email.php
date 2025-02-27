<?php 
include("config.php");
session_start();
//Przekierowanie na stronę logowanie gdy uzytkownik nie jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: zaloguj.html");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana hasła i e-mail</title>
</head>
<body>
    <h1>Zmiana hasła i e-mail</h1>
    <form action="zmiana_email.php" method="post">

        <label for="email">Nowy adres e-mail:</label>
        <input type="email" name="email" required>
        
        <input type="submit" name="submit" value="Zmień dane">
</body>
</html>
<?php
if(isset($_POST['submit'])){
    $new_email=$_POST['email'];
    $user_email=$_SESSION['user']; // Zakładamy ze dane są w sesji
    //zapytanie do bazy poniewarz w sesji jest zapisaywany email
    $alert = "";
    if (empty($new_email) && !filter_var($new_email, FILTER_VALIDATE_EMAIL)){
        $alert = "Podano nieprawidłowy adres e-mail.";
        exit();
    }
    $query="Select email From`uzytkownicy` where email = '{$user_email}'";
    $result=mysqli_query($mysqli, $query);
    $row=mysqli_fetch_assoc($result);
    $current_name=$row['email'];
        //jeśli imie nie jest takie samo jak wpisane
    if ($current_name != $new_email){
        //generowanie tokena do potwierdzenia i  zmiana email na nowy 
        $token = bin2hex(random_bytes(32));
        $query = "Update `uzytkownicy` SET token = '$token' where email='{$user_email}";
        $update_result=mysqli_query($mysqli, $query);
        //wysłanie email
        if($update_result || mysqli_affected_rows($mysqli)>0){
            //Wysyłanie e-mail z linkiem potwierdzającym 
            $to = $new_email;
            $subject = "Potwierdzenie zmiany adresu e-mail";
            $message = "Kliknij w poniższy link, aby potwierdzić zmianę adresu e-mail:\n\n";
            $message .= "https://twojadomena.pl/potwierdzenie_email.php?token=$token&new_email=$new_email";
            $headers = "From: no-reply@twojadomena.pl\r\n";
            $headers .= "Reply-To: kontakt@twojadomena.pl\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            if(mail($to, $subject, $message, $headers)){
                $alert = "Na nowy adres e-mail został wysłany link potwierdzający.";
            } else {
                $alert = "Wystąpił błąd podczas wysyłania e-maila.";
            }
        }else{
            $alert = "Nie wprowadzono żadnych zmian.";
        }
    }else{
        $alert = "Nowy adres e-mail jest taki sam jak aktualny.";
    }
}else{
    $alert="Użytkownik nie został znaleziony.";
}
if (isset($alert) && $alert != "") {
    echo "<p>".$alert."</p>";
} 
?>