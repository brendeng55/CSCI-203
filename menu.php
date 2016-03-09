<nav>
<ul>

    <li><a href="userinsert.php">Add New User</a></li>
    <?php
    if(!isset($_SESSION['userid']))
    {
        echo '<li><a href="login.php">Log In</a></li>';
    }

    if(isset($_SESSION['userid']))
    {
        echo '<li><a href="logout.php">Log out</a></li>
              <li><a href="userlist.php">User List</a></li>
              <li><a href="itemlist.php">Item List</a></li>
              <li><a href="iteminsert.php">Add New Item</a></li>';
    }

    ?>

</ul>
</nav>