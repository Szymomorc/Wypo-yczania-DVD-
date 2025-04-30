<?php
include("config.php");
header('Content-Type: text/html; charset=utf-8');
$alert="";
$token=$_GET['token'];
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $query= "Select * From `uzytkownicy` WHERE token=? AND confirmed=0";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        $update = "UPDATE `uzytkownicy` SET confirmed=1, token=NULL WHERE id=?";
        $stmt_update = $mysqli->prepare($update);
        $stmt_update->bind_param("i", $user['id']);
        $stmt_update->execute();

        $alert=" Rejestracja potwierdzona. Możesz się teraz zalogować.";
    }else{
        $alert="Nieprawidłowy lub wygasły token";       
    }
}else{
    $alert="Brak tokena.";
}
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='zaloguj.html';</script>";
} 
?>