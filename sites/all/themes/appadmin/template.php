<?php
/**
 * Implements template_preprocess_region
 */
function appadmin_preprocess_region(&$vars) { 
    $vars['theme_path'] = drupal_get_path('theme', 'appadmin'); 
    //figure out what page am I on? 
    $elements = $vars['elements'];
    
    
  //add foundation row class to certain regions
  if(in_array('region__content', $vars['theme_hook_suggestions'])) { 
	//$vars['classes_array'][] = 'row'; 
  }
}
/**
 * Implements template_preprocess_block
 */
function appadmin_preprocess_block(&$vars) { 
  //add foundation columns class to certain blocks
  $delta = $vars['elements']['#block']->delta;
  $region = $vars['elements']['#block']->region; 
  $module = $vars['elements']['#block']->module; 
 
  //two-column blocks
  if(($delta == 4) && $module == 'block')  {  
     //$vars['classes_array'][] = 'large-6 columns'; 
  }
  
}

/**
 * Implements template_preprocess_block
 */
function appadmin_preprocess_page(&$vars) { 
  // Top bar menus.
  // If you want to turn off or on the drop menus, use the variable in HOOK_links__topbar_main_menu() - below..
  $vars['top_bar_main_menu'] = '';
  if (!empty($vars['main_menu'])) {
    $vars['top_bar_main_menu'] = theme('links__topbar_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'id' => 'main-menu',
        'class' => array('main-nav')
       ),
      'heading' => array(),
    ));
  }
}


function appadmin_form_alter(&$form, &$form_state, $form_id) {
 // Sexy submit buttons
 if (!empty($form['actions']) && !empty($form['actions']['submit'])) {
   $form['actions']['submit']['#attributes'] = array('class' => array('primary', 'button', 'radius'));
   $form['actions']['preview']['#attributes'] = array('class' => array('secondary', 'button', 'radius'));
   $form['actions']['delete']['#attributes'] = array('class' => array('secondary', 'button', 'radius'));
 }
 if($form_id == 'views_exposed_form') { 
    $form['submit']['#attributes'] = array('class' => array('primary', 'button', 'radius')); 
 }
    
 if($form_id == 'search_block_form') { 
    $form['search_block_form']['#attributes'] = array('placeholder' => t('Search for something...'));
 }
}



/**
 * Implements template_preprocess_block
 */
function appadmin_links__menu_main_menu(&$vars) { 
	//d($vars);
}


//returns an array of taxonomy terms given the vocabulary id
function _get_terms($vocab_id, $expand = false){
	//get the term list				
	$result = db_query("SELECT td.tid, td.name, td.vid AS tvid, td.weight
		FROM taxonomy_term_data td 
		WHERE td.vid = :vid
		ORDER BY td.weight ASC", array(':vid' => $vocab_id));
	$terms = array();
	foreach($result as $item) { 
		$terms[] = taxonomy_term_load($item->tid);
	}
	return $terms; 
}	


//edit the main-menu links to be marked-up for foundation CSS top-bar
function appadmin_links__topbar_main_menu($vars) {
    $show_drop_menus = false;
    $pid = variable_get('menu_main_links_source', 'main-menu');
    $tree = menu_tree($pid);
	//$output = drupal_render($tree);
	$output = array('<ul id="main-menu" class="menu">'); 
	foreach($tree as $item) {
		//set up 1st-level nav links (main top bar navigation)
		if(!empty($item['#href'])) { 
			_build_li_links($output, $item);
			
			//set up 2nd level drop-menu links
			if($show_drop_menus && !empty($item['#below'])){
				$output[] = '<ul class="submenu">';
				foreach($item['#below'] as $sub_item) { 
					if(!empty($sub_item['#href'])) { 
						_build_li_links($output, $sub_item);
						
						//set up 3rd level links						
						if(!empty($sub_item['#below'])){
							$output[] = '<ul class="submenu">';
							foreach($sub_item['#below'] as $sub_sub_item) { 
								if(!empty($sub_sub_item['#href'])) { 
									_build_li_links($output, $sub_sub_item); 
								}
							}
							$output[] = '</ul>'; //close 3rd level
						}
						$output[] = '</li>';
					}
				}
				$output[] = '</ul>'; //close 2nd level
			}		
			$output[] = '</li>';
		}
	}
	$output[] = '</ul>'; //close 1st level
	return implode(' ',$output);
}


//output the <li> and <a> tags for the given drop menu links
//don't close the <li> because there might be another level
function _build_li_links(&$output,$items){
	$c = _build_classes_str($items, true); 
	$drupal_path = base_path();
	$pth = $drupal_path.drupal_get_path_alias($items['#href']);
	if(!array_search('last',$items['#attributes']['class']) && !array_search('first',$items['#attributes']['class'])) { 
		//$output[] = '<li class="divider"></li>'; 
	}
	$output[] = '<li class="'.$c['class'].' button"><a class="'.$c['aClass'].'" href="'.$pth.'">'.$items['#title'].'</a>'; 
}

// pull the classes from drupal's class array, and add in Foundation classes for the li and a tags. 
// return array with <li> class string, and <a> class string
function _build_classes_str($item, $dropdown = false){
	$classes = $item['#attributes']['class'];
	foreach($classes as $cls) { 
		if($cls == 'expanded') { 
			$classes[] = 'has-dropdown not-click';
		}
		if($cls == 'active-trail') { 
			$classes[] = 'active';
		}
	}
	$aclasses = array(); 
	if(!empty($item['#localized_options']['attributes']['class'])){ 
		$aclasses = $item['#localized_options']['attributes']['class']; 
	}
	return array('class' => implode(' ',$classes), 'aClass' => implode(' ',$aclasses));
}