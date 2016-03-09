<?php
/**
 * Created by PhpStorm.
 * User: Brenden
 * Date: 2/9/16
 * Time: 6:26 PM
 */

require_once "connect.php";


$sql = "SELECT firstn FROM bmgoldman_users";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':fname', $formfield['fname']);
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row)
{
    echo $row['fname'];
}
?>

