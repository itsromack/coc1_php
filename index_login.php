<div style="background-color:lightblue; padding: 10px; position:absolute; top: 0; left: 750;">
<? if(is_logged_in()): ?>
Welcome <b><?= get_logged_in_user(); ?></b>! <a href='login.php?logout=1'>Log out</a>
<? else: ?>
<a href='login.php'>Login</a>
<? endif; ?>
</div>