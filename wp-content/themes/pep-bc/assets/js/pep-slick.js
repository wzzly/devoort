jQuery(document).ready(function($) {
	$('.loop-variation-slider').slick({
		infinite:false,
		dots:false,
		prevArrow:'<button type="button" class="slick-prev"><span class="screen-reader-text">Vorige slide</span> &lsaquo;</button>',
		nextArrow:'<button type="button" class="slick-next"><span class="screen-reader-text">Volgend slide</span> &rsaquo;</button>',
	});
	
	$(document).on('yith_infs_added_elem',function() {
		console.log('loaded');
		
		$('.loop-variation-slider').not('.slick-initialized').slick({
			infinite:false,
			dots:false,
			prevArrow:'<button type="button" class="slick-prev"><span class="screen-reader-text">Vorige slide</span> &lsaquo;</button>',
			nextArrow:'<button type="button" class="slick-next"><span class="screen-reader-text">Volgend slide</span> &rsaquo;</button>',
		});
		console.log('slick slider should be working');
		
	});
	
});