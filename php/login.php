<?php
include("config.php");
session_start();

// Sprawdzanie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user'])) {
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}

if(isset($_POST['login'])){
    //odbiera dane z formularza
    $alert="";
    $email=$_POST['email'];
    $password=$_POST['password'];

    //Sprawdzanie wyniku w bazie
    $query="SELECT * from `uzytkownicy` where email like '{$email}'";
    $wynik= mysqli_query($mysqli,$query);
    $db_user=mysqli_fetch_assoc($wynik);
    
    //sprawdzanie hasła
    if (!empty($db_user)){
        if (password_verify($password, $db_user['haslo'])){
           $_SESSION['user']=$db_user['email']; //tworzenie sesji
           header("Location: index.php");
           exit();
        } else{
            $alert="Nieprawidłowe hasło";
        }
    }else{
        $alert="Nie ma tekiego użytkownika";
    }
}
// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<p>".$alert."</p>";
}  
?>