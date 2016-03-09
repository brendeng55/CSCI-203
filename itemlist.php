<?php
$myheading = "Item List";
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
    $sql = 'SELECT * FROM bmgoldman_items';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', $formfield['title']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<table>';
    echo '<tr><th>ID</th><th>Title</th><th colspan="3">Options</th></tr>';
    foreach ($result as $row)
    {
        echo '<tr>

                    <td>'.$row['ID'] . '</td>
                    <td>'.$row['title'] . '</td>
                    <td><a href="itemdetails.php?ID=' .$row['ID'] .'">Details</a></td>
                    <td><a href="itemupdate.php?ID=' .$row['ID'] .'">Update</a></td>
                    <td><a href="itemdelete.php?ID=' .$row['ID'] .'">Delete</a></td>
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
