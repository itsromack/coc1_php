<?php
if(isset($_GET['act']) && isset($_GET['isbn']))
{
	$result = ($_GET['act'] == 'add') ? add_to_cart($_GET['isbn']) : false;
   	echo ($result == true) ? $_SESSION['message'] : '';

   	if ($_GET['act'] == 'add')
   	{
   		error_log('Adding ' . $isbn . ' to CART');
   		add_to_cart($_GET['isbn']);
   	}
   	elseif($_GET['act'] == 'remove')
   	{
   		error_log('Removed ' . $isbn . ' from CART');
   		remove_from_cart($_GET['isbn']);
   	}
}
else
{
	$_SESSION['message'] = 'Howdy, have a nice day! ';
}
header('Location: index.php');
?>