<?php
   // define('DB_SERVER', 'localhost:3036');
   // define('DB_USERNAME', 'root');
   // define('DB_PASSWORD', 'rootpassword');
   // define('DB_DATABASE', 'database');
   // $db = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'username', 'password');
    try {
        $dbh = new PDO('sqlite:db.sqlite3');
        // $dbh = new PDO('mysql:host=localhost;charset=utf8mb4')
        // $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
?>
