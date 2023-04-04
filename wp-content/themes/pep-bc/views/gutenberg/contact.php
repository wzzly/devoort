<div class="address-block clearfix <?php echo $class;?> <?php if($show_map==true) echo 'show-map';?> <?php if($show_hours && !empty($hours)) echo 'show-hours';?>">
<?php if($hide_all_map==false) { ?>
<div itemscope itemtype="https://schema.org/<?php echo $schema_org; ?>" class="address-block-inner">
<div class="address">
  <?php if($show_name) { ?>
  <p class="organization" itemprop="name"><?php echo $company_name; ?></p>
  <?php } else { ?>
	<meta class="organization" itemprop="name" content="<?php echo $company_name; ?>">
	<?php }  ?>
  <?php if($show_subline && $subline!="") { ?>
  <em class="subline"><?php echo $subline; ?></em>
  <?php } ?>
  <meta itemprop="url" content="<?php if($alt_url) { echo esc_url($alt_url); } else { echo get_site_url(); }?>">
    
  <?php if($description) { ?>
	<meta itemprop="description" content="<?php echo strip_tags($description); ?>">
  <?php } ?>
  
  <?php if($price_range) { ?>
	<meta itemprop="priceRange" content="<?php echo $price_range; ?>">
  <?php } ?>
  
  <?php if($company_vat && $show_vat!=1) { ?>
	<meta itemprop="vatID" content="<?php echo $company_vat; ?>">
  <?php } ?>
  
  <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
    <span itemprop="streetAddress"><?php echo $street; ?></span>
	<div class="postal_city">
		<span itemprop="postalCode"><?php echo $postcode; ?></span>
		<span itemprop="addressLocality"><?php echo $city; ?></span>
	</div>
	<?php if($show_country) { ?>
		<span itemprop="addressCountry"><?php echo $country;?></span>
		<?php } else { ?>
		<meta itemprop="addressCountry" content="<?php echo $country;?>">
		<?php } ?>
  </address>
  
  <?php if($show_phone || $show_email || $show_fax) { ?>
	<?php if($show_phone && $phone) { ?>
		<a class="phone <?php if($show_fax) echo 'prefix-phone';?>" href="tel:<?php echo str_replace(' ','',$phone);?>"><span itemprop="telephone"><?php echo $phone;?></span></a>
	<?php } ?>
	<?php if($show_fax && $fax!="") { ?>
		<span itemprop="faxNumber" class="faxnumber"><?php echo $fax;?></span>
	<?php } ?>
	<?php if($show_email && $email) { ?>
        <a href="mailto: <?php echo $email; ?>" class="email"><span itemprop="email"><?php echo $email; ?></span></a>
	<?php } ?>
 
  <?php } 
  
  if($company_kvk && $show_kvk==1) { ?>
	<span class="kvk"><?php printf(__('KvK: %s','pep'),$company_kvk); ?></span>
  <?php } ?>
  
  
  <?php
  if($company_vat && $show_vat==1) { ?>
	<p class="vat_id"><?php _e('Vat id:','pep');?> <span itemprop="vatID"><?php echo $company_vat; ?></span></p>
  <?php } 
  
  echo '</div>';
  if($show_hours && !empty($hours)) { ?>
	<div class="openinghours-container">
	<h4 class="hours-title"><?php _e('Openinghours','pep');?></h4>
	<ul class="openinghours">
	<?php 
	foreach($hours as $day => $time) {
		if($day==$currentDay) { $class='class="current"'; } else {$class='';}
		if($time['open']!='gesloten') {
			echo '<li '.$class.'><time itemprop="openingHours" datetime="'.$day.' '.$time['open'].' - '.$time['closed'].'" class="opening-'.$day.'"><span class="day">'.$day_name[$day].'</span> <span class="time">'. $time['open'].'-'.$time['closed'] .'</span></time></li>';
		} else {
			echo '<li '.$class.'><span class="day">'.$day_name[$day].'</span> <span class="time">'. $time['open'].'</span></li>';
		}
	}
	?>
	</ul>
	</div>
	<?php
  } elseif(!empty($hours)) { 
	
		foreach($hours as $day => $time) {
			if($time['open']!='gesloten') {
				echo '<meta itemprop="openingHours" datetime="'.$day.' '.$time['open'].' - '.$time['closed'].'">';
			}
		}
		
	}
  ?>
  
  
  <?php
  if($show_menu==1 && $show_reservations==1) {
	  echo '<div class="restaurant-container">';
  }
  if($company_reservations && $show_reservations==1 && $company_reservations!=1) { ?>
	<p class="show-reservations"><a itemprop="acceptsReservations" href="<?php echo esc_url($company_reservations);?>"><?php _e('Make a reservation','pep');?></a></p>
  <?php } elseif($company_reservations) { ?>
	<meta itemprop="acceptsReservations" content="<?php echo $company_reservations;?>">
  <?php }
  if($company_menu && $show_menu==1) { ?>
	<p class="show-menu"><?php _e('Check out','pep');?> <a itemprop="hasMenu" href="<?php echo esc_url($company_menu);?>"><?php _e('our menu','pep');?></a></p>
  <?php } elseif($company_menu) { ?>
	<meta itemprop="hasMenu" content="<?php echo esc_url($company_menu);?>">
  <?php }?>
  
  <?php
  
  if($show_menu==1 && $show_reservations==1) {
	  echo '</div>';
  }
  ?>

</div>
<?php } ?>
<?php if($show_map==true) { ?>
	<div id="map_<?php echo $block_id;?>" class="map" data-target="<?php echo $block_id;?>" data-lat="<?php echo $geodata['lat'];?>" data-lng="<?php echo $geodata['lng'];?>" data-address="<?php echo $geodata['address'];?>" data-marker="<?php echo $geodata['marker'];?>" data-zoom="<?php echo $geodata['zoom'];?>"></div>
	<div id="popup_<?php echo $block_id;?>" class="ol-popup">
		<a href="#" id="popup-closer_<?php echo $block_id;?>" class="ol-popup-closer"></a>
		<div id="popup-content_<?php echo $block_id;?>" class="popup-content"></div>
	</div>
  <?php } ?>
</div>