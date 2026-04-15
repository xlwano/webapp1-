<?php
session_start(); 
include 'php/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: login.php"); 
    exit; 
}

$message = ''; // bericht voor gebruiker
$gerechten = []; // lijst met gerechten
$gerecht_bewerken = null; // gerecht dat wordt bewerkt

// SAVE - Gerecht toevoegen of bewerken
if (isset($_POST['save_gerecht'])) { // als save-knop gedrukt
    $id = !empty($_POST['id']) ? intval($_POST['id']) : null; // haal id op (kan leeg zijn)
    $naam = trim($_POST['naam']); // haal naam op
    $beschrijving = trim($_POST['beschrijving']); // haal beschrijving op
    $prijs = floatval($_POST['prijs']); // haal prijs op als getal
    $categorie = trim($_POST['categorie']); // haal categorie op

    if ($id) { // als id aanwezig = bewerken
        $stmt = $databaseVerbinding->prepare("UPDATE gerechten SET naam = ?, beschrijving = ?, prijs = ?, categorie = ? WHERE id = ?");
        $stmt->execute([$naam, $beschrijving, $prijs, $categorie, $id]);
        $message = "✓ Gerecht bijgewerkt!";
    } else { // anders = toevoegen
        $stmt = $databaseVerbinding->prepare("INSERT INTO gerechten (naam, beschrijving, prijs, categorie) VALUES (?, ?, ?, ?)");
        $stmt->execute([$naam, $beschrijving, $prijs, $categorie]);
        $message = "✓ Gerecht toegevoegd!";
    }
}

// DELETE - Gerecht verwijderen
if (isset($_POST['delete_gerecht'])) { // als verwijderen-knop gedrukt
    $id = intval($_POST['id']); // haal id op als getal
    $stmt = $databaseVerbinding->prepare("DELETE FROM gerechten WHERE id = ?"); // bereid delete query voor
    $stmt->execute([$id]); // voer delete uit
    $message = "✓ Gerecht verwijderd!"; // succes bericht
}

// Controleer of je een gerecht wilt bewerken
if (isset($_GET['edit'])) { // als edit parameter in url
    $id = intval($_GET['edit']); // haal id op als getal
    $stmt = $databaseVerbinding->prepare("SELECT * FROM gerechten WHERE id = ?"); // bereid select query voor
    $stmt->execute([$id]); // voer select uit
    $gerecht_bewerken = $stmt->fetch(); // haal gerecht op
}

// READ - Haal alle gerechten op
$stmt = $databaseVerbinding->query("SELECT * FROM gerechten ORDER BY categorie, naam"); // haal alle gerechten op gesorteerd
$gerechten = $stmt->fetchAll(); // haal resultaten op
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gerechten Beheren</title>
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
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="sectie sectie-licht">
        <div class="container admin-dashboard">
            <h1>Admin - Gerechten Beheren</h1>
            <p>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulier om gerecht toe te voegen -->
            <div class="admin-section">
                <h2><?php echo $gerecht_bewerken ? 'Gerecht Bewerken' : 'Nieuw Gerecht Toevoegen'; ?></h2>
                <form method="post" class="admin-form">
                    <?php if ($gerecht_bewerken): ?>
                        <input type="hidden" name="id" value="<?php echo $gerecht_bewerken['id']; ?>">
                    <?php endif; ?>

                    <div class="form-groep">
                        <label for="naam">Naam:</label>
                        <input type="text" name="naam" id="naam" value="<?php echo $gerecht_bewerken ? htmlspecialchars($gerecht_bewerken['naam']) : ''; ?>" required>
                    </div>

                    <div class="form-groep">
                        <label for="beschrijving">Beschrijving:</label>
                        <textarea name="beschrijving" id="beschrijving" required><?php echo $gerecht_bewerken ? htmlspecialchars($gerecht_bewerken['beschrijving']) : ''; ?></textarea>
                    </div>

                    <div class="form-groep">
                        <label for="prijs">Prijs (€):</label>
                        <input type="number" step="0.01" name="prijs" id="prijs" value="<?php echo $gerecht_bewerken ? $gerecht_bewerken['prijs'] : ''; ?>" required>
                    </div>

                    <div class="form-groep">
                        <label for="categorie">Categorie:</label>
                        <select name="categorie" id="categorie" required>
                            <option value="Friet" <?php echo ($gerecht_bewerken && $gerecht_bewerken['categorie'] == 'Friet') ? 'selected' : ''; ?>>Friet</option>
                            <option value="Snacks" <?php echo ($gerecht_bewerken && $gerecht_bewerken['categorie'] == 'Snacks') ? 'selected' : ''; ?>>Snacks</option>
                            <option value="Burgers" <?php echo ($gerecht_bewerken && $gerecht_bewerken['categorie'] == 'Burgers') ? 'selected' : ''; ?>>Burgers</option>
                            <option value="Dranken" <?php echo ($gerecht_bewerken && $gerecht_bewerken['categorie'] == 'Dranken') ? 'selected' : ''; ?>>Dranken</option>
                        </select>
                    </div>

                    <button type="submit" name="save_gerecht" class="btn-admin"><?php echo $gerecht_bewerken ? 'Bijwerken' : 'Toevoegen'; ?></button>
                    <?php if ($gerecht_bewerken): ?>
                        <a href="admin.php" class="btn-admin secondary">Annuleren</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Tabel met bestaande gerechten -->
            <div class="admin-section">
                <h2>Bestaande Gerechten</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naam</th>
                            <th>Beschrijving</th>
                            <th>Prijs</th>
                            <th>Categorie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gerechten as $gerecht): ?>
                        <tr>
                            <td><?php echo $gerecht['id']; ?></td>
                            <td><?php echo htmlspecialchars($gerecht['naam']); ?></td>
                            <td><?php echo htmlspecialchars($gerecht['beschrijving']); ?></td>
                            <td>€ <?php echo number_format($gerecht['prijs'], 2, ',', ''); ?></td>
                            <td><?php echo htmlspecialchars($gerecht['categorie']); ?></td>
                            <td>
                                <!-- Link om te bewerken -->
                                <a href="?edit=<?php echo $gerecht['id']; ?>" class="btn-admin secondary">Bewerken</a>

                                <!-- Formulier om te verwijderen -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $gerecht['id']; ?>">
                                    <button type="submit" name="delete_gerecht" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?')" class="btn-admin danger">Verwijderen</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </section>
</body>
</html>
