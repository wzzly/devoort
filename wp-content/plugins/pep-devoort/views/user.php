<div id="user">
	<div class="user-info-container">
		<ul class="user-info">
		<?php 	
		foreach($user['box'] as $label=>$value) {
			if($value!="") echo '<li class="'.sanitize_title($label).'"><strong>'.$label.': </strong>'.$value.'</li>';
				
		}
	?>
		</ul>
	
		<ul class="user-social">
		<?php 	
		foreach($user['social'] as $label=>$value) {
			if($value!="") echo '<li class="'.sanitize_title($label).'"><a class="flaticon flaticon-'.sanitize_title($label).'" href="'.$value.'" target="_blank"><span class="screen-reader-text">'.sprintf(__('Follow us on %s','pep'),$label).'</span></a></li>';
		}
		?>
		</ul>
	</div>
	<?php
if(!empty($user['foto']) && isset($user['foto']['sizes']['large'])) {
	$name=get_the_author_meta('display_name',$author_id);
	echo '<img src="'.$user['foto']['sizes']['large'].'" alt="'.$name.'" width="'.$user['foto']['sizes']['large-width'].'" height="'.$user['foto']['sizes']['large-height'].'">';
}
?>
</div>

<div id="userdetails">
	<?php 
	$i=0;
	foreach($user['info'] as $label=>$value) {
		
		if(empty($value)) continue;
		
		if($i==1 && $user['foto3']!="") {
			echo '<div class="image-notification"><small>'.__('Text continues below the photo',PEP_Theme_ID).'</small></div>';
			echo wp_get_attachment_image($user['foto3'],'full');
		}
		
		echo '<h3>'.$label.'</h3>';
		if(is_array($value)) {
			echo '<ul>';
			foreach($value as $tag_id) {
				$tag=get_tag($tag_id);
				echo '<li><a href="'.esc_url(get_tag_link($tag_id)).'">'.$tag->name.'</a></li>';
			}
			echo '</ul>';
			
		} else {
			echo $value;
		}
		$i++;
	}
	?>
</div>