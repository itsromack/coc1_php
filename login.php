<?php
include_once("connect.php");
include_once("utils.php");

if(!empty($_POST))
{
    // execute login function - see utils.php
	$result = login($_POST['username'], $_POST['password']);
	
	if($result == true){
	    $page = '';
	    if($_SESSION['user']['type'] == 'normal'){
	        $page = 'index.php';
	    } elseif($_SESSION['user']['type'] == 'admin'){
	        $page = 'administrator/index.php';
	    }
	    // redirect to a specific page
		header('Location: ' . $page);
	} else {
		echo $_SESSION['login_error'];
	}
}
?>

<h1>Login</h1>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
Username: <input type='text' name='username' /><br />
Password: <input type='password' name='password' /><br />
<input type='submit' value='Login' />
</form>

<hr />
<pre>
<?
print_r($_SESSION);
?>
</pre>
