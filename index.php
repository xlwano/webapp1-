<?php 
    $host = 'db';
    $db = 'mydatabase';
    $user = 'user';
    $password = 'password';
    $charset = 'utf8mb4';

    // pdo opties
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // dsn 
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    try { 
        // create the connection
        $pdo = new PDO($dsn, $user, $password, $options);
        // succes melding
        echo "Database connectie gelukt <br/>";
    } catch (PDOException $e) {
        // foutmelding
        echo $e->getMessage();
        // stop (die)
        die("Sorry, database probleem");
    }


    // define sql statement
    $sql = "SELECT * FROM studenten";

    // prepare the statement
    $statement = $pdo->prepare($sql);

    // exectute sql statement 
    $statement->execute();

    $studenten = $statement->fetchAll();

    // echo "<pre>";
    // print_r($studenten); 
    // echo "</pre>";