<?php
$pagetitle = "Update an item";
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
        $_GET['ID'] = $_POST['ID'];

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
        if ($formfield['title'] != $_POST['origtitle']) {
            try {

                $sqlusers = 'SELECT * FROM bmgoldman_items WHERE title = :title';
                $stmtusers = $pdo->prepare($sqlusers);
                $stmtusers->bindValue(':title', $formfield['title']);
                $stmtusers->execute();
                $countusers = $stmtusers->rowCount();
                if ($countusers > 0) {
                    $errormsg .= "<p>The title has already been listed.</p>";
                }
            }
    catch
        (PDOException $e)
        {
            echo 'Unable to fetch users. <br />' . $e->getMessage();
            exit();
        }
}

        /*  ****************************************************************************
        DISPLAY ERRORS
        **************************************************************************** */
        if($errormsg != "")
        {
            echo "<div class='error'><p>THERE ARE ERRORS!</p>";
            echo $errormsg;
            echo "</div>";
        }
        else {
            /*  ****************************************************************************
            INSERT INTO DATABASE TABLE
            **************************************************************************** */
            try {
                //enter data into database
                $sqlupdate = 'UPDATE bmgoldman_items SET title = :title, summary = :summary, afirst = :afirst, alast = :alast WHERE ID = :ID';
                $stmtupdate = $pdo->prepare($sqlupdate);
                $stmtupdate->bindvalue(':title', $formfield['title']);
                $stmtupdate->bindvalue(':summary', $formfield['summary']);
                $stmtupdate->bindvalue(':afirst', $formfield['afirst']);
                $stmtupdate->bindvalue(':alast', $formfield['alast']);
                $stmtupdate->bindvalue(':ID', $_POST['ID']);
                $stmtupdate->execute();
                //hide the form
                $showform = 0;
                echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
                echo "<p>Back to <a href='itemlist.php'>itemupdate</a>";
            }//try
            catch (PDOException $e) {
                echo "<p class= 'error'>THERE ARE ERRORS! REPOPULATING FORM WITH ORIGINAL VALUES.</p>";
                echo 'ERROR!!!' . $e->getMessage();
                exit();
            }//catch
        }//else errors
    }//if submit

	if($showform ==1) {
        try {
            $sql = 'SELECT * FROM bmgoldman_items WHERE ID = :ID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':ID', $_GET['ID']);
            $stmt->execute();
            $row = $stmt->fetch();

            //populate the form from the result set
            ?>
            <form method="post" action="itemupdate.php" name="myform">
                <table>

                    <tr>
                        <th><label for="title">Title:</label></th>
                        <td><input type="text" name="title" id="title" size="45" value="<?php echo $row['title']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="summary">Brief Summary:</label></th>
                        <td><textarea name="summary" id="summary"><?php echo $row['summary']; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="afirst">Author's first name</label></th>
                        <td><input type="text" name="afirst" id="afirst" size="20" value="<?php echo $row['afirst']; ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="alast">Author's last name:</label></th>
                        <td><input type="text" name="alast" id="alast" size="30" value="<?php echo $row['alast']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="submit">Submit Form</label></th>
                        <input type="hidden" name="origtitle" id="origtitle" value="<?php echo $row['title']; ?>"/>
                        <input type="hidden" name="ID" id="ID" value="<?php echo $row['ID']; ?>"/>
                        <td><input type="submit" name="submit" id="submit" value="submit"/></td>
                    </tr>
                </table>
            </form>
            <?php
        }//try
        catch (PDOException $e) {
            echo 'Error fetching users: <br />ERROR MESSAGE:<br />' . $e->getMessage();
            exit();
        }
    }//if showform

include_once "footer.php";
?>