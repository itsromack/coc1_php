<div style="background-color:lightblue; padding: 10px; position:absolute; top: 0; left: 750;">

<? if(is_logged_in()): ?>
	<? $fullname = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']; ?>
	Welcome <b><?= $fullname ?> (<i style='color:red;'><?= $_SESSION['user']['username'] ?></i>)</b>! 
	<a href='login.php?logout=1'>Log out</a>

<? else: ?>

	<a href='login.php'>Login</a>

<? endif; ?>

</div>
