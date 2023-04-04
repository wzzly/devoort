<?php
use PEP\Template;
?>

<div id="<?php echo $elID;?>" aria-describedby="instructions" class="<?php echo $class;?>">
	<div class="dots-before"></div>
	<p id="instructions" class="screen-reader-text"><?php _e('Use your arrows keys for more','pep');?></p>
	<?php echo $content; ?>
</div>