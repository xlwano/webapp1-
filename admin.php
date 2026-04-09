<?php
// Controleer of gebruiker ingelogd is EN admin is
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';
$gerechten = [];

try {
    // Als het formulier wordt ingestuurd (POST request)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Gerecht toevoegen
        if (isset($_POST['add_gerecht'])) {
            $naam = trim($_POST['naam']);
            $beschrijving = trim($_POST['beschrijving']);
            $prijs = floatval($_POST['prijs']);
            $categorie = trim($_POST['categorie']);

            $stmt = $pdo->prepare("INSERT INTO gerechten (naam, beschrijving, prijs, categorie) VALUES (?, ?, ?, ?)");
            $stmt->execute([$naam, $beschrijving, $prijs, $categorie]);
            $message = "✓ Gerecht toegevoegd!";
        }

        // Gerecht bewerken
        elseif (isset($_POST['edit_gerecht'])) {
            $id = intval($_POST['id']);
            $naam = trim($_POST['naam']);
            $beschrijving = trim($_POST['beschrijving']);
            $prijs = floatval($_POST['prijs']);
            $categorie = trim($_POST['categorie']);

            $stmt = $pdo->prepare("UPDATE gerechten SET naam = ?, beschrijving = ?, prijs = ?, categorie = ? WHERE id = ?");
            $stmt->execute([$naam, $beschrijving, $prijs, $categorie, $id]);
            $message = "✓ Gerecht bijgewerkt!";
        }

        // Gerecht verwijderen
        elseif (isset($_POST['delete_gerecht'])) {
            $id = intval($_POST['id']);
            $stmt = $pdo->prepare("DELETE FROM gerechten WHERE id = ?");
            $stmt->execute([$id]);
            $message = "✓ Gerecht verwijderd!";
        }
    }

    // Haal alle gerechten op uit de database
    $stmt = $pdo->query("SELECT * FROM gerechten ORDER BY categorie, naam");
    $gerechten = $stmt->fetchAll();

} catch (PDOException $e) {
    $message = "Database fout: " . $e->getMessage();
}
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
                <h2>Nieuw Gerecht Toevoegen</h2>
                <form method="post" class="admin-form">
                    <div class="form-groep">
                        <label for="naam">Naam:</label>
                        <input type="text" name="naam" id="naam" required>
                    </div>

                    <div class="form-groep">
                        <label for="beschrijving">Beschrijving:</label>
                        <textarea name="beschrijving" id="beschrijving" required></textarea>
                    </div>

                    <div class="form-groep">
                        <label for="prijs">Prijs (€):</label>
                        <input type="number" step="0.01" name="prijs" id="prijs" required>
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

                    <button type="submit" name="add_gerecht" class="btn-admin">Toevoegen</button>
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
