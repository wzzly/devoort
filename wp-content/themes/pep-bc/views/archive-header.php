<div class="entry-content">
	<?php printf('<div %s>', genesis_attr('archive-header')); ?><div class="inner">

	<?php printf('<header %s>', genesis_attr('entry-header')); ?>

		<?php printf('<h1 %s>', genesis_attr('entry-title')); ?><?php echo $heading;?></h1>

	</header>
	
	<?php do_action('pep_archive_after_inner_header');?>

	
	</div>
</div>
<?php do_action('pep_archive_after_header');?>
</div>