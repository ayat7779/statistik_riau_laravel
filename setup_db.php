<?php
$host = 'localhost';
$user = 'postgres';
$pass = '123!';

try {
    // 1. Connect to postgres database to create stat_provinsi_riau if not exists
    $pdo = new PDO("pgsql:host=$host;dbname=postgres", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if database exists
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'stat_provinsi_riau'");
    if (!$stmt->fetch()) {
        $pdo->exec("CREATE DATABASE stat_provinsi_riau");
        echo "Database 'stat_provinsi_riau' created successfully.\n";
    } else {
        echo "Database 'stat_provinsi_riau' already exists.\n";
    }
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    // 2. Connect to stat_provinsi_riau database and import SQL
    $pdoDb = new PDO("pgsql:host=$host;dbname=stat_provinsi_riau", $user, $pass);
    $pdoDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents('stat_provinsi_riau_postgres.sql');

    // Split into individual statements or execute
    // PostgreSQL can execute multiple statements separated by semicolon in PDO exec
    $pdoDb->exec($sql);
    echo "SQL script imported successfully into PostgreSQL database 'stat_provinsi_riau'.\n";

} catch (PDOException $e) {
    echo "Error importing SQL: " . $e->getMessage() . "\n";
    exit(1);
}
