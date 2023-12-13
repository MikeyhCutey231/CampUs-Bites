<?php

$dsn = 'mysql:host=localhost;dbname=yawa';
$username = 'root';
$password = 'root';

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>