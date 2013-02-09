<?php
include_once("admin_header.php");

$reserved_books = array();
if (!empty($_GET['user_id'])) {
	$reserved_books = get_reserved_books($_GET['user_id']);
} else {
	$reserved_books = get_reserved_books();
}

?>
<h1>Reserved Books</h1>

<table border='1' cellpadding='7'>
	<tr>
		<th>Reservee</th>
		<th>ISBN</th>
		<th>Book Title</th>
		<th>Author</th>
		<th>Date Reserved</th>
		<th>Action</th>
	</tr>
	<? if (count($reserved_books) > 0): ?>
		<? $row = 1 ?>
		<? foreach($reserved_books as $item): ?>
		<tr <? if($row++ % 2 != 0) echo 'style="background-color:silver"';?>>
			<td><?=$item['fullname']?> (<?=$item['username']?>)</td>
			<td><?=$item['isbn']?></td>
			<td><?=$item['title']?></td>
			<td><?=$item['author']?></td>
			<td><?=$item['date_reserved']?></td>
			<td>
				<a href="<?= $item['id'] ?>">Grant Reservation</a>
			</td>
		</tr>
		<? endforeach; ?>
	<? else: ?>
		<tr><td colspan="5">No items found</td></tr>
	<? endif?>
</table>

<hr>
<pre>
	<? print_r($_SESSION); ?>
</pre>