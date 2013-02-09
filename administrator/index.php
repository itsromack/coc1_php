<?php
include_once("admin_header.php");

?>
<table style="border:2px solid; background-color:silver;">
	<tr>
		<td>Reserved Books</td>
		<td><?= count_reserved_books() ?></td>
	</tr>
	<tr>
		<td>Borrowed Books</td>
		<td><?= count_borrowed_books() ?></td>
	</tr>
	<tr>
		<td>Active Users</td>
		<td><?= count_active_users() ?></td>
	</tr>
	<tr>
		<td>Expired Users</td>
		<td><?= count_expired_users() ?></td>
	</tr>
	<tr>
		<td>Disabled Users</td>
		<td><?= count_disabled_users() ?></td>
	</tr>
</table>
