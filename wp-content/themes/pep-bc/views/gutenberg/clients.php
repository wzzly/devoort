<?php
use PEP\Template;
global $post;
?>
<section id="clients" class="<?php echo $class;?>">
	<div class="js-carrousel relative">
			<?php 
			$i=0;
			foreach ($clients AS $client): 
				
			?>
				<div class="col">	
					<div class="col-inner">
					<?php 
					if(has_post_thumbnail($client->ID) && isset($attributes['show_image']) && $attributes['show_image']==1) {
					$page='';
					if(function_exists('get_field')) {
						$page=get_field('page',$client->ID);
					}
					if(isset($page[0]) && !empty($page[0])) {
						$pages=implode(',',$page);
						$url='/referenties/?ref='.$pages;
					?>
					<a href="<?php echo $url;?>" class="pep-tooltip" data-popup="<?php echo strtolower(str_replace(' ','',$client->post_title));?>"><img src="<?php echo Template::get_post_image($client->ID, 'client'); ?>" alt="" /><span class="screen-reader-text"><?php echo $client->post_title; ?></span></a>
					<?php } else {
					
						?>
						<span class="pep-tooltip" data-popup="<?php echo strtolower(str_replace(' ','',$client->post_title));?>"><img src="<?php echo Template::get_post_image($client->ID, 'client'); ?>" alt="" /><span class="screen-reader-text"><?php echo $client->post_title; ?></span></span>						
						<?php
						}
					}
					if(isset($attributes['show_text']) && $attributes['show_text']==1) {
					echo wpautop(get_the_content(null,null,$client->ID));
					}
					?>
					</div>
				</div>
				<?php
				$i++;
				
				
			endforeach; ?>
	</div>
</section>
<script>

<?php if(!isset($attributes['slides_to_scroll'])) $attributes['slides_to_scroll']=1; ?>

jQuery(document).ready(function($) {
$('.js-carrousel').slick({
  dots: <?php echo (isset($attributes['show_controls']) && $attributes['show_controls'] ? 'true' : 'false');?>,
  infinite: <?php echo $attributes['infinite'];?>,
  slidesToShow: <?php echo (isset($attributes['load_slides']) ? $attributes['load_slides'] : '1');?>,
  slidesToScroll: <?php echo (isset($attributes['slides_to_scroll']) ? $attributes['slides_to_scroll'] : '1');?>,
  arrows:<?php echo (isset($attributes['show_arrows']) && $attributes['show_arrows'] ? 'true' : 'false');?>,
  autoplay:<?php echo (isset($attributes['auto_play']) && $attributes['auto_play'] ? 'false' : 'true');?>,
  autoplaySpeed:<?php echo (isset($attributes['auto_play_speed']) ? $attributes['auto_play_speed'] : '5000');?>,
  speed:<?php echo (isset($attributes['speed']) ? $attributes['speed'] : '5000');?>,
  <?php if(isset($attributes['appendArrows']) && $attributes['appendArrows']!="") { ?>appendArrows: '<?php echo $attributes['appendArrows'];?>',<?php } ?>
  <?php if(isset($attributes['appendDots']) && $attributes['appendDots']!="") { ?>appendDots: '<?php echo $attributes['appendDots'];?>',<?php } ?>
  prevArrow: '<button type="button" class="slick-prev"><?php echo (isset($attributes["prevArrow"]) ? $attributes["prevArrow"] : __("Previous","pep"));?></button>',
  nextArrow: '<button type="button" class="slick-next"><?php echo (isset($attributes["nextArrow"]) ? $attributes["nextArrow"] : __("Next","pep"));?></button>',
  responsive: [
			<?php echo apply_filters('pep_clients_breakpoints','
			{
				breakpoint: 960,
				settings: {
					slidesToShow: 3 ,
					slidesToScroll: 1,
					arrows:false,
					dots:true,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 3 ,
					slidesToScroll: 1,
					adaptiveHeight: true,
					arrows:false,
					dots:true,
				}
			},
			{
				breakpoint: 600,
				settings: {
					adaptiveHeight: true,
					slidesToShow: 2,
					slidesToScroll: 1,
					arrows:false,
					dots:true,
				}
			},
			{
				breakpoint: 480,
				settings: {
					adaptiveHeight: true,
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows:false,
					dots:true,
				}
			}',$post);?>
		],
});
});
</script>