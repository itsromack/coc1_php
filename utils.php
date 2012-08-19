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
			$_SESSION['message'] = 'Account Disabled';
			return false;
		}

		if($user['days_left'] < 1){
			$_SESSION['message'] = 'Expired User Account';
			return false;
		}
		
		$_SESSION['user'] = $user;

		// remove error message on session once logged in
		if(isset($_SESSION['message'])) unset($_SESSION['message']);

		return true;
	} else {
		$_SESSION['message'] = 'Wrong username or password';
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

/**
* add book to cart
*/
function add_to_cart($isbn)
{
	if(isset($_SESSION['user']['cart']))
	{
		$number_of_books_reserved = count($_SESSION['user']['cart']);
		if($number_of_books_reserved >= 3)
		{
			$_SESSION['message'] = 'You can only reserve 3 books.';
			return false;
		}
	}
	$_SESSION['user']['cart'][] = $isbn;
	return true;
}

/**
* remove from cart
*/
function remove_from_cart($isbn)
{
	if(isset($_SESSION['user']['cart']))
	{
		$cart = $_SESSION['user']['cart'];
		if(in_array($isbn, $cart))
		{
			// find key of the ISBN that will be deleted
			$isbn_key = array_search($isbn, $cart);
			if($isbn_key != FALSE)
			{
				unset($_SESSION['user']['cart'][$isbn_key]);
				return true;
			}
		}
	}
	return false;
}

function reserve_books()
{
	$user = $_SESSION['user'];
	$books = $_SESSION['user']['cart'];
	//
	// TODO: add reserve books script
	//
}

?>
