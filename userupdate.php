<?php
$pagetitle = "Update a user";
include_once "header.php";

/*  ****************************************************************************
    CHECK TO SEE IF LOGGED IN
    ****************************************************************************
*/

if(!isset($_SESSION['userid']))
{
    echo $pagetitle;
    echo '<p>This page requires you to <a href="login.php">log in</a>.';
    include_once "footer.php";
    exit();
}

if($_SESSION['userid'] != $_GET['ID'] && $_SESSION['userid'] != $_POST['ID'])
{
    echo '<p>You can <em>only</em> update yourself.</p>';
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

    $formfield['uname'] = trim(strtolower($_POST['uname']));
    $formfield['email'] = trim(strtolower($_POST['email']));
    $formfield['firstn'] = trim($_POST['firstn']);
    $formfield['mn'] = trim($_POST['mn']);
    $formfield['lastn'] = trim($_POST['lastn']);
    $formfield['pwd'] = trim($_POST['pwd']);
    $formfield['pwd2'] = trim($_POST['pwd2']);
    $formfield['age'] = trim($_POST['age']);
    $formfield['nickname'] = trim($_POST['nickname']);
    $formfield['hobbies'] = trim($_POST['hobbies']);

    /*  ****************************************************************************
                 CHECK FOR EMPTY REQUIED FIELDS
      **************************************************************************** */

    if (empty($formfield['firstn'])) {
        $errormsg .= "<p>Your first name is empty.</p>";
    }
    if (empty($formfield['lastn'])) {
        $errormsg .= "<p>Your last name is empty.</p>";
    }
    if (empty($formfield['email'])) {
        $errormsg .= "<p>Your email is empty.</p>";
    }
    if (empty($formfield['uname'])) {
        $errormsg .= "<p>Your username is empty.</p>";
    }
    if (empty($formfield['age'])) {
        $errormsg .= "<p>Your age is empty.</p>";
    }
    if (empty($formfield['nickname'])) {
        $errormsg .= "<p>Your nickname is empty.</p>";
    }
    if (empty($formfield['hobbies'])) {
        $errormsg .= "<p>Your hobbies is empty.</p>";
    }

    /*  ****************************************************************************
        CHECK FOR DUPLICATE EMAILS
     **************************************************************************** */
    if ($formfield['email'] != $_POST['origemail'])
    {
        try
        {
            $sqlusers = 'SELECT * FROM bmgoldman_users WHERE email = :email';
            $stmtusers = $pdo->prepare($sqlusers);
            $stmtusers->bindValue(':email', $formfield['email']);
            $stmtusers->execute();
            $countusers = $stmtusers->rowCount();
            if ($countusers > 0)
            {
                $errormsg .= "<p>The email has already been registered.</p>";
            }
        } catch (PDOException $e)
        {
            echo 'Unable to fetch users. <br />' . $e->getMessage();
            exit();
        }
    }
    /*  ****************************************************************************
           CHECK FOR DUPLICATE USERNAMES
        **************************************************************************** */
    if ($formfield['uname'] != $_POST['origuname'])
    {
        try
        {

            $sqlusers = 'SELECT * FROM bmgoldman_users WHERE uname = :uname';
            $stmtusers = $pdo->prepare($sqlusers);
            $stmtusers->bindValue(':uname', $formfield['uname']);
            $stmtusers->execute();
            $countusers = $stmtusers->rowCount();
            if ($countusers > 0)
            {
                $errormsg .= "<p>The username has already been registered.</p>";
            }
        } catch (PDOException $e)
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
            $sqlupdate = 'UPDATE bmgoldman_users SET age = :age, email = :email, firstn = :firstn, hobbies = :hobbies, lastn = :lastn, mn = :mn, nickname = :nickname, uname = :uname WHERE ID = :ID';
            $stmtupdate = $pdo->prepare($sqlupdate);
            $stmtupdate->bindvalue(':age', $formfield['age']);
            $stmtupdate->bindvalue(':email', $formfield['email']);
            $stmtupdate->bindvalue(':firstn', $formfield['firstn']);
            $stmtupdate->bindvalue(':hobbies', $formfield['hobbies']);
            $stmtupdate->bindvalue(':lastn', $formfield['lastn']);
            $stmtupdate->bindvalue(':mn', $formfield['mn']);
            $stmtupdate->bindvalue(':nickname', $formfield['nickname']);
            $stmtupdate->bindvalue(':uname', $formfield['uname']);
            $stmtupdate->bindvalue(':ID', $_POST['ID']);
            $stmtupdate->execute();
            //hide the form
            $showform = 0;
            echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
            echo "<p>Back to <a href='userlist.php'>userlist</a>";
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
        $sql = 'SELECT * FROM bmgoldman_users WHERE ID = :ID';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch();

        //populate the form from the result set
        ?>
        <form method="post" action="userupdate.php" name="myform">
            <table>
                <tr>
                    <th><label for="firstn">First Name:</label></th>
                    <td><input type="text" name="firstn" id="firstn" size="25" value="<?php echo $row['firstn']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="mn">Middle Name:</label></th>
                    <td><input type="text" name="mn" id="mn" size="5" maxlength="1"
                               value="<?php echo $row['mn']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="lastn">Last Name</label></th>
                    <td><input type="text" name="lastn" id="lastn" value="<?php echo $row['lastn']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="email">Email</label></th>
                    <td><input type="email" name="email" id="email" size="40" value="<?php echo $row['email']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="uname">Username:</label></th>
                    <td><input type="text" name="uname" id="uname" size="20" value="<?php echo $row['uname']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="age">Age</label></th>
                    <td><input type="text" name="age" id="age" value="<?php echo $row['age']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="nickname">Nickname</label></th>
                    <td><input type="text" name="nickname" id="nickname" size="30"
                               value="<?php echo $row['nickname']; ?>"/></td>
                </tr>
                <tr>
                    <th><label for="hobbies">Hobbies:</label></th>
                    <td><textarea name="hobbies" id="hobbies"><?php echo $row['hobbies']; ?></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row['ID']; ?>"/>
                    <input type="hidden" name="origuname" id="origuname" value="<?php echo $row['uname']; ?>"/>
                    <input type="hidden" name="origemail" id="origemail" value="<?php echo $row['email']; ?>"/>
                    <th><label for="submit">Submit Form</label></th>
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