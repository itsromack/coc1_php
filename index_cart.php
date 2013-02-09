<div style="background-color:gold; padding: 10px; position:absolute; top: 40; left: 750;">
	<h4>Book Cart</h4>
	<? $cart = get_cart() ?>
	<? if($cart != false): ?>
		<ul>
		<? foreach($cart as $isbn): ?>
			<? $book = get_book($isbn); ?>
			<li><?= $book['title'] ?> - <a href='cart.php?act=remove&isbn=<?= $isbn ?>'>remove from cart</a></li>
		<? endforeach; ?>
		</ul>
		<form action="cart.php" method="get">
			<input type="submit" value="Reserve" name="act" />
			<input type="submit" value="Clear" name="act" />
		</form>
	<? else: ?>	
		Cart is empty	
	<? endif ?>
</div>
