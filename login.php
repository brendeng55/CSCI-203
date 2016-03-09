<?php
$usertable = "bmgoldman_users"; // ONLY MODIFY THIS LINE
$pagetitle = "Login Confirmation";
require_once "header.php";
$showform = 1;
$errormsg = "";
error_reporting(E_ALL); ini_set('display_errors', 'On');

if(isset($_SESSION['userid']))
{
    echo "<p class='error'>You are already logged in.</p>";
    include_once 'footer.php';
    exit();
}

if(isset ($_POST['submit']))
{

    //CLEANSE DATA THE SAME AS THE REGISTRATION PAGE
    $formfield['uname'] = strtolower(trim($_POST['uname']));
    $formfield['pwd'] = trim($_POST['pwd']);

    //CHECKING FOR EMPTY FIELDS THE SAME AS THE REGISTRATION PAGE
    if (empty($formfield['uname'])){$errormsg .= "<p class='error'>USERNAME IS MISSING.</p>";}
    if (empty($formfield['pwd'])){$errormsg .= "<p class='error'>PASSWORD IS MISSING.</p>";}


    //display error

    if($errormsg !="")
    {
        echo "<p class='error'>There are errors:  <br> " . $errormsg . "</p>";
    }
    else
    {
        //GET THE USER AND SALT FROM THE DATABASE
        try
        {
            $sqllogin = "SELECT * FROM " . $usertable . " WHERE uname = :uname";
            $slogin = $pdo->prepare($sqllogin);
            $slogin->bindValue(':uname', $formfield['uname']);
            $slogin->execute();
            $rowlogin = $slogin->fetch();
            $countlogin = $slogin->rowCount();

            //if query okay, see if there is a result
            if ($countlogin < 1)
            {
                echo  "<p class='error'>This user cannot be found.</p>";
            }
            else
            {
                $confirmeduname = $rowlogin['uname'];
                $confirmedsalt = $rowlogin['salt'];
                //rehash password using unique salt from dbase for the user and passoword from form
                $securepwd = crypt($formfield['pwd'],$rowlogin['salt']);
                if ($securepwd != $rowlogin['pwd'])
                {
                    echo "<p class='error'>The uname and password combination you entered is not correct.  Please try again.</p>";
                }
                else
                {
                    $_SESSION['userid']= $rowlogin['ID'];
                    $_SESSION['uname'] = $rowlogin['uname'];
                    $showform = 0;
                    //once logged in and session vars populated, redirect to confirmation page.
                    header("Location: confirm.php");
                }
            }//username exists
        }
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }


    }//else errormessage

}//ifisset

if($showform ==1)
{
    ?>
    <p>You are not logged in.  Please log in to access restricted areas.</p>
    <form name="loginForm" id="loginForm" method="post" action="login.php">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="uname" id="uname" size="20" value="<?php if(isset($formfield['uname'])){echo $formfield['uname'];} ?>"/>
                </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="pwd" id="pwd" size="20" /></td>
            </tr>
            <td>Submit:</td>
            <td><input type="submit" name="submit" value="submit" /></td>
            </tr>
        </table>
    </form>
    <?php
}//showform
include_once 'footer.php';
?>