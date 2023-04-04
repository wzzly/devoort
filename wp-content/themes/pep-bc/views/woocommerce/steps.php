<div id="wc-steps" class="clearfix">
	<div class="progress-bar">
		<div class="progress-inner" style="width: <?php echo $progress;?>%"></div>
	</div>
	
	<span class="step step-1" aria-hidden="true"><?php _e('Cart','pep');?></span>
	<span class="step step-2" aria-hidden="true"><?php _e('Checkout','pep');?></span>
	<span class="step step-3" aria-hidden="true"><?php _e('Confirmation','pep');?></span>
	<span class="screen-reader-text"><?php echo $current;?></span>
</div>
