<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "painel";

$conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);