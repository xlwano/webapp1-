<?php
session_start(); // start een sessie omdat niet iedereen in de admin panels mag 
include 'php/db.php'; // connectie met de database

if (!isset($_SESSION['username'])) { // controleert of de username niet bestaat
    header('Location: login.php'); // als de username niet  bestaat dan ga je naar login.php
    exit(); // de code stopt
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder_id'])) { // controleert of de request method precies gelijk is aan post en of de verwijder id bestaat
    $gerechtenVerwijderen = $databaseVerbinding->prepare("DELETE FROM gerechten WHERE id = :id"); // zet een query klaar die een bepaalde id van gerechten kan verwijderen er staat :id omdat deze later ingevult kan gaan worden
    $gerechtenVerwijderen->bindParam(':id', $_POST['verwijder_id']); // 
    $gerechtenVerwijderen->execute(); // hier voert hij de query uit
    header('Location: admin.php'); // ga naar admin.php
    exit(); // stop de code
}

$gerechtenOphalen = $databaseVerbinding->prepare("SELECT * FROM gerechten ORDER BY categorie"); // hier zet hij een query klaar die alle gerechten ophaalt zodat je die later in een lijst kan laten zien
$gerechtenOphalen->execute(); // hier voert hij de query uit
$alleGerechten = $gerechtenOphalen->fetchAll(); // hier zet hij alle gerechten in de allegerechten variabel
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Frietlust - Beheer</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/admin.css" />
  </head>
  <body>

    <header class="admin-header">
      <a href="index.php" class="admin-logo">Frietlust</a>
      <nav class="admin-nav">
        <a href="gerecht-toevoegen.php" class="admin-toevoegen-knop">+ Gerecht toevoegen</a>
        <a href="logout.php" class="admin-uitloggen-knop">Uitloggen</a>
      </nav>
    </header>

    <main class="admin-sectie">
      <h1 class="admin-titel">Beheerpaneel</h1>
      <table class="gerechten-tabel">
        <tr>
          <th>Naam</th>
          <th>Categorie</th>
          <th>Prijs</th>
          <th>Acties</th>
        </tr>
        <?php foreach ($alleGerechten as $gerecht): ?>
          <tr> 
            <td><?php echo htmlspecialchars($gerecht['naam']); ?></td> <!-- html specialchars beschermt je tegen -->
            <td><?php echo htmlspecialchars($gerecht['categorie']); ?></td> 
            <td>&euro; <?php echo number_format($gerecht['prijs'], 2, ',', '.'); ?></td> <!-- numberformat zorgt zegmaar ervoor dat het formatteerd naar geldwaardes en de 2 is dat er maximaal 2 decimalen zijn de , is voor honderdtallen en . voor duizendtallen -->
            <td>
              <a href="gerecht-bewerken.php?id=<?php echo $gerecht['id']; ?>">Bewerken</a>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="verwijder_id" value="<?php echo $gerecht['id']; ?>">
                <button type="submit" onclick="return confirm('Weet je het zeker?')">Verwijderen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </main>

    <footer class="footer">
      <p>&copy; 2024 <span>Frietlust</span> - Alle rechten voorbehouden</p>
    </footer>
  </body>
</html>
