<?php
namespace PEP;

class MegaMenu extends \Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
		$output .= "\n$indent";
		if($depth==0) {
			$output.="<div class='sub-menu'>";
			$output .= "<ul class='sub-menu-list'>\n";
		} else {
			$output .= "<ul class='sub-menu'>\n";
		}
		
        
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
		if($depth==0) {
			$output .= "$indent</ul></div>\n";
		} else {
			$output .= "$indent</ul>\n";
		}
    } 
	
	
	 /*function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {
    	$object = $item->object;
    	$type = $item->type;
    	$title = $item->title;
		$classes=$item->classes;
		$permalink=$item->url;
		
		if(in_array('second-column',$classes)) {
			$output.='</ul><ul class="sub-menu second-column">';
		}
		
		if(in_array('column-wide',$classes)) {
			$output.='</ul><ul class="sub-menu column-wide">';
		}
			
		$output .= "<li class='" .  implode(" ", $item->classes) . "'>";
        
		//Add SPAN if no Permalink
		if( $permalink && $permalink != '#' ) {
			$output .= '<a href="' . $permalink . '"><span>';
		} else {
			$output .= '<span>';
		}
       
		$output .= $title;
		
		if( $permalink && $permalink != '#' ) {
			$output .= '</span></a>';
		} else {
			$output .= '</span>';
		}
		
		
	}

	
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "\n";
	}
	*/
}
	
?>