<?php
$post_type=get_post_type();
$rand=rand(0,99999);
$term_id='';
$show_date=false;
if(isset(get_queried_object()->term_id)) {
	$term_id=get_queried_object()->term_id;
	$show_date = get_term_meta($term_id, 'show_date', true);
}

   printf('<article %s>', genesis_attr('entry'));
?>
				<?php do_action('genesis_before_entry'); ?>
				
				<div class="text">
					<?php do_action('pep_before_card_header');?>
					<header class="<?php if(isset($date)) { echo 'has-event-date';} elseif($show_date) { echo 'has-date';}?>"><h2 class="entry-title" itemprop="headline">
						<a href="<?php the_permalink(); ?>" rel="bookmark" aria-describedby="card_<?php echo $rand;?>"><?php the_title(); ?></a>
					</h2>
					<?php
					
					if($show_date) {
						?>
						<time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>
						<?php
					}
					?>
					</header>
					<?php do_action('pep_after_card_header');?>					
					<?php if($show_excerpt) { ?>
					<p class="entry-content blog-content" itemprop="text">
						<?php echo get_the_excerpt(); ?>
					</p>
					<?php } ?>
					<?php if($readmore) { ?>
					<span class="cta" aria-hidden="true" id="card_<?php echo $rand;?>"><?php echo $readmore;?> <span class="screen-reader-text"><?php _e('about','pep');?> <?php the_title();?></span></span>
					<?php } ?>
				</div>
				<?php if(has_post_thumbnail() && $thumb_size!="no_thumb") { 
				?>
						<figure class="img">
						<?php $thumb=wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $thumb_size );
						$large=wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
						$alt = get_post_meta(get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true);
						?>
						<img width="<?php echo $thumb[1];?>" height="<?php echo $thumb[2];?>" src="<?php echo $thumb[0];?>" class="attachment-<?php echo $thumb_size;?> size-<?php echo $thumb_size;?> wp-post-image" alt="<?php echo esc_attr($alt);?>" srcset="<?php echo $thumb[0];?> <?php echo $thumb[1];?>w, <?php echo $large[0];?> <?php echo $large[1];?>w" sizes="(max-width: <?php echo $thumb[1];?>px) 100vw, <?php echo $thumb[1];?>px">
						</figure>
					<?php } elseif($thumb_size!="no_thumb") {  ?>
					<figure class="img placeholder-image">
					<?php
					$custom_logo_id=get_theme_mod( 'custom_logo' );
					
					if ( $custom_logo_id ) {
						$custom_logo_attr['class']='attachment-'.$thumb_size.' size-'.$thumb_size.' wp-post-image placeholder-image';
						//$custom_logo_attr['alt']='';
						echo wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );
					}
					?>
					</figure>
					<?php } ?>
            <?php
            echo '</article>';
			do_action('genesis_after_entry');