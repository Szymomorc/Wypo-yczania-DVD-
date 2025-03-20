<?php
include("config.php");
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}

$film_id = $_GET['id'];

// Pobieranie danych filmu
$query = "SELECT * FROM `filmy` WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    $alert = "Nie znaleziono filmu";
    echo "<script>alert('$alert'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['tytul']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($film['tytul']); ?></h1>
    <p>Gatunek: <?php echo htmlspecialchars($film['gatunek']); ?></p>
    <p>Reżyser: <?php echo htmlspecialchars($film['rezyser']); ?></p>
    <p>Rok premiery: <?php echo htmlspecialchars($film['rok_premiery']); ?></p>
    <p>Data dodania: <?php echo htmlspecialchars($film['data_dodania']); ?></p>
    <?php if (!empty($film['okladka'])): ?>
        <img src="<?php echo htmlspecialchars($film['okladka']); ?>" alt="<?php echo htmlspecialchars($film['tytul']); ?>">
        <?php
        if (isset($_SESSION['user'])) {
            // Użytkownik jest zalogowany
            echo '<button class="">WYPOŻYCZ</button>'; // Przycisk wyporzyć
        }
        ?>
    <?php endif; ?>
    <a href="index.php">Powrót do strony głównej</a>
</body>
</html>
