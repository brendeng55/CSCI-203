
<?php
require_once "connect.php";
include "header.php";
/*  ****************************************************************************
    CHECK TO SEE IF LOGGED IN
    ****************************************************************************
*/
if(!isset($_SESSION['userid']))
{
    echo '<p>This page requires you to <a href="login.php">log in</a>.';
    include_once "footer.php";
    exit();
}
try
{
    $sql = 'SELECT * FROM bmgoldman_users WHERE ID = :ID';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $_GET['ID']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<table>';
    foreach ($result as $row)
    {
        echo    '   <tr><td><th>ID</th></td><td>'.$row['ID'] . '</td></tr>
					<tr><td><th>First Name</th></td><td>'.$row['firstn'] . '</td></tr>
					<tr><td><th>Middle Initial</th></td><td>'.$row['mn'] . '</td></tr>
					<tr><td><th>Last Name</th></td><td>'.$row['lastn'] . '</td></tr>
					<tr><td><th>User Name</th></td><td>'.$row['uname'] . '</td></tr>
					<tr><td><th>Email</th></td><td>'.$row['email'] . '</td></tr>
					<tr><td><th>Age</th></td><td>'.$row['age'] . '</td></tr>
					<tr><td><th>Nickname</th></td><td>'.$row['nickname'] . '</td></tr>
					<tr><td><th>Hobbies</th></td><td>'.$row['hobbies'] . '</td></tr>';
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


