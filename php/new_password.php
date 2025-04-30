<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowe Hasło</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/c876edd7c5.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/inne.css">
</head>
<style>
.password-container {
    position: relative;
    display: flex;
    align-items: center;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    height:30px;
    width:30px;
    aspect-ratio: 1 / 1;
    display: flex;
    min-width: 10% !important;
}

.toggle-password img {
    width: 24px;
    height: 24px;
}
</style>
<body>
<header class="header" id="header">
        <div class="header-bg container">
            <div class="container">
                <div class="container__item container__item-one"></div>
                <div class="container__item container__item-two"></div>
                <div class="container__item container__item-three"></div>
            </div>
        </div>
    </header>
    <div class="container_other">
    <h2 class="other_heading">Wpisz nowe hasło</h2>
    <form class="header__login-form form_other" action="new_password.php" method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <div class="password-container">
        <input type="password" id="new_password" placeholder="Hasło" name="new_password" required>
        <button type="button" class="toggle-password" onclick="togglePassword('new_password', this)">
            <img src="./img/eye.png" alt="Pokaż hasło">
        </button>
    </div>
    <br><br>
    <button type="submit" name="new">Nowe hasło</button>
    </form>
    </div>
    

<script>
    function togglePassword(fieldId, button) {
        let passwordField = document.getElementById(fieldId);
        let img = button.querySelector("img");
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            img.src = "./img/eye-closed.png"; // Dodaj drugą ikonę np. zamknięte oko
        } else {
            passwordField.type = "password";
            img.src = "./img/eye.png";
        }
    }
</script>
</body>
</html>

<?php
include("config.php");
$alert = "";
$check_password = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*]).{8,64}$/";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $token = $_POST['token'];
    
    // Sprawdzenie, czy token został przekazany w URL
    if (!isset($_POST['token']) || empty($_POST['token'])) {
        die("Niepoprawny token resetujący!");
    }

    // Usuwamy białe znaki z tokenu przed porównaniem
    $token = trim($token);
    if(!preg_match($check_password, $new_password)){
        $alert = "Hasło musi zawierać co najmniej 8 znaków w tym wielkie litery, małe litery, cyfry oraz znaki specjalne.";
    }else{
        // Przygotowanie zapytania do bazy danych
        $stmt = $mysqli->prepare("SELECT id FROM `uzytkownicy` WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Aktualizacja hasła
            $stmt = $mysqli->prepare("UPDATE `uzytkownicy` SET haslo = ? WHERE token = ?");
            $stmt->bind_param("ss", password_hash($new_password, PASSWORD_DEFAULT), $token);
            $stmt->execute();
            $alert = "Hasło zostało zmienione";
            echo "<script>alert('$alert'); window.location.href='zaloguj.php';</script>"
        } else {
            $alert = "Nie ma takiego tokenu w bazie";
        }
    }
}

// Wyświetlanie błędu
if (isset($alert) && $alert != "") {
    echo "<script>alert('$alert'); window.location.href='new_password.php';</script>";
}
?>
