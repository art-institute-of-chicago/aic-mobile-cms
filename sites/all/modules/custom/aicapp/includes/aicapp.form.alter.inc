<?php

/**
 * @file aicapp.form.alter.inc.
 */

/**
 * Private callback to alter setup form.
 */
function _aicapp_form_alter(&$form, &$form_state, $form_id) {
  // Add an afterbuild callback.
  $form['#after_build'][] = 'aicapp_setup_afterbuild';
  // Only proceed if either object or tour node form.
  if ($form_id != 'object_node_form' && $form_id != 'tour_node_form') {
    return;
  }
  // Attach CSS & JS assets to the form.
  $form['#attached']['css'] = array(
    drupal_get_path('module', 'aicapp') . '/css/imgareaselect-default.css',
  );
  $form['#attached']['js'][] = array(
    'type' => 'file',
    'data' => drupal_get_path('module', 'aicapp') . '/jquery.imgareaselect.min.js',
  );
  // Options for how javascript is added to page.
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
  // Within the tour node page, we want improve the user experience of choosing
  // audio commentary for a particular tour stop limiting audio choices to ones
  // that have already been linked with a particular artwork object. By adding
  // an #ajax property to each artwork object autocomplete we can refresh the
  // list of autio options available after a object has been selected.
  // Begin with a of few of the fields that are involved in saving tour stops.
  $field_name_stops = 'field_tour_stops';
  $field_name_stops_object = 'field_tour_stop_object';
  $field_name_stops_audio = 'field_tour_stop_audio_commentary';
  // Check that the field_tour_stops field collection field is present.
  if (!empty($form[$field_name_stops])) {
    // Create a referenced form element to add the #ajax proerties to.
    $stops_element =& $form[$field_name_stops];
    // Loop through all tour stop elements.
    foreach (element_children($stops_element[LANGUAGE_NONE]) as $delta) {
      if (!is_numeric($delta)) {
        continue;
      }
      // Define a CSS ID that will be replaced in the DOM when the updated
      // select form element is object from the server response.
      $audio_commentary_id = 'edit-field-tour-stops-und-' . $delta . '-field-tour-stop-audio-commentary';
      // Add all the ajax properties to each stop element.
      $stops_element[LANGUAGE_NONE][$delta][$field_name_stops_object][LANGUAGE_NONE][0]['target_id']['#ajax'] = array(
        'callback' => 'aicapp_object_limit_js',
        'wrapper' => $audio_commentary_id,
        'method' => 'replace',
        'effect' => 'fade',
        'event' => 'change',
      );
      // Create a wrapper with the CSS ID to make replacement targeting easier.
      $stops_element[LANGUAGE_NONE][$delta][$field_name_stops_audio][LANGUAGE_NONE]['#prefix'] = '<div id="' . $audio_commentary_id . '">';
      $stops_element[LANGUAGE_NONE][$delta][$field_name_stops_audio][LANGUAGE_NONE]['#suffix'] = '</div>';

      // Reset and disable the audio select element unless a arwork object is selected.
      if (empty($stops_element[LANGUAGE_NONE][$delta][$field_name_stops_object][LANGUAGE_NONE][0]['target_id']['#default_value'])) {
        $element_selector = ':input[name="' . $field_name_stops . '[';
        $element_selector .= LANGUAGE_NONE . '][' . $delta . '][';
        $element_selector .= $field_name_stops_object . '][' . LANGUAGE_NONE;
        $element_selector .= '][0][target_id]"]';
        $stops_element[LANGUAGE_NONE][$delta][$field_name_stops_audio][LANGUAGE_NONE]['#states'] = array(
          // Only show this field when the 'toggle_me' checkbox is enabled.
          'disabled' => array(
            $element_selector => array(
              array('value' => ''),
            ),
          ),
        );
      }
    }
  }
}

/**
 * Private callback to alter setup form after translation related items
 * have been added.
 */
