<?php
/**
* login function
*/
function login($username, $password) {
	$_SESSION['is_logged_in'] = FALSE;	// initial value

	$query = "SELECT *, DATEDIFF(expiry_date, NOW()) AS days_left
	            FROM users WHERE username LIKE '{$username}' 
	            AND password LIKE '{$password}'";
	$result = mysql_query($query) 
	            or die('Query failed: ' . mysql_error());
	
	if (mysql_num_rows($result) > 0)	{
	    $_SESSION['user'] = null;
		$user = mysql_fetch_assoc($result);

		if ($user['is_disabled'] == 1) {
			$_SESSION['message'] = 'Account Disabled';
			return false;
		}

		if ($user['days_left'] < 0) {
			$_SESSION['message'] = 'Expired User Account';
			return false;
		}
		
		$_SESSION['user'] = $user;
		clear_book_cart();

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

/**
* This function is used for all the pages in the administration area
*  because only users of type 'admin' could view the admin page.
* An attempt to access the page other that admin user shall be redirected to warning page.
*/
function auth(){
	if (is_logged_in()) {
		if ($_SESSION['user']['type'] === 'admin') {
			return true;
		}
	}
	header('Location: /warning.php');
}

function logout() {
	$items = array('user', 'is_logged_in', 'message');
	foreach ($items as $item) unset($_SESSION[$item]);
}

/**
* return status of access
*/
function is_logged_in() {
	return (isset($_SESSION['is_logged_in'])) ? $_SESSION['is_logged_in'] : false;
}

/**
* return logged in user
*/
function get_logged_in_user() {
	return (isset($_SESSION['user'])) ? $_SESSION['user']['username'] : 'Guest';
}

/**
* retrieve all books
*/
function get_all_books() {
	$query = "SELECT * FROM books";
	$result = mysql_query($query) 
		or die('Query failed: ' . mysql_error());
	$books = array();
	if (mysql_num_rows($result) > 0)	{
		while ($row = mysql_fetch_assoc($result)) {
			$books[] = $row;
		}
	}
	return $books;	
}

function get_book($isbn = null) {
	if (!is_null($isbn)) {
		$query = "SELECT * FROM books WHERE isbn LIKE '{$isbn}'";		
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		return (mysql_num_rows($result) > 0) ? mysql_fetch_assoc($result) : false;
	}
	return false;
}

/**
* dynamic book search
*/
function search_books($isbn='', $title='', $publisher='', $author='', $copyright='ALL') {
	$query = "SELECT * FROM books WHERE 1=1 ";

	if(strlen($isbn) > 0) $query .= "AND isbn LIKE '%{$isbn}%' ";
	if(strlen($title) > 0) $query .= "AND title LIKE '%{$title}%' ";
	if(strlen($publisher) > 0) $query .= "AND publisher LIKE '%{$publisher}%' ";
	if(strlen($author) > 0) $query .= "AND author LIKE '%{$author}%' ";
	if($copyright != "ALL") $query .= "AND copyright = {$copyright}";

	error_log('Search Books Query: ' . $query);

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	$books = array();

	if (mysql_num_rows($result) > 0)	{
		while($row = mysql_fetch_assoc($result)) {
			$books[] = $row;
		}
	}
	
	$_SESSION['message'] = 'Search Results';

	return $books;
}

/**
* add book to cart
*/
function add_to_cart($isbn) {
	if (isset($_SESSION['user']['cart'])) {
		if (array_search($isbn, $_SESSION['user']['cart'])) {
			$_SESSION['message'] = 'The book is already in the cart.';
			return false;
		}

		$number_of_books_reserved = count($_SESSION['user']['cart']);
		if ($number_of_books_reserved >= 3) {
			$_SESSION['message'] = 'You can only reserve 3 books.';
			return false;
		}

		$_SESSION['user']['cart'][] = $isbn;
		$_SESSION['message'] = 'Added a book to cart';
	}
	
	return true;
}

/**
* remove from cart
*/
function remove_from_cart($isbn) {
	if (isset($_SESSION['user']['cart'])) {
		$cart = $_SESSION['user']['cart'];
		
		if (in_array($isbn, $cart)) {
			error_log('Books in cart: ' . count($cart));

			$key = array_search($isbn, $cart);
			unset($_SESSION['user']['cart'][$key]);

			$_SESSION['message'] = 'Removed a book from cart';
			return true;
		}
	}
	return false;
}

/**
* return books in cart
*/
function get_cart() {
	return (isset($_SESSION['user']['cart'])) ? $_SESSION['user']['cart'] : false;
}

/**
* return true if book is in cart
*/
function is_in_cart($isbn) {
	return in_array($isbn, $_SESSION['user']['cart']);
}

/**
* clears the book cart
*/
function clear_book_cart() {
	$_SESSION['user']['cart'] = array();
	$_SESSION['message'] = 'Book Cart Cleared';
}

/**
* reserve all books
*/
function reserve_books() {
	$user = $_SESSION['user'];
	$books = $_SESSION['user']['cart'];

	if (count($books) > 0) {
		$query = "INSERT INTO reserved_books (isbn, user_id, date_reserved) VALUES ";
		$values = array();
		foreach ($books as $book) {
			$values[] = "('{$book}', {$user['id']}, NOW())";	// NOW() is a MySQL Date Function
		}
		$values = implode(',', $values);
		$query .= $values;

		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		if (mysql_affected_rows() > 0) {
			clear_book_cart();
			$_SESSION['message'] = 'Reserved books';
		}
	} else {
		$_SESSION['message'] = 'There are no books to reserve';
	}

	return FALSE;
}

/**
* Retrieve all reserved books
*  - can also retrieve the reserved book of a specific user
*/
function get_reserved_books($user_id = null) {
	$query = 
		"SELECT 
			u.username, 
			CONCAT(u.first_name, ' ', u.last_name) AS fullname, 
			r.isbn,
			b.title, 
			b.author,
			r.date_reserved
			FROM reserved_books r
			JOIN users u ON (r.user_id=u.id)
			JOIN books b ON (r.isbn=b.isbn)";
	// if looking for the books the specific user have reserved, append this to query
	if (!is_null($user_id)) $query .= " WHERE r.user_id=" . $user_id;

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	$reserved_books = array();
	if (mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_assoc($result)) {
			$reserved_books[] = $row;
		}
	}

	return $reserved_books;
}

function get_reserved_book($reservation_id) {
	$query = "SELECT * FROM reserved_books WHERE id = $reservation_id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if (mysql_num_rows($result) > 0) {
		$reserved_book = mysql_fetch_assoc($result);
	}
}

