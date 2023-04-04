<div class="sitemap sitemap-<?php echo $post_type->name;?>">
	<h2><?php _e($post_type->label,'pep');?></h2>
	<ul class="sitemap-list">
		<?php 
		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<li><p><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title();?></a> </p></li>
			<?php
		}
	?>
	</ul>
</div>