<ul class="social-icons">

    <?php if (!empty($linkedin)): ?>
        <li><a href="<?php echo $linkedin; ?>" class="linkedin" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('LinkedIn','pep'));?></span></a></li>
    <?php endif; ?>
	
	<?php if (!empty($instagram)): ?>
        <li><a href="<?php echo $instagram; ?>" class="instagram" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('Instagram','pep'));?></span></a></li>
    <?php endif; ?>
	
	<?php if (!empty($facebook)): ?>
        <li><a href="<?php echo $facebook; ?>" class="facebook" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('Facebook','pep'));?></span></a></li>
    <?php endif; ?>
	
	<?php if (!empty($twitter)): ?>
        <li><a href="https://www.twitter.com/<?php echo $twitter; ?>" class="twitter" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('Twitter','pep'));?></span></a></li>
    <?php endif; ?>
	
	<?php if (!empty($pinterest)): ?>
        <li><a href="<?php echo $pinterest; ?>" class="pinterest" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('Pinterest','pep'));?></span></a></li>
    <?php endif; ?>
	
	<?php if (!empty($youtube)): ?>
        <li><a href="<?php echo $youtube; ?>" class="youtube" target="_blank"><span class="screen-reader-text"><?php printf(__('%s on %s','pep'),get_bloginfo('name'),__('YouTube','pep'));?></span></a></li>
    <?php endif; ?>

</ul>