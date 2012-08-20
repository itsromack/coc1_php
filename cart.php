<?php
include_once("connect.php");
include_once("utils.php");

if(isset($_GET['act']) && isset($_GET['isbn']))
{
   	if ($_GET['act'] == 'add')
   	{
   		error_log('Adding ' . $isbn . ' to CART');
   		add_to_cart($_GET['isbn']);
   	}

   	if($_GET['act'] == 'remove')
   	{
   		error_log('Removed ' . $_GET['isbn'] . ' from CART');
   		remove_from_cart($_GET['isbn']);
   	}
}
else
{
	$_SESSION['message'] = 'Howdy, have a nice day! ';
}
header('Location: index.php');
?>