<h4>Search by</h4>
<form method='POST' action='index.php'>
	ISBN <input type='text' name='isbn' size='10' />
	Title <input type='text' name='title' size='20' />
	Publisher <input type='text' name='publisher' size='10' />
	<br />
	Author <input type='text' name='author' size='20' />
	Copyright Year
	<select name='copyright'>
		<option value='ALL'>ALL</option>
		<? for($i = date('Y'); $i > 1900; $i--): ?>
		<option value='<?= $i ?>'><?= $i ?></option>
		<? endfor; ?>
	</select>
	<input type='submit' value='Search for Books' />
</form>