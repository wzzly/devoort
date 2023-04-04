<div class="clearfix"></div>
<div class="searchbar">
	<div class="col-1 assortiment-container">
		<?php echo $assortiment;?>
	</div>
	<div class="col-2 searchbar-container">
		<?php
		if ( function_exists( 'woocommerce_product_search' ) ) {
			echo $searchbar;
		} else {
			the_widget( 'WC_Widget_Product_Search', 'title=' );
		}
		?>
	</div>
	<div class="col-3 cart-container">
		<a class="icon icon-cart pep-tooltip" data-popup="cart" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"> <span class="screen-reader-text">Bekijk winkelwagen</span> <span class="count <?php echo $countClass;?>"><?php echo $countText;?></span></a>
		<a class="icon icon-wishlist pep-tooltip" data-popup="favo" href="/mijn-favorieten/"><span class="screen-reader-text">Mijn favorieten</span></a>
		<a class="icon icon-my-account pep-tooltip" data-popup="account" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><span class="screen-reader-text">Mijn account</span></a>
		<a class="icon icon-search only-mobile" href="<?php echo get_search_link(); ?>"><span class="screen-reader-text">Zoeken</span></a>
	</div>
</div>