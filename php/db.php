<?php
// databaseverbinding met PDO
$host = 'db'; // database server
$dbname = 'webapp'; // database naam
$username = 'user'; // gebruikersnaam
$password = 'password'; // wachtwoord

try { // probeer verbinding te maken
    $databaseVerbinding = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password); // maak PDO verbinding
    $databaseVerbinding->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // zet foutmodus aan
} catch (PDOException $e) { // als het misgaat
    die("Database connection failed: " . $e->getMessage()); // stop en toon fout
}
?>
