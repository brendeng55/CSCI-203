<?php
$pagetitle = "Insert a book";
include_once "header.php";
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
//NECESSARY VARIABLES
$showform = 1;
$errormsg = "";
require_once "connect.php";

if(isset($_POST['submit'])) {
    /*  ****************************************************************************
                 CLEANSE DATA
     **************************************************************************** */

    $formfield['title'] = trim(strtolower($_POST['title']));
    $formfield['summary'] = trim($_POST['summary']);
    $formfield['afirst'] = trim($_POST['afirst']);
    $formfield['alast'] = trim($_POST['alast']);

    /*  ****************************************************************************
                CHECK FOR EMPTY REQUIED FIELDS
     **************************************************************************** */

    if (empty($formfield['title'])) {
        $errormsg .= "<p>Your title is empty.</p>";
    }
    if (empty($formfield['summary'])) {
        $errormsg .= "<p>Your summary is empty.</p>";
    }
    if (empty($formfield['afirst'])) {
        $errormsg .= "<p>Your author's first name is empty.</p>";
    }
    if (empty($formfield['alast'])) {
        $errormsg .= "<p>Your author's last name is empty.</p>";
    }


    /*  ****************************************************************************
     		CHECK FOR DUPLICATE TITLES
	**************************************************************************** */
    try
    {

        $sqlusers = 'SELECT * FROM bmgoldman_items WHERE title = :title';
        $stmtusers = $pdo->prepare($sqlusers);
        $stmtusers->bindValue(':title', $formfield['title']);
        $stmtusers->execute();
        $countusers = $stmtusers->rowCount();
        if ($countusers > 0)
        {
            $errormsg .= "<p>The title has already been listed.</p>";
        }
    }
        catch (PDOException $e)
        {
            echo 'Unable to fetch users. <br />' .$e->getMessage();
            exit();
        }
    /*  ****************************************************************************
            DISPLAY ERRORS
     **************************************************************************** */
    if ($errormsg != "") {
        echo "<div class='error'><p>THERE ARE ERRORS!</p>";
        echo $errormsg;
        echo "</div>";
    }else {

    /*  ****************************************************************************
                    INSERT INTO DATABASE TABLE
     **************************************************************************** */
    try
    {
        //enter data into database
        $sqlinsert = 'INSERT INTO bmgoldman_items (title, summary, afirst, alast) VALUES (:title, :summary, :afirst, :alast)';
        $stmtinsert = $pdo->prepare($sqlinsert);
        $stmtinsert->bindvalue(':title', $formfield['title']);
        $stmtinsert->bindvalue(':summary', $formfield['summary']);
        $stmtinsert->bindvalue(':afirst', $formfield['afirst']);
        $stmtinsert->bindvalue(':alast', $formfield['alast']);
        $stmtinsert->execute();
        //hide the form
        $showform = 0;
        echo "<div class='success'><p>There are no errors. Thank you.</p></div>";
    }//try
    catch (PDOException $e)
    {
        echo 'ERROR!!!' . $e->getMessage();
        exit();
    }//catch
}}
if($showform ==1) {
    ?>
    <form method="post" action="iteminsert.php" name="myform">
        <table>

            <tr>
                <th><label for="title">Title:</label></th>
                <td><input type="text" name="title" id="title" size="45" value="<?php echo $formfield['title']; ?>"/>
                </td>
            </tr>
            <tr>
                <th><label for="summary">Brief Summary:</label></th>
                <td><textarea name="summary" id="summary"><?php echo $formfield['summary']; ?></textarea></td>
            </tr>
            <tr>
                <th><label for="afirst">Author's first name</label></th>
                <td><input type="text" name="afirst" id="afirst" size="20" value="<?php echo $formfield['afirst']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="alast">Author's last name</label></th>
                <td><input type="text" name="alast" id="alast" size="30" value="<?php echo $formfield['alast']; ?>"/>
                </td>
            </tr>
            <tr>
                <th><label for="submit">Submit Form</label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>
        </table>
    </form>
    <?php
}
include_once "footer.php";
?>
