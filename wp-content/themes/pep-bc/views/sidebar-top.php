<div id="topbar" class="topbar">
	<div class="wrap">
		<?php 
		do_action('pep_before_topbar_widgets'); 
		dynamic_sidebar('top');
		do_action('pep_after_topbar_widgets');
		?>
	</div>
</div>