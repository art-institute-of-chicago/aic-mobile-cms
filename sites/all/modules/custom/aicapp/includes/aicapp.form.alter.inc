<?php
// $Id: aicapp.form.alter.inc$

/**
 * Private callback to alter setup form.
 */
function _aicapp_form_alter(&$form, &$form_state, $form_id) {
  // Add an afterbuild callback
  $form['#after_build'][] = 'aicapp_setup_afterbuild';
  // Only proceed if either object or tour node form.
  if ($form_id != 'object_node_form' && $form_id != 'tour_node_form') {
    return;
  }
  $drupal_path = base_path();
  $dest = drupal_get_path('module', 'aicapp');
  $coords = !empty($form['#node']->field_location[LANGUAGE_NONE][0]['safe_value']) ? $form['#node']->field_location[LANGUAGE_NONE][0]['safe_value'] : 0;
  $map_markup = '<iframe width="650" height="600" frameborder="0" scrolling="no"';
  $map_markup .= ' marginheight="0" marginwidth="0" src="' . $drupal_path;
  $map_markup .= $dest . '/map-page.php?coords=' . $coords . '"></iframe>';
  $form['map'] = array(
    '#markup' => $map_markup,
    '#weight' => 10,
  );
  // Attach CSS & JS assets to the form.
  $form['#attached']['css'] = array(
    drupal_get_path('module', 'aicapp') . '/css/imgareaselect-default.css',
  );
  $form['#attached']['js'][] = array(
    'type' => 'file',
    'data' => drupal_get_path('module', 'aicapp') . '/jquery.imgareaselect.min.js',
  );
  // options for how javascript is added to page.
  $js_options = array(
    'type' => 'inline',
    'scope' => 'footer',
    'group' => JS_THEME,
    'weight' => 5,
  );
  // If node form has a field called "field_thumbnail_image".
  if (!empty($form['#node']->field_thumbnail_image)) {
    $imageToCrop = $form['#node']->field_thumbnail_image;
    // Crop the image.
    cropImage($form, $imageToCrop);
  }
  else {
    $js_to_add = '(function($) { $("div#field-thumbnail-crop-rect-add-more-wrapper .fieldset-description" ).append( "<p>&nbsp;</p><p class=\"warning\">Thumbnail image unavailable.</p>" );
        $("#field-thumbnail-crop-rect-add-more-wrapper input").prop("readonly", "readonly");} (jQuery))';
    // Add JS to the page.
    drupal_add_js($js_to_add, $js_options);
  }

  // If node form has a field called "field_large_image".
  if (!empty($form['#node']->field_large_image)) {
    $imageToCrop = $form['#node']->field_large_image;
    // Set a image size to crop.
    $imageType = 'large';
    cropImage($form, $imageToCrop, $imageType);
  }
  else {
    $js_to_add = '(function($) { $("div#field-large-image-crop-rect-add-more-wrapper .fieldset-description" ).append( "<p>&nbsp;</p><p class=\"warning\">Large image unavailable.</p>" );
        $("#field-large-image-crop-rect-add-more-wrapper input").prop("readonly", "readonly");}(jQuery))';
    // Add JS to the page.
    drupal_add_js($js_to_add, $js_options);
  }
}


/**
 * Private callback to alter setup form.
 */
function _aicapp_form_afterbuild(&$form, &$form_state) {
  // Fix for empty title fields when changing from language neutral.
  if (!empty($form['title_field']) && !empty($form['title_field'][LANGUAGE_NONE])
    && !empty($form['title_field'][LANGUAGE_NONE][0])
    && empty($form['title_field'][LANGUAGE_NONE][0]['#default_value'])
    && !empty($form['title']['#default_value'])) {
    // Add the default title.
    $form['title_field'][LANGUAGE_NONE][0]['value']['#value'] = $form['title']['#default_value'];
  }
  // Fix to set language to 'en' by default.
  if (!empty($form['language']) && $form['language']['#default_value'] === LANGUAGE_NONE
    && $form_state['values']['language'] === LANGUAGE_NONE) {
    // Add EN as the default language.
    $form_state['values']['language'] = 'en';
  }
}
