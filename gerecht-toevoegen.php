<?php
session_start();
include 'php/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $toevoegen = $databaseVerbinding->prepare("INSERT INTO gerechten (naam, beschrijving, prijs, categorie) VALUES (:naam, :beschrijving, :prijs, :categorie)");
    $toevoegen->bindParam(':naam',         $_POST['naam']);
    $toevoegen->bindParam(':beschrijving', $_POST['beschrijving']);
    $toevoegen->bindParam(':prijs',        $_POST['prijs']);
    $toevoegen->bindParam(':categorie',    $_POST['categorie']);
    $toevoegen->execute();
    header('Location: admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Frietlust - Gerecht Toevoegen</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/admin.css" />
  </head>
  <body>

    <header class="admin-header">
      <a href="index.php" class="admin-logo">Frietlust</a>
      <nav class="admin-nav">
        <a href="admin.php" class="admin-uitloggen-knop">Terug naar beheer</a>
        <a href="logout.php" class="admin-uitloggen-knop">Uitloggen</a>
      </nav>
    </header>

    <main class="admin-sectie">
      <h1 class="admin-titel">Gerecht Toevoegen</h1>
      <form method="POST" class="admin-form">

        <div class="form-groep">
          <label for="naam">Naam:</label>
          <input type="text" name="naam" id="naam" required />
        </div>

        <div class="form-groep">
          <label for="beschrijving">Beschrijving:</label>
          <textarea name="beschrijving" id="beschrijving" required></textarea>
        </div>

        <div class="form-groep">
          <label for="prijs">Prijs (€):</label>
          <input type="number" step="0.01" name="prijs" id="prijs" required />
        </div>

        <div class="form-groep">
          <label for="categorie">Categorie:</label>
          <select name="categorie" id="categorie" required>
            <option value="Friet">Friet</option>
            <option value="Snacks">Snacks</option>
            <option value="Burgers">Burgers</option>
            <option value="Dranken">Dranken</option>
          </select>
        </div>

        <button type="submit" class="btn-admin">Toevoegen</button>
      </form>
    </main>

    <footer class="footer">
      <p>&copy; 2024 <span>Frietlust</span> - Alle rechten voorbehouden</p>
    </footer>
  </body>
</html>
