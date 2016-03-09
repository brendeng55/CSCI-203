<?php
$pagetitle = "Add A User";
include_once "header.php";

//NECESSARY VARIABLES
$inputdate = time();
$showform = 1;
$errormsg = "";
require_once "connect.php";

if(isset($_POST['submit'])) {
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
    if (empty($formfield['pwd'])) {
        $errormsg .= "<p>Your password is empty.</p>";
    }
    if (empty($formfield['pwd2'])) {
        $errormsg .= "<p>Your confirm password is empty.</p>";
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
     		COMPARE PASSWORDS
	**************************************************************************** */
    if($formfield['pwd'] != $formfield['pwd2']) {$errormsg .= "<p>Passwords do not match</p>";}

    /*  ****************************************************************************
     		CHECK FOR DUPLICATE EMAILS
	**************************************************************************** */
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
    } else {
        /*  ****************************************************************************
        HASH THE PASSWORD
        **************************************************************************** */
        // Creates a string of 22 of characters/digits/symbols in the substring shown.
        for ($i = 0; $i < 22; $i++) {
            $char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
        }

        $salt = '$2a$07$' . $char22;
        $securepwd = crypt($formfield['pwd'], $salt);

    }
    /*  ****************************************************************************
                    INSERT INTO DATABASE TABLE
     **************************************************************************** */
    try
    {
        //enter data into database
        $sqlinsert = 'INSERT INTO bmgoldman_users (inputdate, age, email, firstn, hobbies, id, lastn, mn, nickname, pwd, salt, uname) VALUES (:inputdate, :age, :email, :firstn, :hobbies, :id, :lastn, :mn, :nickname, :pwd, :salt, :uname)';
        $stmtinsert = $pdo->prepare($sqlinsert);
        $stmtinsert->bindvalue(':inputdate', $_POST['inputdate']);
        $stmtinsert->bindvalue(':age', $formfield['age']);
        $stmtinsert->bindvalue(':email', $formfield['email']);
        $stmtinsert->bindvalue(':firstn', $formfield['firstn']);
        $stmtinsert->bindvalue(':hobbies', $formfield['hobbies']);
        $stmtinsert->bindvalue(':id', $formfield['id']);
        $stmtinsert->bindvalue(':lastn', $formfield['lastn']);
        $stmtinsert->bindvalue(':mn', $formfield['mn']);
        $stmtinsert->bindvalue(':nickname', $formfield['nickname']);
        $stmtinsert->bindvalue(':salt', $salt);
        $stmtinsert->bindvalue(':pwd', $securepwd);
        $stmtinsert->bindvalue(':uname', $formfield['uname']);
        $stmtinsert->execute();
        //hide the form
        $showform = 0;
        echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
    }//try
    catch (PDOException $e)
    {
        echo 'ERROR!!!' . $e->getMessage();
        exit();
    }//catch
}

if($showform ==1) {
    ?>
    <form method="post" action="userinsert.php" name="myform">
        <table>
            <tr>
                <th><label for="inputdate">Current Time:</label></th>
                <td><? echo date("M, d, Y h:m:s", $inputdate); ?>
                    <input type="hidden" name="inputdate" id="inputdate" value="<?php echo $inputdate; ?>"/></td>
            </tr>
            <tr>
                <th><label for="firstn">First Name:</label></th>
                <td><input type="text" name="firstn" id="firstn" size="25" value="<?php echo $formfield['firstn']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="mn">Middle Name:</label></th>
                <td><input type="text" name="mn" id="mn" size="5" maxlength="1"
                           value="<?php echo $formfield['mn']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="lastn">Last Name</label></th>
                <td><input type="text" name="lastn" id="lastn" value="<?php echo $formfield['lastn']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="email">Email</label></th>
                <td><input type="email" name="email" id="email" size="40" value="<?php echo $formfield['email']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="uname">Username:</label></th>
                <td><input type="text" name="uname" id="uname" size="20" value="<?php echo $formfield['uname']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="pwd">Password:</label></th>
                <td><input type="password" name="pwd" id="pwd" size="30" value="<?php echo $formfield['pwd']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="pwd2">Confirm password:</label></th>
                <td><input type="password" name="pwd2" id="pwd2" maxlength="30" size="30"/></td>
            </tr>
            <tr>
                <th><label for="age">Age</label></th>
                <td><input type="text" name="age" id="age" value="<?php echo $formfield['age']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="nickname">Nickname</label></th>
                <td><input type="text" name="nickname" id="nickname" size="30"
                           value="<?php echo $formfield['nickname']; ?>"/></td>
            </tr>
            <tr>
                <th><label for="hobbies">Hobbies:</label></th>
                <td><textarea name="hobbies" id="hobbies"><?php echo $formfield['hobbies']; ?></textarea></td>
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
