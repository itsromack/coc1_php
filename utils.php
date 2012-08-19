<?php
/**
* login function
*/
function login($username, $password)
{
	$query = "SELECT *, DATEDIFF(expiry_date, NOW()) AS days_left
	            FROM users WHERE username LIKE '{$username}' 
	            AND password LIKE '{$password}'";
	$result = mysql_query($query) 
	            or die('Query failed: ' . mysql_error());
	
	if(mysql_num_rows($result) > 0){
	    $_SESSION['user'] = null;
		$user = mysql_fetch_assoc($result);

		if($user['is_disabled'] == 1){
			$_SESSION['login_error'] = 'Account Disabled';
			return false;
		}

		if($user['days_left'] < 1){
			$_SESSION['login_error'] = 'Expired User Account';
			return false;
		}
		
		$_SESSION['user'] = 
		    array(
		        'username' => $username,
		        'type' => $user['type']
		    );

		// remove error message on session once logged in
		if(isset($_SESSION['login_error'])) unset($_SESSION['login_error']);

		return true;
	} else {
		$_SESSION['login_error'] = 'Wrong username or password';
		return false;
	}
}

/**
* retrieve all books
*/
function get_all_books()
{
	$query = "SELECT * FROM books";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$books = array();
	if(mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)){
			$books[] = $row;
		}
	}
	return $books;	
}

/**
* dynamic book search
*/
function search_books($isbn='', $title='', $publisher='', $author='', $copyright=0)
{
	$query = "SELECT * FROM books WHERE " .
		"isbn LIKE '%{$isbn}% '" .
		" OR title LIKE '%{$title}%'" .
		" OR publisher LIKE '%{$publisher}%'" .
		" OR author LIKE '%{$author}%'" .
		" OR copyright = {$copyright}";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$books = array();
	if(mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)){
			$books[] = $row;
		}
	}
	return $books;
}
?>
