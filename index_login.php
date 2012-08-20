<? if(is_logged_in()): ?>
Welcome <?= get_logged_in_user(); ?>! <a href='login.php?logout=1'>Log out</a>
<? else: ?>
<a href='login.php'>Login</a>
<? endif; ?>