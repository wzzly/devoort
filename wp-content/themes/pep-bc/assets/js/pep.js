/**
 * Genesis Sample entry point.
 *
 * @package GenesisSample\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */
jQuery(document).ready(function($) {
	
	$('.nav-search:not(.active) a').on('click',function(e) {
		e.preventDefault();
		$('.nav-search').addClass('active');
	});
	
	$('body').on('click', function(e) {
		if(!$(event.target).is('.nav-search *')){
			$('.nav-search').removeClass('active');
		}
	});
	
	$('.nav-search.active a').on('click',function(e) {
		e.preventDefault();
		$('.nav-search').removeClass('active');
	});
	
	// Add span to every link or button element for effects
	$('.entry-content a.wp-block-button__link,.entry-content button').each(function() {
		$(this).contents().wrap('<span/>');
	});
	
	$('.wp-block-columns').each(function() {
		var count=$(this).children().length;
		$(this).addClass('wp-block-columns-'+count);
	});
	
	
	$('.wp-block-columns .wp-block-group .wp-block-group__inner-container .wp-block-buttons').each(function() {
		$(this).closest('.wp-block-column').addClass('contains-button');
	});
	
	$('.wp-block-columns h3, .wp-block-columns summary').each(function() {
		var textVal=$(this).text();
		$(this).addClass(textVal.toLowerCase()).addClass('icon');
	});
	
	$('.wp-block-columns .wp-block-column[style*="flex-basis:66.66%"]').each(function() {
		$(this).closest('.wp-block-columns').addClass('wp-block-columns-two-third');
	});
	
	$('.wp-block-columns .wp-block-column[style*="flex-basis:66.66%"] + .wp-block-column[style*="flex-basis:33.33%"]').each(function() {
		$(this).closest('.wp-block-columns').addClass('wp-block-columns-one-third-last');
	});
	

	$('.charleft').each(function() {
		$(this).attr('role','status');
	});
	
	/* Filter cards */
	$('.tag-filter-list li').each(function(){
		var tag=$(this).find('input').val();
		if ( $( 'article.tag-'+tag ).length == 0 ) {
			$(this).remove();
		}
	});
	
	$('.tag-filter input').click(function() {
		$('.card').fadeOut(200);
		var i=0;
		$('.tag-filter input:checked').each(function() {
			var tag=$(this).val();
			console.log(tag);
			$('.card.tag-'+tag).fadeIn(200);
			i=i+1;
		});
		if(i==0) {
			$('.card').fadeIn(200);
		}
	});
	
	if ($("#type_articles").is(':checked')){
		$("#filter_cat").show();
	} else {
		$("#filter_cat").hide();
		$("#filter_cat input").removeAttr('checked');
	}
	
	$('.gfield_error input,.gfield_error textarea').on('change',function() {
		var value=$(this).val();
		console.log('value: '+value);
		if(value!="") {
			$(this).closest('li').removeClass('gfield_error');
			$(this).closest('li').find('.validation_message').remove();
		}
	});
	
	$('#filter_type input').click(function() {
		var postType=$(this).val();
		if(postType=='articles') {
			$("#filter_cat").fadeIn(500);
			wp.a11y.speak( ajax.search_cats_shown, 'polite' );
		} else {
			$("#filter_cat").fadeOut(500);
			$("#filter_cat input").removeAttr('checked');
			wp.a11y.speak( ajax.search_cats_hidden, 'polite' );
		}
	});
	
	/* Check if iframe has title */
	$('iframe').each(function() {
		var attr=$(this).attr('title');
		
		if (typeof attr !== typeof undefined && attr !== false) {
			// Element has this attribute
			$(this).removeClass('error-title');
		} else {
			$(this).addClass('error-title');
			var parentEl=$(this).closest('figure');
			parentEl.addClass('error-title');
			var figcaption=parentEl.find('figcaption').text();
			console.log(figcaption);
			if(figcaption!="") {
				$(this).attr('title',figcaption);
				$(this).removeClass('error-title');
				parentEl.removeClass('error-title');
				parentEl.find('figcaption').hide().attr('aria-hidden',true);
			}
		}
	});

	
	/* Blog cards */
	$('.filter input').prop('checked', false);
	
	/*$('.card').each(function() {
		var url=$(this).find('.entry-title a').attr('href');
		
		if(url!="" && url!== undefined) {
			$(this).addClass('clickable');
		}
	});
	
	$('.site-container').on('click', '.card', function(e) {
		var url=$(this).find('.entry-title a').attr('href');
		var target=$(this).find('.entry-title a').attr('target');
		
		if(url!="" && url!="#" && url!== undefined) {
			
			e.stopPropagation();
			e.preventDefault();

			if(target!="_blank") {
				window.location.href = url;
			} else {
				window.open(url,'_blank');
			}
		}
	});
	
	$('.site-container').on('click','.card a',function(e) {
		e.stopPropagation();
	});
	*/
	
	/* Blog Cards */
	
	/* A11Y filter */
	
	$('.filter input').on('change',function() {
		var terms=[];
		$('.filter input:checked').each(function() {
			terms.push($(this).val());
		});
		console.log(terms);
		var data = {
			'action': 'filter_cards',
			'terms': terms,
			'security':ajax.nonce,
		};
		
		$('.cards div').fadeOut(200);
		jQuery.post(ajax.ajax_url, data, function(response) {
			$('.cards div').html(response);
			$('.cards div').fadeIn(500);
		});
		
	});
	
	/* A11Y Filter */	
	/*document.querySelectorAll('.entry-content a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
	});*/
	
	$('a[href*="fancybox"]').each(function(){jQuery(this).fancybox(jQuery.extend({},fb_opts,{'type':'inline','autoDimensions':true,'scrolling':'no','easingIn':'easeOutBack','easingOut':'easeInBack','opacity':false,'hideOnContentClick':false,'titleShow':false}))});

	
	$('.icon-search').on('click',function(e) {
		e.preventDefault();
		e.stopPropagation();
		$('.col-2.searchbar-container').slideToggle();
	});
	
	$('.col-2.searchbar-container *').on('click',function(e) {
		e.stopPropagation();
	});
	
	$(document).on('click',function(e){
		var width=$(window).innerWidth();
		if(width < 961) {
			$('.col-2.searchbar-container').slideUp();
		}
	});

	
	$(".archive-description").on("focusin", function(){

		$(this).find('.gallery-item a').each(function() {
			$(this).addClass('fancybox image');
			$(this).attr('rel','gallery-0');
		});
		
		$(".archive-description .gallery-item a").fancybox({
			'padding': 0
		});
		
	});
	
	$(document).on({
		mouseenter: function (e) {
			var id=$(this).data('popup');
			var tooltip=$(this).find('span.screen-reader-text').text();
			showTooltip(id,e.pageX,e.pageY,tooltip);
		},
		mouseleave: function () {
			$('.tooltip').remove();
		}
	}, ".pep-tooltip");  
		
/*	$('.wp-block-genesis-blocks-gb-column .gb-block-layout-column-inner .wp-block-image a').append('<span class="read-more">meer <span>info</span></span>');*/
	$('.wp-block-genesis-blocks-gb-column .gb-block-layout-column-inner').mouseover(function() {
		var parent=$(this).find('.wp-block-image');
		$(parent).addClass('active');
	});

	$('.wp-block-genesis-blocks-gb-column .gb-block-layout-column-inner').mouseout(function() {
		var parent=$(this).find('.wp-block-image');
		$(parent).removeClass('active');
	});

	var currentHeight=$('.site-header').height();

	$(window).click(function() {
		$('.woocommerce .sidebar').removeClass('show-filters')
	});

	$('.wp-block-media-text__media').wrap(function() { return '<div class="wp-block-media-text__media figure-wrap"></div>';});
	
	$('.archive-pagination li a').each(function() {
		var attr = $(this).attr('aria-current');
		if (typeof attr !== typeof undefined && attr !== false) {
			$(this).removeAttr('aria-label');
			$(this).find('span').text('Huidige pagina');
			
		}
	});

	$('a.woo-filters').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		$('.woocommerce .sidebar').toggleClass('show-filters').css('max-height','calc(100vh - '+currentHeight+'px - 30px)').css('top',currentHeight+'px');
	});

	$('.woocommerce .sidebar').click(function(e) {
		e.stopPropagation();
	});
	
	
	$('.woocommerce .woocommerce-tabs .woocommerce-Tabs-panel:first-of-type').show();

	$(window).scroll(function() {

	  if($('#topbar').length) {
		var scroll = $(window).scrollTop();
		var topbar = $('#topbar').outerHeight();
		
		if($('#notification').length) {
			topbar = topbar + $('#notification').outerHeight();
		}
		
		var header = $('.site-header').outerHeight();
		var searchbar = $('.site-header .searchbar').outerHeight();
		var topHeight=topbar;
		
		if (scroll >= topHeight) {
			$("body").addClass("fixedHeader");
		} else {
			$("body").removeClass("fixedHeader");
		}
		
		

	  }

	});

	$(function() {
		//caches a jQuery object containing the header element
		var body = $("body");
		var activityInfo=$('.activity-info');
		var activityInfo_top=activityInfo.offset();
		var header=$('.site-header')
		$(window).scroll(function() {
			var header_height=header.innerHeight();
			var scroll = $(window).scrollTop();
			$('.opening-hours').css('top',-20-scroll);
			$('.reserveren').css('top',-20-scroll);

			if (scroll >= 150) {
				body.addClass("scrollHeader");
			} else {
				body.removeClass("scrollHeader");
			}
			
			
			/*if (activityInfo.length > 0) {
			if(scroll >= (activityInfo_top.top - header_height-3)) {
				body.addClass('fixedInfo');
				activityInfo.css('top',header_height);
			} else {
				body.removeClass('fixedInfo');
				activityInfo.css('top','auto');
			}
			}*/
		});
	});

	$('a:not([href*="pdf"]):not([href*="jpg"]):not([href*="png"])').each(function() {
		var target=$(this).attr('target');
		if(target=="_blank") {
			$(this).addClass('external');
			var text=$(this).html();
			var external ='<span class="external-placeholder"></span><span class="screen-reader-text" lang="'+ajax.locale+'">'+ajax.new_window+'</span>';
			$(this).html(text+ external);
		}
	});

	// Add "in view" class to several blocks during scroll
	$(".entry-content > *").each(function() {
		if( isOnScreen( jQuery( this) ) ) {
			$(this).attr('data-view','in-view');
		}
	});
	
	$(window).on('scroll', function() {
		var height=$(window).height();
		var scroll = $(window).scrollTop();
		$(".entry-content > *").each(function() {
			if( isOnScreen( jQuery( this) ) ) {
				$(this).attr('data-view','in-view');
			} else {
				$(this).delay(500).attr('data-view','outside-view');
			}
		});
	});

});

