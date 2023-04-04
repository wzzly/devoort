<div class="address-block clearfix <?php echo $class;?>">
<div itemscope itemtype="https://schema.org/<?php echo get_theme_mod('schema_org','LocalBusiness'); ?>">
  <p class="organization" itemprop="name"><?php echo get_theme_mod('company_name',get_bloginfo('name')); ?></p>
  <span class="subline"><?php echo get_theme_mod('company_subline'); ?></span>
  <meta itemprop="url" content="<?php echo get_site_url();?>">
    
  <?php if(get_theme_mod('company_descr')) { ?>
	<meta itemprop="description" content="<?php echo strip_tags(get_theme_mod('company_descr')); ?>">
  <?php } ?>
  
  <?php if(get_theme_mod('company_pricerange')) { ?>
	<meta itemprop="priceRange" content="<?php echo get_theme_mod('company_pricerange'); ?>">
  <?php } ?>
  
  <?php if(get_theme_mod('company_vat') && $show_vat!=1) { ?>
	<meta itemprop="vatID" content="<?php echo get_theme_mod('company_vat'); ?>">
  <?php } ?>
  
  <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
    <span itemprop="streetAddress"><?php echo get_theme_mod('company_address'); ?></span>
	<div class="postal_city">
		<span itemprop="postalCode"><?php echo get_theme_mod('company_postal'); ?></span>
		<span itemprop="addressLocality"><?php echo get_theme_mod('company_city'); ?></span>
	</div>
  </address>
  
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
  
  if(get_theme_mod('company_kvk') && $show_kvk==1) { ?>
	<span class="kvk"><?php printf(__('KvK: %s','pep'),get_theme_mod('company_kvk')); ?></span>
  <?php } ?>
  
  
  <?php
  if(get_theme_mod('company_vat') && $show_vat==1) { ?>
	<p class="vat_id"><?php _e('Vat id:','pep');?> <span itemprop="vatID"><?php echo get_theme_mod('company_vat'); ?></span></p>
  <?php } 
  
  
  if($show_hours && !empty($hours)) { ?>
	<h4 class="hours-title"><?php _e('Openinghours','pep');?></h4>
	<ul class="openinghours">
	<?php 
	foreach($hours as $day => $time) {
		if($day==$currentDay) { $class='class="current"'; } else {$class='';}
		echo '<li><time itemprop="openingHours" datetime="'.$day.' '.$time['open'].' - '.$time['closed'].'" class="opening-'.$day.'"><span class="day">'.$day_name[$day].'</span> <span class="time">'. $time['open'].'-'.$time['closed'] .'</span></time></li>';
	}
	?>
	</ul>
	<?php
  } elseif(!empty($hours)) { 
	
		foreach($hours as $day => $time) {
			echo '<meta itemprop="openingHours" datetime="'.$day.' '.$time['open'].' - '.$time['closed'].'">';
		}
		
	}
  ?>
  
  
  <?php
  if(get_theme_mod('company_menu') && $show_menu==1) { ?>
	<p class="show-menu"><?php _e('Check out','pep');?> <a itemprop="hasMenu" href="<?php echo esc_url(get_theme_mod('company_menu'));?>"><?php _e('our menu','pep');?></a></p>
  <?php } elseif(get_theme_mod('company_menu')) { ?>
	<meta itemprop="hasMenu" content="<?php echo esc_url(get_theme_mod('company_menu'));?>">
  <?php }?>
  
  <?php
  if(get_theme_mod('company_reservations') && $show_reservations==1 && get_theme_mod('company_reservations')!=1) { ?>
	<p class="show-reservations"><a itemprop="acceptsReservations" href="<?php echo esc_url(get_theme_mod('company_reservations'));?>"><?php _e('Make a reservation','pep');?></a></p>
  <?php } elseif(get_theme_mod('company_reservations')) { ?>
	<meta itemprop="acceptsReservations" content="<?php echo get_theme_mod('company_reservations');?>">
  <?php }?>
  
</div>
</div>