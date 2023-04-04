<div class="author">
	<figure>
		<?php if(isset($photo['sizes']['profile'])) { 
			echo '<a href="' . $author_link . '">';
		?>
		<?php echo wp_get_attachment_image($photo['ID'],'profile'); ?>
		<?php /*<img src="<?php echo $photo['sizes']['profile'];?>" alt="<?php echo $titel . ' ' . $name;?>" width="<?php echo $photo['sizes']['profile-width'];?>" height="<?php echo $photo['sizes']['profile-height'];?>"> */?>
		<?php
			echo '</a>'; 
		} ?>
	</figure>
	<div class="author-content">
		<h4><?php echo '<a href="' . $author_link . '">' . $titel . ' ' . $name . '</a>';?></h4>
		
		<p class="author-meta"><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a> | <a class="phonenumber" href="tel:<?php echo str_replace(' ','',$phone);?>"><?php echo $phone;?></a></p>
		
		<?php 
		if(!empty($specialismen)) {
			echo '<ul class="specialismen">';
			foreach($specialismen as $tag_id) {
				$tag=get_tag($tag_id);
				echo '<li><a href="'.get_tag_link($tag_id).'">'.$tag->name.'</a></li>';
			}
			echo '</ul>';
		}
		if (is_single()) {
	?>
		<a class="more-articles" href="<?php echo $author_more;?>"><?php _e('More articles',PEP_Theme_ID);?> <span class="screen-reader-text"><?php printf(__('from %s',PEP_Theme_ID),$name);?></span></a>
	<?php } ?>
	</div>
</div>