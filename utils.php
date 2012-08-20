<?php
/**
* login function
*/
function login($username, $password)
{
	$_SESSION['is_logged_in'] = FALSE;	// initial value

	$query = "SELECT *, DATEDIFF(expiry_date, NOW()) AS days_left
	            FROM users WHERE username LIKE '{$username}' 
	            AND password LIKE '{$password}'";
	$result = mysql_query($query) 
	            or die('Query failed: ' . mysql_error());
	
	if(mysql_num_rows($result) > 0)
	{
	    $_SESSION['user'] = null;
		$user = mysql_fetch_assoc($result);

		if($user['is_disabled'] == 1)
		{
			$_SESSION['message'] = 'Account Disabled';
			return false;
		}

		if($user['days_left'] < 1)
		{
			$_SESSION['message'] = 'Expired User Account';
			return false;
		}
		
		$_SESSION['user'] = $user;
		$_SESSION['user']['cart'] = array();

		// remove error message on session once logged in
		if(isset($_SESSION['message'])) unset($_SESSION['message']);

		$_SESSION['message'] = 'Successful Login';

		$_SESSION['is_logged_in'] = TRUE;

		return true;
	} else {
		$_SESSION['message'] = 'Wrong username or password';
		return false;
	}
}

function logout()
{
	unset($_SESSION['user']);
	unset($_SESSION['is_logged_in']);
	unset($_SESSION['message']);
}

/**
* return status of access
*/
function is_logged_in()
{
	return (isset($_SESSION['is_logged_in'])) ? $_SESSION['is_logged_in'] : false;
}

/**
* return logged in user
*/
function get_logged_in_user()
{
	return (isset($_SESSION['user'])) ? $_SESSION['user']['username'] : 'Guest';
}

/**
* retrieve all books
*/
function get_all_books()
{
	$query = "SELECT * FROM books";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$books = array();
	if(mysql_num_rows($result) > 0)
	{
		while($row = mysql_fetch_assoc($result))
		{
			$books[] = $row;
		}
	}
	return $books;	
}

function get_book($isbn = null)
{
	if(!is_null($isbn))
	{
		$query = "SELECT * FROM books WHERE isbn LIKE '{$isbn}'";
		
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		return (mysql_num_rows($result) > 0) ? mysql_fetch_assoc($result) : false;
	}
	return false;
}

/**
* dynamic book search
*/
function search_books($isbn='', $title='', $publisher='', $author='', $copyright='ALL')
{
	$query = "SELECT * FROM books WHERE 1=1 ";

	if(strlen($isbn) > 0) $query .= "AND isbn LIKE '%{$isbn}%'";
	if(strlen($title) > 0) $query .= "AND title LIKE '%{$title}%'";
	if(strlen($publisher) > 0) $query .= "AND publisher LIKE '%{$publisher}%'";
	if(strlen($author) > 0) $query .= "AND author LIKE '%{$author}%'";
	if($copyright != "ALL") $query .= "AND copyright = {$copyright}";

	error_log('Search Books Query: ' . $query);

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	$books = array();

	if(mysql_num_rows($result) > 0)
	{
		while($row = mysql_fetch_assoc($result))
		{
			$books[] = $row;
		}
	}
	
	$_SESSION['message'] = 'Search Results';

	return $books;
}

/**
* add book to cart
*/
function add_to_cart($isbn)
{
	if(isset($_SESSION['user']['cart']))
	{
		if(array_search($isbn, $_SESSION['user']['cart']))
		{
			$_SESSION['message'] = 'The book is already in the cart.';
			return false;
		}

		$number_of_books_reserved = count($_SESSION['user']['cart']);
		if($number_of_books_reserved >= 3)
		{
			$_SESSION['message'] = 'You can only reserve 3 books.';
			return false;
		}
	}
	$_SESSION['message'] = 'Added a book to cart';
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
			error_log('Books in cart: ' . count($cart));

			foreach ($cart as $key => $book_isbn)
			{
				if($isbn == $book_isbn)
				{
					unset($_SESSION['user']['cart'][$key]);
				}
			}
			$_SESSION['message'] = 'Removed a book from cart';
			return true;
		}
	}
	return false;
}

/**
* return books in cart
*/
function get_cart()
{
	return (isset($_SESSION['user']['cart'])) ? $_SESSION['user']['cart'] : false;
}

/**
* return true if book is in cart
*/
function is_in_cart($isbn)
{
	return in_array($isbn, $_SESSION['user']['cart']);
}

/**
* reserve all books
*/
function reserve_books()
{
	$user = $_SESSION['user'];
	$books = $_SESSION['user']['cart'];

	if(count($books) > 0)
	{
		$query = "INSERT INTO reserved_books (isbn, user_id, reserved_date) VALUES ";
		$values = array();
		foreach ($books as $book)
		{
			$values[] = "('{$book}', '{$user['id']}', NOW())";	// NOW() is a MySQL Date Function
		}
		$values = implode(',', $values);
		$query .= $query . $values;

		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		if(mysql_affected_rows() > 0)
		{
			$_SESSION['message'] = 'Reserved books';
			return TRUE;
		}
		else 
		{
			$_SESSION['message'] = 'Unable to reserve books';
			return FALSE;
		}
	}
}

function get_reserved_book($reservation_id)
{
	$query = "SELECT * FROM reserved_books WHERE id = $reservation_id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if(mysql_num_rows($result) > 0)
	{
		$reserved_book = mysql_fetch_assoc($result);
	}
}

/**
* borrow books
*   - grant reserved book for borrowing
*      using reservation ID (which contains ISBN and UserID)
*   - delete reserve record once added into borrowed books table
*/
function borrow_book($reservation_id)
{
	// TODO
	$query = "INSERT INTO borrowed_books (isbn, user_id, date_borrowed, is_returned) ".
		"(SELECT isbn, user_id, NOW(), 0 FROM reserved_books WHERE id = {$reservation_id})";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if(mysql_affected_rows() > 0)
	{
		$reserved_book = mysql_fetch_assoc($result);
	}
}

/**
* return books
*/
function return_books($borrowed_book_id)
{
	// TODO
	$query = "UPDATE borrowed_books SET ".
		" is_returned = 1, date_returned = NOW(), [DAYS_PENALTY]" .
		" WHERE id = {$borrowed_book_id}";
}
?>