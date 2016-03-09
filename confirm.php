<?php
$pagetitle = "Login Confirmation";
require_once "header.php";
?>
    <p>Thank you for logging in!</p>
<table>
    <tr><th>User ID</th><td><?php echo $_SESSION['userid']; ?></td></tr>
    <tr><th>Username</th><td><?php echo $_SESSION['uname']; ?></td></tr></table>
<?php
require_once "footer.php";
?>