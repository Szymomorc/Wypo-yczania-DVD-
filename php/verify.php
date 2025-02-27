<?php
include("config.php");

if(isset($_GET['token'])){
    $query= "Select * From `uzytkownicy` WHERE token='{$token}' AND confirmed=0";
    $res= mysqli_query($mysqli, $query);

    if($user = mysqli_fetch_assoc($res)){
    $update = "UPDATE `uzytkownicy` SET confirmed=1, token=NULL WHERE id={$user['id']}";
    mysqli_query($mysqli, $update);

    echo " Rejestracja potwierdona. Możesz się teraz zalogować.";
    }else{
        echo "Nieprawidłowy lub wygasł token";       
    }
}else{
    echo "Brak tokena.";
}
?>