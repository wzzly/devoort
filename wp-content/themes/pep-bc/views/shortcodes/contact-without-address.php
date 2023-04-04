<div class="address-block clearfix <?php echo $class;?>">
  <?php if($show_tel || $show_email) { ?>
	<?php if($show_tel && get_theme_mod('company_phone')) { ?>
		<a class="phone" href="tel:<?php echo str_replace(' ','',get_theme_mod('company_phone'));?>"><span itemprop="telephone"><?php echo get_theme_mod('company_phone');?></span></a>
	<?php } ?>
	<?php if($show_fax && get_theme_mod('company_fax')) { ?>
		<a class="faxNumber" href="tel:<?php echo str_replace(' ','',get_theme_mod('company_fax'));?>"><span itemprop="faxNumber"><?php echo get_theme_mod('company_fax');?></span></a>
	<?php } ?>
	<?php if($show_email && get_theme_mod('company_email')) { ?>
        <a href="mailto: <?php echo get_theme_mod('company_email', get_bloginfo('admin_email')); ?>" class="email"><span itemprop="email"><?php echo get_theme_mod('company_email', get_bloginfo('admin_email')); ?></span></a>
	<?php } ?>
 
  <?php } 
  
  if($show_hours && !empty($hours)) { ?>
	<h4 class="hours-title"><?php _e('Openinghours','pep');?></h4>
	<ul class="openinghours">
	<?php 
	foreach($hours as $day => $time) {
		echo '<li><time itemprop="openingHours" datetime="'.$day.' '.$time['open'].' - '.$time['closed'].'"><span class="day">'.$day_name[$day].'</span> <span class="time">'. $time['open'].'-'.$time['closed'] .'</span></time></li>';
	}
	?>
	</ul>
	<?php
  } 
  ?>
  
  <?php
  if(get_theme_mod('company_vat') && $show_vat==1) { ?>
	<p class="vat_id"><?php _e('Vat id:','pep');?> <span itemprop="vatID"><?php echo get_theme_mod('company_vat'); ?></span></p>
  <?php } ?>
  
  <?php
  if(get_theme_mod('company_menu') && $show_menu==1) { ?>
	<p class="show-menu"><?php _e('Check out','pep');?> <a itemprop="hasMenu" href="<?php echo esc_url(get_theme_mod('company_menu'));?>"><?php _e('our menu','pep');?></a></p>
  <?php } ?>
  
  <?php
  if(get_theme_mod('company_reservations') && $show_reservations==1 && get_theme_mod('company_reservations')!=1) { ?>
	<p class="show-reservations"><a itemprop="acceptsReservations" href="<?php echo esc_url(get_theme_mod('company_reservations'));?>"><?php _e('Make a reservation','pep');?></a></p>
  <?php } ?>
  
</div>