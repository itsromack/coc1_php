<? $cart = get_cart(); ?>
<? if(get_cart() != false): ?>
<ul>
	<? foreach($cart as $book): ?>
	<li></li>
	<? endforeach; ?>
</ul>
<? endif ?>