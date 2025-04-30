<?php 
// połączenie z bazą musi mieć dostęp do większości plików najlepiej ten sam folder
$mysqli = new mysqli("localhost", "srv82359_szymon","zaq1@WSX","srv82359_wyporzyczalnia");
mysqli_set_charset($mysqli, "utf8");
// jeśli brak połączeia 
if ($mysqli -> connect_error) {
    die("Nie można połączyć z bazą danych.<br />Błąd:" . $mysqli->connect_error);
}
?>