<?php
try {
    require 'config.php';

    $zoek = isset($_GET['zoek']) ? trim($_GET['zoek']) : '';
    $query = "SELECT * FROM gerechten";
    if ($zoek) {
        $query .= " WHERE naam LIKE :zoek";
    }
    $query .= " ORDER BY FIELD(categorie, 'Friet', 'Snacks', 'Burgers', 'Dranken'), naam";
    $stmt = $pdo->prepare($query);
    if ($zoek) {
        $stmt->bindValue(':zoek', '%' . $zoek . '%');
    }
    $stmt->execute();
    $gerechten = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categorieen = [];
    foreach ($gerechten as $gerecht) {
        $categorieen[$gerecht['categorie']][] = $gerecht;
    }
} catch (PDOException $e) {
    $gerechten = [];
    $categorieen = [];
    $db_error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Frietlust</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- HEADER -->
    <header class="header vast" id="header">
        <div class="header-inner">
            <a href="index.php" class="logo">Friet<span>lust</span></a>
            <nav class="nav" id="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php" class="actief">Menu</a></li>
                    <li><a href="over-ons.html">Over Ons</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="login.php" class="btn-beheer">Beheer</a></li>
                </ul>
            </nav>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </div>
    </header>

    <!-- PAGINA HERO -->
    <div class="pagina-hero">
        <div class="pagina-hero-bg"></div>
        <div class="pagina-hero-inhoud">
            <span class="label">Lekker & Vers</span>
            <h1>Onze Menukaart</h1>
            <p>Dagelijks vers bereid met de beste ingredienten</p>
        </div>
    </div>

    <!-- ZOEKBALK -->
    <div class="zoek-sectie">
        <form class="zoek-formulier" action="menu.php" method="GET">
            <input type="text" name="zoek" placeholder="Zoek een gerecht...">
            <button type="submit">Zoeken</button>
        </form>
    </div>

    <!-- MENU -->
    <section class="sectie sectie-licht">
        <div class="container">
            <?php if (isset($db_error)): ?>
                <p>Database fout: <?php echo htmlspecialchars($db_error); ?></p>
            <?php else: ?>
                <?php foreach ($categorieen as $cat => $items): ?>
                    <?php
                    $icon = '';
                    $class = '';
                    switch ($cat) {
                        case 'Friet': $icon = '&#127839;'; $class = 'friet'; break;
                        case 'Snacks': $icon = '&#127829;'; $class = 'snack'; break;
                        case 'Burgers': $icon = '&#127828;'; $class = 'burger'; break;
                        case 'Dranken': $icon = '&#127867;'; $class = 'drank'; break;
                    }
                    ?>
                    <div class="menu-categorie">
                        <div class="categorie-titel">
                            <h3><?php echo $icon; ?> <?php echo htmlspecialchars($cat); ?></h3>
                            <div class="lijn"></div>
                        </div>
                        <div class="menu-items-grid">
                            <?php foreach ($items as $item): ?>
                                <?php $prijs = number_format($item['prijs'], 2, ',', ''); ?>
                                <div class="menu-item">
                                    <div class="menu-item-icon <?php echo $class; ?>"><?php echo $icon; ?></div>
                                    <div class="menu-item-info">
                                        <h4><?php echo htmlspecialchars($item['naam']); ?></h4>
                                        <p><?php echo htmlspecialchars($item['beschrijving']); ?></p>
                                    </div>
                                    <span class="menu-item-prijs">&euro; <?php echo $prijs; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="logo">Friet<span>lust</span></a>
                    <p>Frietlust staat voor vers, zelfgemaakt eten in het hart van Nijmegen. Opgericht door Lano Ockers in 2026.</p>
                </div>
                <div class="footer-kolom">
                    <h4>Navigatie</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="over-ons.html">Over Ons</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-kolom">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="menu.php">Friet</a></li>
                        <li><a href="menu.php">Snacks</a></li>
                        <li><a href="menu.php">Burgers</a></li>
                        <li><a href="menu.php">Dranken</a></li>
                    </ul>
                </div>
                <div class="footer-kolom">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="#">Hoekstraat 12, Nijmegen</a></li>
                        <li><a href="#">024-123 4567</a></li>
                        <li><a href="#">info@frietlust.nl</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <span>&copy; 2026 Frietlust. Alle rechten voorbehouden.</span>
                <span>Gemaakt met liefde in Nijmegen</span>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>

</body>
</html>
