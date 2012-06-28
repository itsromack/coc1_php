<?php
include_once("connect.php");
include_once("utils.php");

$books = get_all_books();
?>

<h1>Books</h1>

<?foreach($books as $book):?>
<li><?=$book['isbn']?>, <?=$book['title']?>, <?=$book['publisher']?>, <?=$book['author']?>, <?=$book['copyright']?>
<?endforeach;?>

<hr />
<pre>
<?
print_r($_SESSION);
?>
</pre>
