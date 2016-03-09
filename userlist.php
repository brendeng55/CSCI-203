<?php
$myheading = "User List";
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

try{
    $sql = 'SELECT * FROM bmgoldman_users';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uname', $formfield['uname']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th colspan="2">Options</th></tr>';
    foreach ($result as $row)
    {
        echo '<tr>

                    <td>'.$row['ID'] . '</td>
                    <td>'.$row['uname'] . '</td>
                    <td><a href="userdetails.php?ID=' .$row['ID'] .'">Details</a></td>
                    <td><a href="userupdate.php?ID=' .$row['ID'] .'">Update</a></td>
              </tr>';
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


