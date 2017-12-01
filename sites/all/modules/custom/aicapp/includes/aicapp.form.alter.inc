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
  // Tour stop and object audio commentary elements get altered here.
  $fields = array(
    array(
      'name' => 'field_tour_stops',
      'add_more' => t('Add another stop'),
      'remove' => t('Remove this stop'),
      'audio_field' => 'field_tour_stop_audio_commentary',
      'translation_label' => t('Audio Commentary Translations'),
    ),
    array(
      'name' => 'field_tour_stops',
      'audio_field' => 'field_tour_stop_audio_bumper',
      'translation_label' => t('Audio Bumper Translations'),
    ),
    array(
      'name' => 'field_audio_commentary',
      'add_more' => t('Add another audio track'),
      'remove' => t('Remove this track'),
      'audio_field' => 'field_audio_commentary_audio',
      'translation_label' => t('Audio Track Translations'),
    ),
  );
  foreach ($fields as $field) {
    if (!empty($form[$field['name']]) && empty($path_part_3)) {
      $langcode = $form[$field['name']]['#language'];
      if (!empty($field['add_more'])) {
        // Change the add annother text to add another stop.
        $form[$field['name']][$langcode]['add_more']['#value'] = $field['add_more'];
      }
      if (!empty($field['remove'])) {
        // Change the remove button text.
        foreach (element_children($form[$field['name']][$langcode]) as $delta) {
          $form[$field['name']][$langcode][$delta]['remove_button']['#value'] = $field['remove'];
        }
      }
      // Show any available translations, as well as a link to create a new translation.
      _aicapp_add_translations($form, $field);
    }
  }
  // Use ajax to update audio elements with the status of available translations.
  $ajax_fields = array(
    // Within the tour node page, we want improve the user experience of choosing
    // audio commentary for a particular tour stop limiting audio choices to ones
    // that have already been linked with a particular artwork object. By adding
    // an #ajax property to each artwork object autocomplete we can refresh the
    // list of autio options available after a object has been selected.
    // Begin with a of few of the fields that are involved in saving tour stops.
    array(
      'field_name' => 'field_tour_stops',
      'field_to_ajaxify' => 'field_tour_stop_object',
      'field_to_update' => 'field_tour_stop_audio_commentary',
      'callback' => 'aicapp_object_limit_js',
    ),
    array(
      'field_name' => 'field_audio_commentary',
      'field_to_ajaxify' => 'field_audio_commentary_audio',
      'callback' => 'aicapp_object_limit_js',
    ),
    array(
      'field_name' => 'field_tour_stops',
      'field_to_ajaxify' => 'field_tour_stop_audio_commentary',
      'callback' => 'aicapp_object_limit_js',
    ),
    array(
      'field_name' => 'field_tour_stops',
      'field_to_ajaxify' => 'field_tour_stop_audio_bumper',
      'callback' => 'aicapp_object_limit_js',
    ),
  );
  foreach ($ajax_fields as $field) {
    $field_name = $field['field_name'];
    $field_ajaxify = $field['field_to_ajaxify'];
    $field_wrapper = !empty($field['field_to_update']) ? $field['field_to_update'] : $field_ajaxify;
    if (!empty($form[$field_name])) {
      // Create a referenced form element to add the #ajax proerties to.
      $field_element =& $form[$field_name];
      // Loop through all tour stop elements.
      foreach (element_children($field_element[LANGUAGE_NONE]) as $delta) {
        if (!is_numeric($delta)) {
          continue;
        }
        $suffix = '';
        // Define a CSS ID that will be replaced in the DOM when the updated
        // select form element is object from the server response.
        $wrapper = 'edit-' . str_replace('_', '-', $field_name) . '-' . LANGUAGE_NONE;
        $wrapper .= '-' . $delta . '-' . str_replace('_', '-', $field_wrapper);
        // Add all the ajax properties to each element.
        if ($field_ajaxify === 'field_tour_stop_audio_commentary') {
          // As this is a selection element, we use a different path to
          // assign the ajax property.
          $field_element[LANGUAGE_NONE][$delta][$field_ajaxify][LANGUAGE_NONE]['#ajax'] = array(
            'callback' => $field['callback'],
            'wrapper' => $wrapper,
            'method' => 'replace',
            'effect' => 'fade',
            'event' => 'change',
          );
          if (!empty($field_element[LANGUAGE_NONE][$delta][$field_wrapper]['#suffix'])) {
            $suffix = $field_element[LANGUAGE_NONE][$delta][$field_wrapper]['#suffix'];
            unset($field_element[LANGUAGE_NONE][$delta][$field_wrapper]['#suffix']);
          }
          // Create a wrapper with the CSS ID to make replacement targeting easier.
          $field_element[LANGUAGE_NONE][$delta][$field_wrapper][LANGUAGE_NONE]['#prefix'] = '<div id="' . $wrapper . '">';
          $field_element[LANGUAGE_NONE][$delta][$field_wrapper][LANGUAGE_NONE]['#suffix'] = $suffix . '</div>';
        }
        else {
          $field_element[LANGUAGE_NONE][$delta][$field_ajaxify][LANGUAGE_NONE][0]['target_id']['#ajax'] = array(
            'callback' => $field['callback'],
            'wrapper' => $wrapper,
            'method' => 'replace',
            'effect' => 'fade',
            'event' => 'change',
          );
          // Create a wrapper with the CSS ID to make replacement targeting easier.
          $field_element[LANGUAGE_NONE][$delta][$field_wrapper][LANGUAGE_NONE]['#prefix'] = '<div id="' . $wrapper . '">';
          $field_element[LANGUAGE_NONE][$delta][$field_wrapper][LANGUAGE_NONE]['#suffix'] = '</div>';
        }

        // Special handling for tour stops field.
        if ($field_name === 'field_tour_stops') {
          // Reset and disable the audio select element unless a arwork object is selected.
          if (empty($field_element[LANGUAGE_NONE][$delta][$field_ajaxify][LANGUAGE_NONE][0]['target_id']['#default_value'])) {
            $element_selector = ':input[name="' . $field_name . '[';
            $element_selector .= LANGUAGE_NONE . '][' . $delta . '][';
            $element_selector .= $field_ajaxify . '][' . LANGUAGE_NONE;
            $element_selector .= '][0][target_id]"]';
            $field_element[LANGUAGE_NONE][$delta][$field_wrapper][LANGUAGE_NONE]['#states'] = array(
              // Only show this field when the value is not '' (empty).
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
  }
}

/**
 * Private callback to alter setup form after translation related items
 * have been added.
 */
function _aicapp_form_afterbuild(&$form, &$form_state) {
  $path_part_3 = arg(3);
  // Add a google map for pages that have a location field.
  if (!empty($form['field_location']) && empty($path_part_3)) {
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
}

function _aicapp_add_translations(&$form, $field) {
  include_once DRUPAL_ROOT . '/includes/language.inc';
  $field_langcode = $form[$field['name']]['#language'];
  foreach (element_children($form[$field['name']][$field_langcode]) as $delta) {
    if (!is_numeric($delta)) {
      continue;
    }
    // Create some defaults that will be used to output a table
    // showing the status of translations for the audio files
    // referenced.
    $header = $rows = array();
    $target_id = $audio_node = $audio_raw = $audio_suffix = NULL;
    $audio_field =& $form[$field['name']][$field_langcode][$delta][$field['audio_field']];
    $field_language = $audio_field['#language'];
    if ($field['audio_field'] === 'field_tour_stop_audio_commentary') {
      if (!empty($audio_field[$field_language]['#value'][0])) {
        $target_id = $audio_field[$field_language]['#value'][0];
      }
      elseif (!empty($audio_field[$field_language]['#default_value'][0])) {
        $target_id = $audio_field[$field_language]['#default_value'][0];
      }
      if (empty($target_id)) {
        // Missing target id, jump to next item in loop.
        continue;
      }
      $fc_entity = entity_load_single('field_collection_item', $target_id);
      if (!empty($fc_entity->field_audio_commentary_audio[LANGUAGE_NONE])) {
        $target_id = $fc_entity->field_audio_commentary_audio[LANGUAGE_NONE][0]['target_id'];
      }
    }
    elseif ($field['name'] === 'field_audio_commentary'
      || $field['audio_field'] === 'field_tour_stop_audio_bumper') {
      if (!empty($audio_field[$field_language][0]['target_id']['#value'])) {
        $audio_raw = $audio_field[$field_language][0]['target_id']['#value'];
      }
      elseif (!empty($audio_field[$field_language][0]['target_id']['#default_value'])) {
        $audio_raw = $audio_field[$field_language][0]['target_id']['#default_value'];
      }
    }
    if (!empty($audio_raw) && empty($target_id)) {
      // Clean off any wrapping quotes.
      $audio_raw = trim($audio_raw, '"\'');
      // This value is in the format of "Node Title (Node ID)", so we
      // split or regex depending on how many '(' are found.
      $bracket_count = substr_count($audio_raw, '(');
      if ($bracket_count === 1) {
        // Only 1 set was found, use split method.
        $parts = explode('(', trim($audio_raw, ')'));
        $target_id = end($parts);
      }
      elseif ($bracket_count > 1) {
        preg_match('/\((\d+)\)$/', $audio_raw, $parts);
        $target_id = !empty($parts[1]) ? $parts[1] : NULL;
      }
      if ($field['name'] === 'field_tour_stops' && $target_id) {
        $fc_entity = entity_load_single('field_collection_item', $target_id);
        if (!empty($fc_entity->field_audio_commentary_audio[LANGUAGE_NONE])) {
          $target_id = $fc_entity->field_audio_commentary_audio[LANGUAGE_NONE][0]['target_id'];
        }
      }
    }
    if (!empty($target_id)) {
      if (!empty($field['translation_label'])) {
        $translation_title = $field['translation_label'];
      }
      else {
        $translation_title = t('Translations');
      }
      $audio_suffix = '<div class="audio-translations">' . $translation_title . '</div>';
      $audio_node = node_load($target_id);
      if (empty($audio_node->field_audio_file) || $audio_node->field_audio_file['#field_language'] === LANGUAGE_NONE) {
        continue;
      }
      $entity_type = 'node';
      $handler = entity_translation_get_handler($entity_type, $audio_node);
      // Ensure $entity holds an entity object and not an id.
      $entity = $handler->getEntity();
      $handler->initPathScheme();

      // Initialize translations if they are empty.
      $translations = $handler->getTranslations();
      if (empty($translations->original)) {
        $handler->initTranslations();
        $handler->saveTranslations();
      }

      // Ensure that we have a coherent status between entity language and field
      // languages.
      if ($handler->initOriginalTranslation()) {
        // FIXME!
        entity_translation_entity_save($entity_type, $entity);
      }
      if (variable_get('aicapp_show_translation_links', FALSE)) {
        $header = array(t('Language'), t('Status'), t('Operations'));
      }
      else {
        $header = array(t('Language'), t('Status'));
      }
      $languages = entity_translation_languages();
      $source = $translations->original;
      $path = $handler->getViewPath();
      $rows = array();

      if (drupal_multilingual()) {
        // If we have a view path defined for the current entity get the switch
        // links based on it.
        if ($path) {
          $links = EntityTranslationDefaultHandler::languageSwitchLinks($path);
        }
        foreach ($languages as $language) {
          $classes = array();
          $options = array();
          $language_name = $language->name;
          $langcode = $language->language;
          $edit_path = $handler->getEditPath($langcode);
          $add_path = "{$handler->getEditPath()}/add/$source/$langcode";

          if ($edit_path) {
            $add_links = EntityTranslationDefaultHandler::languageSwitchLinks($add_path);
            $edit_links = EntityTranslationDefaultHandler::languageSwitchLinks($edit_path);
          }

          if (isset($translations->data[$langcode])) {
            list(, , $bundle) = entity_extract_ids($entity_type, $entity);

            // Existing translation in the translation set: display status.
            $is_original = $langcode == $translations->original;
            $translation = $translations->data[$langcode];
            $label = 'LABEL TO REPLACE';
            $link = isset($links->links[$langcode]['href']) ? $links->links[$langcode] : array('href' => $path, 'language' => $language);

            if ($edit_path && $handler->getAccess('update') && $handler->getTranslationAccess($langcode)) {
              $link = isset($edit_links->links[$langcode]['href']) ? $edit_links->links[$langcode] : array('href' => $edit_path, 'language' => $language);
              $link['query'] = isset($_GET['destination']) ? drupal_get_destination() : FALSE;
              $options[] = l(t('edit'), $link['href'], $link);
            }

            $status = $translation['status'] ? t('Published') : t('Not published');
            $classes[] = $translation['status'] ? 'published' : 'not-published';
            $status .= isset($translation['translate']) && $translation['translate'] ? theme('entity_translation_overview_outdated', array('message' => t('outdated'))) : '';
            $classes[] = isset($translation['translate']) && $translation['translate'] ? 'outdated' : '';

            if ($is_original) {
              $language_name = t('<strong>@language_name</strong>', array('@language_name' => $language_name));
            }
          }
          else {
            // No such translation in the set yet: help user to create it.

            if ($source != $langcode && $handler->getAccess('update') && $handler->getTranslationAccess($langcode)) {
              list(, , $bundle) = entity_extract_ids($entity_type, $entity);
              $translatable = FALSE;

              foreach (field_info_instances($entity_type, $bundle) as $instance) {
                $translation_field_name = $instance['field_name'];
                $translation_field = field_info_field($translation_field_name);
                if ($translation_field['translatable']) {
                  $translatable = TRUE;
                  break;
                }
              }

              $link = isset($add_links->links[$langcode]['href']) ? $add_links->links[$langcode] : array('href' => $add_path, 'language' => $language);
              $link['query'] = isset($_GET['destination']) ? drupal_get_destination() : FALSE;
              $options[] = $translatable ? l(t('add'), $link['href'], $link) : t('No translatable fields');
              $classes[] = $translatable ? '' : 'non-traslatable';
            }
            $status = t('Not translated');
          }
          if (variable_get('aicapp_show_translation_links', FALSE)) {
            $row_data = array($language_name, $status, implode(" | ", $options));
          }
          else  {
            $row_data = array($language_name, $status);
          }
          $rows[] = array(
            'data' => $row_data,
            'class' => $classes,
          );
        }
      }
      // Add the table of translations to the suffix of the element.
      $audio_suffix .= theme('table', array('header' => $header, 'rows' => $rows, 'empty' => t('No translations available.')));
      if ($field['name'] === 'field_audio_commentary'
        || $field['audio_field'] === 'field_tour_stop_audio_bumper') {
        $audio_field[$field_language][0]['target_id']['#suffix'] = $audio_suffix;
      }
      elseif ($field['audio_field'] === 'field_tour_stop_audio_commentary') {
        $audio_field['#suffix'] = $audio_suffix;
      }
    }
  }
}

/**
 * Helper function to crop an Image.
 */
function cropImage(&$form, $imageToCrop, $imageType = 'thumbnail') {
  // initializing image.
  $thumbUrl = $imageToCrop[LANGUAGE_NONE][0]['value'];
  if (empty($thumbUrl)) {
    return;
  }
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