function count_active_users() {
	$query = "SELECT COUNT(id) AS count_active_users FROM users 
				WHERE is_disabled=0 
					AND DATEDIFF(expiry_date, NOW()) >= 0";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {
		return 0;
	} else {
		$users = mysql_fetch_assoc($result);
		return $users['count_active_users'];
	}
}

function count_expired_users() {
	$query = "SELECT COUNT(id) AS count_expired_users FROM users 
				WHERE DATEDIFF(expiry_date, NOW()) < 0";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {
		return 0;
	} else {
		$users = mysql_fetch_assoc($result);
		return $users['count_expired_users'];
	}
}

function count_disabled_users() {
	$query = "SELECT COUNT(id) AS count_disabled_users FROM users 
				WHERE is_disabled=1";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {
		return 0;
	} else {
		$users = mysql_fetch_assoc($result);
		return $users['count_disabled_users'];
	}
}

function count_borrowed_books() {
	$query = "SELECT COUNT(id) AS count_borrowed_books FROM borrowed_books 
				WHERE is_returned=0";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {
		return 0;
	} else {
		$users = mysql_fetch_assoc($result);
		return $users['count_borrowed_books'];
	}
}

function count_reserved_books() {
	$query = "SELECT COUNT(id) AS count_reserved_books FROM reserved_books";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {
		return 0;
	} else {
		$users = mysql_fetch_assoc($result);
		return $users['count_reserved_books'];
	}
}



/**
* borrow books
*   - grant reserved book for borrowing
*      using reservation ID (which contains ISBN and UserID)
*   - delete reserve record once added into borrowed books table
*/
function borrow_book($reservation_id) {
	// TODO
	$query = "INSERT INTO borrowed_books (isbn, user_id, date_borrowed, is_returned) ".
		"(SELECT isbn, user_id, NOW(), 0 FROM reserved_books WHERE id = {$reservation_id})";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	//...
}

/**
* return books
*/
function return_books($borrowed_book_id) {
	// TODO
	$query = "UPDATE borrowed_books SET ".
		" is_returned = 1, date_returned = NOW(), [DAYS_PENALTY]" .
		" WHERE id = {$borrowed_book_id}";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	//...
}
?>
