<div class="advanced-search-container">
<form method="get" id="advanced-search" class="advanced-search" role="search" action="<?php echo esc_url(home_url('/')); ?>">
  <input type="hidden" name="search" value="advanced" />
	<?php
	if ( is_category('nieuws') && is_archive() ) {
//		echo '<input type="hidden" name="post_type" value="post" />';
		echo '<input type="hidden" name="cat" value="1" />';
	}
	?>
	<div class="adv-search-box">
		<div class="searchterm">
			<label for="searchterm"><?php _e('Search by keyword',PEP_Theme_ID);?></label>
			<input type="search" id="searchterm" value="<?php if(isset($_GET['s'])) esc_attr_e($_GET['s']);?>" placeholder="<?php _e('Search...',PEP_Theme_ID);?>" name="s">
			<?php do_action('pep_search_description');?>
		  <div class="wp-block-button is-style-rounded">
			<input type="submit" id="searchsubmit" value="<?php _e('Search',PEP_Theme_ID);?>" class="wp-block-button__link has-gutenberg-6-background-color has-gutenberg-4-color">
		  </div>
		</div>

		<div class="tag-list">
			<?php
			$count = count( $tag_list );
			if( $count > 0 ) :
				$n = 1;
				?>
				<fieldset id="filter_tag">
					<legend><?php _e('Expertises',PEP_Theme_ID);?></legend>
					<div class="expertise-columns">
						<?php
						echo '<ul>';
						foreach( $tag_list as $term_id => $label ) {
							?>
							<li><input type="checkbox" name="tag[]" value="<?php esc_attr_e($term_id);?>" <?php if(isset($_GET['tag'])) checked(in_array($term_id,$_GET['tag']));?> id="tag_<?php esc_attr_e($term_id);?>"> <label for="tag_<?php esc_attr_e($term_id);?>"><?php esc_attr_e($label);?></label></li>
							<?php

						}
						echo '</ul>';
						?>
					</div>
				</fieldset>
			<?php endif; ?>
		</div>
	</div>


</form>
</div>
