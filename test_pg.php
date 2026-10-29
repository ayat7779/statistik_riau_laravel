<?php
$passwords = ['', 'postgres', '123!', 'password', 'admin'];
foreach ($passwords as $pwd) {
    try {
        $dsn = "pgsql:host=localhost;port=5432";
        $pdo = new PDO($dsn, 'postgres', $pwd);
        echo "Successfully connected with password: '" . $pwd . "'\n";
        exit(0);
    } catch (PDOException $e) {
        // try next
    }
}
echo "Could not connect with common passwords.\n";
