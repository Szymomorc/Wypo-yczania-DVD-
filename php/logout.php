<?php
session_start();
session_unset(); // Usunięcie wszystkich zmiennych sesyjnych
session_destroy(); // Zniszczenie sesji
header("Location: index.php"); // Przekierowanie na stronę główną
exit();
?>