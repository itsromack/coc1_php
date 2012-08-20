<div style="background-color:gold; padding: 10px; position:absolute; top: 40; left: 700;">
	<h4>Book Cart</h4>
	<? $cart = get_cart(); ?>
	<? if(get_cart() != false): ?>
	<ul>
	<? foreach($cart as $isbn): ?>
		<? $book = get_book($isbn); ?>
		<li><?= $book['title'] ?> - <a href='cart.php?act=remove&isbn=<?= $isbn ?>'>remove from cart</a></li>
	<? endforeach; ?>
	</ul>
	<? else: ?>
	Cart is empty
	<? endif ?>
</div>