<?php
include_once("connect.php");
include_once("utils.php");

if(isset($_GET['act']) && isset($_GET['isbn']))
{
    $result = add_to_cart($_GET['isbn']);
   	echo ($result == true) ? $_SESSION['message'] : '';
}

$books = get_all_books();
?>

<h1>Books</h1>

<table border='1' cellpadding='7'>
	<tr>
		<th>ISBN</th>
		<th>Book Title</th>
		<th>Publisher</th>
		<th>Author</th>
		<th>Copyright</th>
		<? if($_SESSION['is_logged_in'] === TRUE): ?>
		<th>Reserve</th>
		<? endif; ?>
	</tr>

	<? foreach($books as $book): ?>
	<tr>
		<td><?=$book['isbn']?></td>
		<td><?=$book['title']?></td>
		<td><?=$book['publisher']?></td>
		<td><?=$book['author']?></td>
		<td><?=$book['copyright']?></td>
		<? if($_SESSION['is_logged_in'] === TRUE): ?>
		<td>
			<a href='index.php?isbn=<?=$book['isbn']?>&act=reserve'>Reserve</a>
		</td>
		<? endif; ?>
	</tr>
	<? endforeach; ?>

</table>