function _aicapp_form_afterbuild(&$form, &$form_state) {
  // Add a google map for pages that have a location field.
  if (!empty($form['field_location']) && empty(arg(3))) {
    $coords = !empty($form['#node']->field_location[LANGUAGE_NONE][0]['safe_value']) ? $form['#node']->field_location[LANGUAGE_NONE][0]['safe_value'] : 0;
    $map_markup = '<iframe width="650" height="600" frameborder="0" scrolling="no"';
    $map_markup .= ' marginheight="0" marginwidth="0" src="' . base_path();
    $map_markup .= drupal_get_path('module', 'aicapp') . '/map-page.php?';
    $map_markup .= 'coords=' . $coords . '"></iframe>';
    $form['amap'] = array(
      '#markup' => $map_markup,
      '#weight' => $form['field_location']['#weight'],
    );
    $form['field_location']['#weight'] = $form['amap']['#weight'] + 1;
  }
  // Fix for empty title fields when changing from language neutral.
  if (!empty($form['title_field']) && !empty($form['title_field'][LANGUAGE_NONE])
    && !empty($form['title_field'][LANGUAGE_NONE][0])
    && empty($form['title_field'][LANGUAGE_NONE][0]['#default_value'])
    && !empty($form['title']['#default_value'])) {
    // Add the default title.
    $form['title_field'][LANGUAGE_NONE][0]['value']['#value'] = check_plain($form['title']['#default_value']);
  }
  // Fix to set language to AICAPP_DEFAULT_LANG, which 'en' by default.
  if (!empty($form['language']) && !empty($form['language']['#default_value'])
    && $form['language']['#default_value'] === LANGUAGE_NONE
    && $form_state['values']['language'] === LANGUAGE_NONE) {
    // Add EN as the default language.
    $form_state['values']['language'] = AICAPP_DEFAULT_LANG;
  }
  // Tour stop element alterations.
  $field_name_stops = 'field_tour_stops';
  if (!empty($form[$field_name_stops]) && empty(arg(3))) {
    $langcode = $form[$field_name_stops]['#language'];
    // Change the add annother text to add another stop.
    $form[$field_name_stops][$langcode]['add_more']['#value'] = t('Add another stop');
  }
  elseif (!empty($form['field_audio_commentary']) && empty(arg(3))) {
    $form['field_audio_commentary'][$form['field_audio_commentary']['#language']]['add_more']['#value'] = t('Add another audio track');
  }
}

/**
 * Helper function to crop an Image.
 */
function cropImage(&$form, $imageToCrop, $imageType = 'thumbnail') {
  // initializing image.
  $thumbUrl = $imageToCrop[LANGUAGE_NONE][0]['value'];
  $thumbImageinfo = getimagesize($thumbUrl);
  $thumbWidth = $thumbImageinfo[0];
  $thumbHeight = $thumbImageinfo[1];
  $thumbX1 = 0;
  $thumbY1 = 0;
  $thumbX2 = $thumbWidth;
  $thumbY2 = $thumbHeight;
  $thumbValues = array(
    'x1' => 0,
    'y1' => 0,
    'x2' => 0,
    'y2' => 0,
    'width' => 0,
    'height' => 0,
  );
  // If cropping a thumnail image.
  if ($imageType == 'thumbnail') {
    $formName = 'field_thumbnail_crop_rect';
    $formID = 'field-thumbnail';
    $formValue = 'field_thumbnail';
    $aspectRatio = variable_get('aicapp_image_thumb_crop_aspect', '1:1');
    $resizable = (variable_get('aicapp_image_thumb_crop_lock', 'false')) ? 'true' : 'false';
    // original crops for thumbnail image.
    if ($thumbWidth > $thumbHeight) {
      // if landscape image
      $thumbX1 = round(($thumbWidth - $thumbHeight) / 2);
      $thumbX2 = round($thumbX1 + $thumbHeight);
    }
    elseif ($thumbHeight > $thumbWidth) {
      // if portrait image.
      $thumbY1 = round(($thumbHeight - $thumbWidth) / 2);
      $thumbY2 = round($thumbY1 + $thumbWidth);
    }
  }
  elseif ($imageType == 'large') {
    $formName = 'field_large_image_crop_rect';
    $formID = 'field-large-image';
    $formValue = 'field_large_image';
    $aspectRatio = variable_get('aicapp_image_large_crop_aspect', '1.82:1');
    $resizable = (variable_get('aicapp_image_large_crop_lock', 'true')) ? 'true' : 'false';
    // Original crops for large image.
    $split_ratio = explode(':', trim($aspectRatio));
    $ratio = !empty($split_ratio[0]) ? $split_ratio[0] : 1.82;
    $ratio = $ratio / (empty($split_ratio[1]) ? $split_ratio[1] : 1);
    $thumbCropHeight = round($thumbWidth / $ratio);
    $thumbY1 = round(($thumbHeight / 2) - ($thumbCropHeight / 2));
    $thumbY2 = round($thumbY1 + $thumbCropHeight);
  }

  if (isset($imageToCrop[LANGUAGE_NONE][0])) {
    $items = field_get_items('node', $form['#node'], $formValue . '_crop_rect');
    if ($items) {
      // Get default crop values.
      foreach ($items as $itemid) {
        $item = field_collection_field_get_entity($itemid);
      }
      if ($item->field_x) {
        // if an x value exists, get all the values.
        getDefaults($items, $thumbValues, $imageType);
      }
      else {
        // otherwise set default crop values.
        setDefaults($form, $formName, $imageType, $thumbX1, $thumbY1, $thumbX2, $thumbY2, $thumbWidth, $thumbHeight, $thumbValues);
      }
    }
    else {
      // set default crop values.
      setDefaults($form, $formName, $imageType, $thumbX1, $thumbY1, $thumbX2, $thumbY2, $thumbWidth, $thumbHeight, $thumbValues);
    }
  }
  // Add the Javascript to the page.
  // @TODO use a file instead of adding JS inline. Only pass configurations for the file to act upon.
  drupal_add_js(
    '(function ($) {
    $(document).ready(function () {
      // display thumbnail and large images
      $( "div#' . $formID . '-crop-rect-add-more-wrapper .fieldset-description" ).append( "<p>&nbsp;</p><p><img id=\"' . $formValue . '\" src=\"' . $thumbUrl . '\" /></p>" );
      // make fields readonly
      $("#field-thumbnail-crop-rect-add-more-wrapper input").prop("readonly", "readonly");
      $("#field-large-image-crop-rect-add-more-wrapper input").prop("readonly", "readonly");

      var imageType = "' . $imageType . '";
      var imageWidth = "' . $thumbValues['width'] . '";
      var imageHeight = "' . $thumbValues['height'] . '";
      if (imageType == "large") {
        var imageWidthMin = 364;
        var imageHeightMin = 200;
      }
      else {
        var imageWidthMin = "' . $thumbValues['width'] . '";
        var imageHeightMin = "' . $thumbValues['height'] . '";
      }
      if ((imageType == "large" && (imageWidth < 364 || imageHeight < 200)) || (imageType == "thumbnail" && imageWidth < 50)) {
        $( "div#' . $formID . '-crop-rect-add-more-wrapper .fieldset-wrapper" ).append( "<p class=\"warning\">WARNING: THIS IMAGE IS SMALLER THAN THE RECOMMENDED MINIMUM AND WILL BE SCALED TO FIT IN THE APP.</p>" );
      }
      $("img#' . $formValue . '").imgAreaSelect({
      x1: ' . $thumbValues['x1'] . ', y1: ' . $thumbValues['y1'] . ', x2: ' . $thumbValues['x2'] . ', y2: ' . $thumbValues['y2'] . ',
      aspectRatio: "' . $aspectRatio . '",
      persistent: true,
      handles: true,
      minWidth: imageWidthMin,
      minHeight: imageHeightMin,
      resizable: ' . $resizable . ',
      onSelectEnd: function (img, selection) {
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_x][und][0][value]\"]").val(selection.x1);
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_y][und][0][value]\"]").val(selection.y1);
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_x2][und][0][value]\"]").val(selection.x2);
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_y2][und][0][value]\"]").val(selection.y2);
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_width][und][0][value]\"]").val(selection.width);
        $("input[name=\"' . $formValue . '_crop_rect[und][0][field_height][und][0][value]\"]").val(selection.height);
        }
      });
    });
    })(jQuery);',
    array(
      'type' => 'inline',
      'scope' => 'footer',
      'group' => JS_THEME,
      'weight' => 15,
    )
  );
}

