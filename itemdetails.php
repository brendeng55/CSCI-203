<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>Item Details</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php
require_once "connect.php";
include "header.php";

try
{
    $sql = 'SELECT * FROM bmgoldman_items WHERE ID = :ID';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $_GET['ID']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<table>';
    foreach ($result as $row)
    {
        echo    '   <tr><td><th>ID</th></td><td>'.$row['ID'] . '</td></tr>
					<tr><td><th>Title</th></td><td>'.$row['title'] . '</td></tr>
					<tr><td><th>Summary</th></td><td>'.$row['summary'] . '</td></tr>
					<tr><td><th>Author\'s First Name</th></td><td>'.$row['afirst'] . '</td></tr>
					<tr><td><th>Author\'s Last Name</th></td><td>'.$row['alast'] . '</td></tr>';
    }
    echo '</table>';


}//try
catch (PDOException $e)
{
    echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}
include "footer.php";
?>
</body>
</html>

