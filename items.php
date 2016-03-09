<h2>Our Books:</h2>
<?php
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
    echo '<tr><th>Title</th></tr>';
    foreach ($result as $row)
    {
        echo '<tr>
                    <td><a href="itemdetails.php?ID=' .$row['ID'] .'">'.$row['title'] . '</a></td>
              </tr>';
    }
    echo '</table>';

}//try
catch (PDOException $e)
{
    echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}
?>
