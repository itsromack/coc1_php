<?php
include_once("../connect.php");
include_once("../utils.php");
auth();
$fullname = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
?>

<div style="background-color:lightblue; padding: 10px; position:absolute; top: 0; left: 750;">
Welcome <b><?= $fullname ?> (<i style='color:red;'><?= $_SESSION['user']['username'] ?></i>)</b>! 
<a href='../login.php?logout=1'>Log out</a>
</div>

<?php include_once("admin_menu.php") ?>