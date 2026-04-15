<?php
include 'php/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // als formulier is verstuurd haalt hij zegmaar de username en wachtwoord op
    $username = trim($_POST['username']); 
    $password = $_POST['password']; 

    $stmt = $databaseVerbinding->prepare("SELECT id, username, password, role FROM users WHERE username = ?"); // bereid query voor om gebruiker te vinden
    $stmt->execute([$username]); // hier voert hij de query uit met de username en daarna haalt hij deze zegmaar op
    $user = $stmt->fetch(); 

    if ($user && password_verify($password, $user['password'])) { // controleert of de gebruiker bestaat en wachtwoord klopt
        session_start(); 

        // opslaan van variabelen 
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['username'] = $user['username']; 
        $_SESSION['role'] = $user['role']; 

        if ($user['role'] == 'admin') { 
            header("Location: admin.php"); 
        } else {
            $error = "Alleen admins mogen inloggen."; 
        }
        exit; 
    } else { 
        $error = "Ongeldige gebruikersnaam of wachtwoord."; 
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Frietlust</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header vast" id="header">
        <div class="header-inner">
            <a href="index.php" class="logo">Friet<span>lust</span></a>
            <nav class="nav" id="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php">Menu</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="sectie sectie-licht">
        <div class="container auth-container">
            <div class="auth-card">
                <h1><i class="fas fa-sign-in-alt"></i> Admin Login</h1>
                <?php if (isset($error)) echo "<div class='auth-error'><i class='fas fa-exclamation-circle'></i> $error</div>"; ?>
                <form method="post" class="auth-form">
                    <div class="form-groep">
                        <label for="username">Gebruikersnaam</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-groep">
                        <label for="password">Wachtwoord</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-admin">Login</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
