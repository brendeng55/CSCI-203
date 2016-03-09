<?php
$myheading = "Delete Book";
include 'header.php';
$errormsg = ""; //create a variable to store the error message.
$showform = 1;
echo '<p><a href="itemlist.php">RETURN TO ITEM LIST</a></p>';

//ONCE WE HAVE PRESSED SUBMIT, DO SOMETHING....
if(isset ($_POST['delete']) && $_POST['delete'] == "YES")
{

    try
    {
        $sql = 'DELETE FROM bmgoldman_items WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_POST['ID']); // using data from form
        $s->execute();
    }
    catch(PDOException $e)
    {
        echo 'Error deleting from database ' . $e->getMessage();
        exit();
    }
    //confirmation
    echo '<p>The item number: ' . $_POST['ID'] . ' has been deleted.</p>';
    $showform=0;
}

if($showform == 1)
{
    echo 'Are you sure you want to delete book No. ' . $_GET['ID'];

    try
    {
        $sql = 'SELECT * FROM bmgoldman_items WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_GET['ID']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }

    $row = $s->fetch();
    echo ' (' . $row['title'] . ')?';

    ?>

    <form name="itemdelete" id="itemdelete" method="post" action="itemdelete.php">
        <input type="hidden" name="ID" value="<?php echo $_GET['ID'];?>">
        <input type="submit" name="delete" value="YES">
        <input type="button" name="nodelete" value="NO" onClick="window.location = 'index.php'" />
    </form>

    <?php


}//showform
include "footer.php";
?>
