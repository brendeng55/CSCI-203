<?php
/* CREATE A CONNECTION TO THE SERVER */
$dsn = 'mysql:host=localhost;dbname=csci20301fa15';
$user = 'csci20301fa15';
$pwd = 'csci20301fa15!';
try{
    $pdo = new PDO($dsn,$user,$pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo 'ERROR connecting to database!' . $e->getMessage();
    exit();
}
?>



