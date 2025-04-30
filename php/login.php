<?php
include("config.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
// Sprawdzanie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user'])) {
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}

if(isset($_POST['login'])){
    //odbiera dane z formularza
    $alert="";
    $email=$_POST['email'];
    $password=$_POST['login_password'];

    //Sprawdzanie wyniku w bazie
    $query="SELECT * from `uzytkownicy` where email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $db_user = $result->fetch_assoc();

    //sprawdzanie hasła
    if (!empty($db_user)){
        if ($db_user['ban'] == 1){
            $alert="Konto zablokowane";
        }elseif(password_verify($password, $db_user['haslo'])){
            $_SESSION['user']=$db_user['email']; //tworzenie sesji
            header("Location: index.php");
            exit();
         } else{
             $alert="Nieprawidłowe hasło";
         }
     }else{
         $alert="Nie ma takiego użytkownika";
     }
}      
// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
}  
?>