/**
 * Helper function to get crop defaults.
 */
function getDefaults($items, &$thumbValues, $imageType) {
  foreach ($items as $itemid) {
    $item = field_collection_field_get_entity($itemid);
    $thumbValues['x1'] = $item->field_x[LANGUAGE_NONE][0]['value'];
    $thumbValues['y1'] = $item->field_y[LANGUAGE_NONE][0]['value'];
    $thumbValues['x2'] = $item->field_x2[LANGUAGE_NONE][0]['value'];
    $thumbValues['y2'] = $item->field_y2[LANGUAGE_NONE][0]['value'];
    $thumbValues['width'] = $item->field_width[LANGUAGE_NONE][0]['value'];
    $thumbValues['height'] = $item->field_height[LANGUAGE_NONE][0]['value'];
  }
}

/**
 * Helper function to set crop defaults.
 */
function setDefaults(&$form, $formName, $imageType, $thumbX1, $thumbY1, $thumbX2, $thumbY2, $thumbWidth, $thumbHeight, &$thumbValues) {
  $thumbValues['x1'] = $thumbX1;
  $form[$formName][LANGUAGE_NONE][0]['field_x'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x1'];
  $thumbValues['y1'] = $thumbY1;
  $form[$formName][LANGUAGE_NONE][0]['field_y'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['y1'];
  $thumbValues['x2'] = $thumbX2;
  $form[$formName][LANGUAGE_NONE][0]['field_x2'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
  $thumbValues['y2'] = $thumbY2;
  $form[$formName][LANGUAGE_NONE][0]['field_y2'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['y2'];
  if ($imageType == 'thumbnail') {
    if ($thumbWidth > $thumbHeight) {
      // if landscape image
      $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['y2'];
      $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['y2'];
    }
    elseif ($thumbHeight > $thumbWidth) {
      // if portrait image
      $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
      $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
    }
    else {
      // perfect square
      $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
      $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
    }
    $thumbValues['width'] = $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'];
    $thumbValues['height'] = $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'];
  }
  elseif ($imageType == 'large') {
    $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['x2'];
    $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'] = $thumbValues['y2'] - $thumbValues['y1'];
    $thumbValues['width'] = $form[$formName][LANGUAGE_NONE][0]['field_width'][LANGUAGE_NONE][0]['value']['#value'];
    $thumbValues['height'] = $form[$formName][LANGUAGE_NONE][0]['field_height'][LANGUAGE_NONE][0]['value']['#value'];
  }
}
