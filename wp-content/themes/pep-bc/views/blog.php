<?php
/*
 * New blog loop template
 */
if (have_posts()) :

	?>
	<div class="archive-wrapper cards" aria-live="polite" role="region" id="cards" aria-label="<?php echo $aria_label;?>">
	<div>
	<?php
    do_action('genesis_before_while');

        while (have_posts()) : the_post();

			include(THEME_VIEWS_PATH.'/card.php');

        endwhile; //* end of one post

        ?>

        
	</div>
</div>
<?php

        do_action('genesis_after_endwhile');

	?>
<?php

    else : //* if no posts exist

        do_action('genesis_loop_else');

    endif; //* end loop
?>