function isOnScreen(elem) {
	// if the element doesn't exist, abort
	if( elem.length == 0 ) {
		return;
	}
	var $window = jQuery(window)
	var viewport_top = $window.scrollTop()
	var viewport_height = $window.height()
	var viewport_bottom = viewport_top + viewport_height
	var $elem = jQuery(elem)
	var top = $elem.offset().top-150
	var height = $elem.height()
	var bottom = top + height + 100

	return (top >= viewport_top && top < viewport_bottom) ||
	(bottom > viewport_top && bottom <= viewport_bottom) ||
	(height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
}


!function(e,n,t){"use strict";function a(){var e=n(this),t=e.next("nav"),a="class";e.addClass(n(t).attr("class")),n(t).attr("id")&&(a="id"),e.attr("id","mobile-"+n(t).attr(a))}function s(){var e=n("button[id^=mobile-]").attr("id");if("undefined"!=typeof e){u(e);var t=n(".site-header").is(":visible");1==t&&o(e),l(e)}}function r(){var e=n(this);c(e,"aria-pressed"),c(e,"aria-expanded"),e.toggleClass("activated"),e.hasClass("activated")?e.find(".screen-reader-text").html("Enter om te sluiten"):e.find(".screen-reader-text").html("Enter om te openen"),e.next("nav, .sub-menu").toggleClass("opened",function(){var e=n(window).innerHeight(),t=n("#genesis-nav-primary").height()+60;t>e&&(n(".site-header").addClass("relativePos"),n(".site-header #genesis-nav-primary").height(e-53),n("html, body").animate({scrollTop:0},20))})}function i(){var e=n(this),t=e.closest(".menu-item").siblings();c(e,"aria-pressed"),c(e,"aria-expanded"),e.toggleClass("activated"),e.next(".sub-menu").slideToggle("fast"),e.hasClass("activated")?e.find(".screen-reader-text").html("Submenu sluiten"):e.find(".screen-reader-text").html("Submenu openen"),t.find("."+v).removeClass("activated").attr("aria-pressed","false"),t.find(".screen-reader-text").html("Submenu openen"),t.find(".sub-menu").slideUp("fast")}function u(e){"function"==typeof n(".js-superfish").superfish&&("none"===d(e)?n(".js-superfish").superfish({delay:100,animation:{opacity:"show",height:"show"},dropShadows:!1}):n(".js-superfish").superfish("destroy"))}
function o(e){var t="#genesis-nav-primary",a="#nav-wrapper";n('.genesis-skip-link a[href^="'+t+'"]').each(function(){var e=n(this).attr("href");e=a,n(this).attr("href",e)})}
function l(e){"none"===d(e)&&(n(".menu-toggle, .sub-menu-toggle").removeClass("activated").attr("aria-expanded",!1).attr("aria-pressed",!1),n("nav, .sub-menu").attr("style",""))}function d(n){var t=e.getElementById(n),a=window.getComputedStyle(t);return a.getPropertyValue("display")}function c(e,n){e.attr(n,function(e,n){return m(n)})}function m(e){return"false"===e?"true":"false"}var f={},p="menu-toggle",v="sub-menu-toggle";f.init=function(){var e={menu:n("<button />",{"class":p+" hamburger hamburger--squeeze","aria-expanded":!1,"aria-pressed":!1,role:"button"}).append('<span class="hamburger-box"><span class="hamburger-inner"></span></span>').append(f.params.mainMenu).append('<span class="screen-reader-text">Enter om te openen</span>'),submenu:n("<button />",{"class":v,"aria-expanded":!1,"aria-pressed":!1,role:"button"}).append(n("<span />",{"class":"screen-reader-text",text:f.params.subMenu}).text("Submenu openen"))};if(n("nav#genesis-nav-primary").before(e.menu),n("nav#genesis-nav-primary .sub-menu").before(e.submenu),n("."+p).each(a),n(window).on("resize.geev",s).triggerHandler("resize.geev"),n("."+p).on("click.geev-mainbutton",r),n("."+v).on("click.geev-subbutton",i),n("nav#genesis-nav-primary li.current-menu-ancestor").hasClass("current-menu-ancestor")){var t=n("nav#genesis-nav-primary li.current-menu-ancestor button"),u=(n(this),t.closest(".menu-item").siblings());c(t,"aria-pressed"),c(t,"aria-expanded"),t.toggleClass("activated"),t.next(".sub-menu"),t.hasClass("activated")?t.find(".screen-reader-text").html("Submenu sluiten"):t.find(".screen-reader-text").html("Submenu openen"),u.find("."+v).removeClass("activated").attr("aria-pressed","false"),u.find(".screen-reader-text").html("Submenu openen"),u.find(".sub-menu")}},n(e).ready(function(){f.params="undefined"==typeof geevL10n?"":geevL10n,"undefined"!=typeof f.params&&f.init(),n("body").on("click touchstart",function(e){if(n("."+p).hasClass("activated")){var t=n(e.target);0==t.parents(".site-header").length&&(n("."+p).trigger("click"),n(".site-header").focus())}}),n(e).on("focusin","a, input, select, textarea, form",function(e){if(n("."+p).hasClass("activated")){var t=n(e.target);0==t.parents("nav#genesis-nav-primary").length&&n("."+p).trigger("click")}})})}(document,jQuery);

function showTooltip(id, x, y, contents) {
	jQuery('.tooltip').remove();
	jQuery('<span id=\"'+id+'\" class="tooltip">' + contents + '</span>').css( { display: 'none', top: y -35, left: x -5}).appendTo('body').delay(500).fadeIn(500); 
}