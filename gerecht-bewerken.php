<?php
session_start();
include 'php/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$gerechtenId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bewerken = $databaseVerbinding->prepare("UPDATE gerechten SET naam = :naam, beschrijving = :beschrijving, prijs = :prijs, categorie = :categorie WHERE id = :id");
    $bewerken->bindParam(':naam',         $_POST['naam']);
    $bewerken->bindParam(':beschrijving', $_POST['beschrijving']);
    $bewerken->bindParam(':prijs',        $_POST['prijs']);
    $bewerken->bindParam(':categorie',    $_POST['categorie']);
    $bewerken->bindParam(':id',           $gerechtenId);
    $bewerken->execute();
    header('Location: admin.php');
    exit();
}

$gerechtenOphalen = $databaseVerbinding->prepare("SELECT * FROM gerechten WHERE id = :id");
$gerechtenOphalen->bindParam(':id', $gerechtenId);
$gerechtenOphalen->execute();
$gerecht = $gerechtenOphalen->fetch();
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Frietlust - Gerecht Bewerken</title>
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
      <h1 class="admin-titel">Gerecht Bewerken</h1>
      <form method="POST" class="admin-form">

        <div class="form-groep">
          <label for="naam">Naam:</label>
          <input type="text" name="naam" id="naam" value="<?php echo htmlspecialchars($gerecht['naam']); ?>" required />
        </div>

        <div class="form-groep">
          <label for="beschrijving">Beschrijving:</label>
          <textarea name="beschrijving" id="beschrijving" required><?php echo htmlspecialchars($gerecht['beschrijving']); ?></textarea>
        </div>

        <div class="form-groep">
          <label for="prijs">Prijs (€):</label>
          <input type="number" step="0.01" name="prijs" id="prijs" value="<?php echo $gerecht['prijs']; ?>" required />
        </div>

        <div class="form-groep">
          <label for="categorie">Categorie:</label>
          <select name="categorie" id="categorie" required>
            <option value="Friet"   <?php echo $gerecht['categorie'] == 'Friet'   ? 'selected' : ''; ?>>Friet</option>
            <option value="Snacks"  <?php echo $gerecht['categorie'] == 'Snacks'  ? 'selected' : ''; ?>>Snacks</option>
            <option value="Burgers" <?php echo $gerecht['categorie'] == 'Burgers' ? 'selected' : ''; ?>>Burgers</option>
            <option value="Dranken" <?php echo $gerecht['categorie'] == 'Dranken' ? 'selected' : ''; ?>>Dranken</option>
          </select>
        </div>

        <button type="submit" class="btn-admin">Opslaan</button>
        <a href="admin.php" class="btn-admin secondary">Annuleren</a>
      </form>
    </main>

    <footer class="footer">
      <p>&copy; 2024 <span>Frietlust</span> - Alle rechten voorbehouden</p>
    </footer>
  </body>
</html>
