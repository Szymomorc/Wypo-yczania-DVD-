<?php 
include("config.php");
session_start();

// sprawdzanie czy uytkonik jest zalogowany
if(isset($_SESSION['email']) and isset($_SESSION['hashed_password']))
{
    $email = addcslashes(strip_tags($_SESSION["email"]));
    $hashed_password = addcslashes(strip_tags($_SESSION['hashed_password']));
    $query = "Select * from `uzytkownicy` where email='{$email}' and haslo='{$hashed_password}'";
    $result = mysqli_query($mysqli, $query);
    $db_user = mysqli_fetch_assoc($result);
    // jeśli istnieje
    if(!empty($db_user && password_verify($hashed_password, $db_user['haslo']))){
        header("Location: index.php");
    } else { //jeśli nie istnieje
        session_unset();
        session_destroy();
        header("Location: zaloguj.html");
    }
}
// logika rejestracji
if(isset($_POST['submit']))
{
    //odbieranie danych
    $alert = "";
    $nazwa = $_POST['nazwa'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $check_nazwa = '/^[A-Za-z0-9_-]{3,20}$/';
    $check_password = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*]).{8,64}$/";
    // sprawdzanie poprawności 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $alert = "Nieprawidłowy format adresu e-mail.";
    } elseif (preg_match($check_nazwa, $nazwa)){
        if (preg_match($check_password, $password)) {
            $query = "Select * from `uzytkownicy` where nazwa='{$nazwa}'";
            $wynik = mysqli_query($mysqli, $query);
            $db_user = mysqli_fetch_assoc($wynik);   
            // sprawdza czy uzytkonik istnieje
            if (!empty($db_user)) {
                $alert = "Użytkownik już istnieje";
            } else {
                //sprawdzanie czy email juz istnieje
                $query_email = "Select * from `uzytkownicy` where email='{$email}'";
                $wynik_email = mysqli_query($mysqli, $query_email);
                $db_email = mysqli_fetch_assoc($wynik_email);
                
                if (!empty($db_email)) {
                    $alert = "Podany adres email jest już używany";
                } else {// jeśli nie ma email to hashed hasło 
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    // tworzy token
                    $token = bin2hex(random_bytes(32));
                    // tworzy uzytkownika
                    $add = "Insert Into `uzytkownicy` (nazwa, email, haslo, token, confirmed) 
                            Values ('{$nazwa}', '{$email}', '{$hashed_password}', '{$token}', 0)";
                    //wysyła email
                    if (mysqli_query($mysqli, $add)) {
                        $to = $email;
                        $subject = "Potwierdź rejestracje";
                        $meil = "Cześć {$nazwa}, \n\nKliknij w poniższy link, aby potwierdzić swoją rejestrację:\n";
                        $meil .= "http://yourwebsite.com/verify.php?token={$token}\n\nDziękujemy";
                        $headers = "From: noreply@yourwebsite.com";
                        //sprawdza czy się powiodło 
                        if (mail($to, $subject, $meil, $headers)) {
                            $alert = "Rejestracja przebiegła pomyślnie. Sprawdź swoją skrzynkę pocztową, aby potwierdzić rejestrację.";
                        } else {
                            $alert = "Rejestracja nie powiodła się. Nie udało się wysłać e-maila potwierdzającego.";
                        }
                    } else {
                        $alert = "Wystąpił błąd podczas rejestracji. Spróbuj ponownie.";
                    }
                }
            }
        } else {
            $alert = "Nieprawidłowe hasło! Musi mieć długość 8-64, zawierać co najmniej jedną małą i wielką literę, cyfrę i znak specjalny (dozwolone: !@#$%^&*)";
        }
    } else {
        $alert = "Nieprawidłowe logowanie! Musi mieć długość 3-16 i zawierać tylko dozwolone znaki: a-z, A-Z, 0-9, znaki specjalne '_-'";
    }
}

// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<p>".$alert."</p>";
}  
?>
