jQuery(document).ready(function($) {
	$('.entry-content a:not(.wp-block-button__link),.entry-content .wp-block-pep-imageheader strong').each(function() {
		$(this).wrapInner('<span></span>');
	});	
	
	$('figcaption').each(function() {
		$(this).wrapInner('<span class="figcaption-block"></span>');
	});

	$('.specialismen').each(function() {
		if ($(this).find('li').length < 4) {
			$(this).css('columns', '1');
		}
	});
	
	$('.trigger-search').click(function(e) {
		e.preventDefault();
		if(!$(this).hasClass('clicked')) {
			$('.menu-toggle').trigger('click');
			$('.search-form-input').focus();
			$(this).addClass('clicked');
			console.log('clicked');
		} else {
			$(this).removeClass('clicked');
		}
	});

	var $document = $(document),
    $element = $('#progress-container'),
    className = 'started';

    $document.scroll(function() {
	  $element.toggleClass(className, $document.scrollTop() >= 1);
	});

	$(document).mouseup(function(e) 
	{
		var container = $('details');

		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			jQuery('details').each(function(){
				jQuery(this).attr('open',false);
			});
		}
	});

});

// Fetch all the details element.
const details = document.querySelectorAll("details");

// Add the onclick listeners.
details.forEach((targetDetail) => {
  targetDetail.addEventListener("click", () => {
    // Close all the details that are not targetDetail.
    details.forEach((detail) => {
      if (detail !== targetDetail) {
        detail.removeAttribute("open");
      }
    });
  });
});