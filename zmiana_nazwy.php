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
    <form method="post">

        <label for="username">Nowa nazwa:</label>
        <input type="text" name="nazwa" required>

        <input type="submit" name="submit" value="Zmień dane">
</body>
</html>
<?php
//odbiera dane z form
if(isset($_POST['submit'])){
    $new_name=$_POST['nazwa'];
    $user_email=$_SESSION['user']; // Zakładamy ze dane są w sesji
    //zapytanie do bazy poniewarz w sesji jest zapisaywany email
    $alert = "";
    if (empty($new_name)) {
        $alert = "Nazwa użytkownika nie może być pusta.";
        exit();
    }
    $query="Select nazwa From`uzytkownicy` where email = '{$user_email}'";
    $result=mysqli_query($mysqli, $query);
    $row=mysqli_fetch_assoc($result);
    $current_name=$row['nazwa'];
        //jeśli imie nie jest takie samo jak wpisane
        if ($current_name != $new_name){
        //wykonuje zapytanie
        $query = "Update `uzytkownicy` SET nazwa = '{$new_name}' where email='{$user_email}'";
        $update_result=mysqli_query($mysqli, $query);
        //wykonanieś
            if($update_result && mysqli_affected_rows($mysqli)>0){
                $alert="Nazwa użytkownika została zmieniona pomyślnie.";
            }else{
                $alert="Nie wprowadzono żadnych zmian.";
            }
        }else{
        $alert= "Nowa nazwa jest taka sama jak aktualna.";
        }
    }else{
        $alert="Użytkownik nie został znaleziony.";
}
if (isset($alert) && $alert != "") {
    echo "<p>".$alert."</p>";
} 
?>
