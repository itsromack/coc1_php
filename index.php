<?php
include_once("connect.php");
include_once("utils.php");

$books = get_all_books();

include_once("index_login.php");

echo "<span style='background-color:pink; padding: 10px;'>{$_SESSION['message']}</span>";

include_once("index_cart.php");
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

	<? $row = 1 ?>
	<? foreach($books as $book): ?>
	<tr <? if($row++ % 2 != 0) echo 'style="background-color:silver"';?>>
		<td><?=$book['isbn']?></td>
		<td><?=$book['title']?></td>
		<td><?=$book['publisher']?></td>
		<td><?=$book['author']?></td>
		<td><?=$book['copyright']?></td>
		<? if($_SESSION['is_logged_in'] === TRUE): ?>
		<td>
			<? if(is_in_cart($book['isbn'])): ?>
			Already in Cart
			<? else: ?>
			<a href='cart.php?isbn=<?=$book['isbn']?>&act=add'>add to cart</a>
			<? endif; ?>
		</td>
		<? endif; ?>
	</tr>
	<? endforeach; ?>

</table>

<hr />
<pre>
<?
print_r($_SESSION);
?>
</pre>
