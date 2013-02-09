<?php
include_once("connect.php");
include_once("utils.php");

if(!empty($_GET['act'])) {

   	if ($_GET['act'] == 'add') {

         add_to_cart($_GET['isbn']);

      } elseif ($_GET['act'] == 'remove') {

         remove_from_cart($_GET['isbn']);

      } elseif ($_GET['act'] == 'Clear') {

         clear_book_cart();

      } elseif ($_GET['act'] == 'Reserve') {

         reserve_books();

      }

} else {

	$_SESSION['message'] = 'Howdy, have a nice day! ';

}

header('Location: index.php');
?>
