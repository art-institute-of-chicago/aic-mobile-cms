<?php

/**
 * @file aicapp.admin.inc.
 */

/**
 * Page callback to redirect from old configuration path to new.
 */
function aicapp_admin_redirect() {
  drupal_goto('admin/config/system/aic');
}

/**
 * Admin setting form callback.
 */
function aicapp_admin() {
  $form = array();
  $form['aicapp_gmap_key'] = array(
    '#type' => 'textfield',
    '#title' => t('Google Maps API Key'),
    '#default_value' => variable_get('aicapp_gmap_key', ''),
    '#size' => 200,
    '#maxlength' => 256,
    '#description' => t("Go to https:// developers.google.com/maps/documentation/javascript/get-api-key to get an API key"),
  );
  $form['aicapp_images'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('Image Cropping'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_object'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#title' => t('Object Node Cropping Defaults'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_full_crop'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Full image crop dimensions'),
    '#default_value' => variable_get('aicapp_image_full_crop', '!800,800'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_crop_aspect'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Full image crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_large_crop_aspect', '1.82:1'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_crop_lock'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow full image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_large_crop_lock', 0)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_thumb_crop'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop dimensions'),
    '#default_value' => variable_get('aicapp_image_thumb_crop', ' !112,112'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_thumb_crop_aspect'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_thumb_crop_aspect', '1:1'),
    '#description' => t('The aspect ratio of the tool used to crop.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_thumb_crop_lock'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow thumnail image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_thumb_crop_lock', 1)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_tour'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#title' => t('Tour Node Cropping Defaults'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_full_crop_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Full image crop dimensions'),
    '#default_value' => variable_get('aicapp_image_full_crop_tour', '!800,800'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_large_crop_aspect_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Full image crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_large_crop_aspect_tour', '1.82:1'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_large_crop_lock_tour'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow full image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_large_crop_lock_tour', 0)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_thumb_crop_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop dimensions'),
    '#default_value' => variable_get('aicapp_image_thumb_crop_tour', ' !112,112'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_thumb_crop_aspect_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_thumb_crop_aspect_tour', '1:1'),
    '#description' => t('The aspect ratio of the tool used to crop.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_thumb_crop_lock_tour'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow thumnail image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_thumb_crop_lock_tour', 1)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  return system_settings_form($form);
}

/**
 * Admin api form callback.
 */
function aicapp_admin_api() {
  // Image API
  $form['aicapp_image_api'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('Image API'),
    '#tree' => FALSE,
  );
  $form['aicapp_image_api']['aicapp_image_server_url'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Image Server URL'),
    '#default_value' => variable_get('aicapp_image_server_url', 'https://lakeimagesweb.artic.edu/iiif/2'),
    '#description' => t('URL to the Image Server.'),
  );
  // Data aggregrator API
  $form['aicapp_data_aggregator'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('Data Aggregator API'),
    '#tree' => FALSE,
  );
  $form['aicapp_data_aggregator']['aicapp_data_api_host'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Data API HOST URL'),
    '#default_value' => variable_get('aicapp_data_api_host', 'http://aggregator-data-test.artic.edu'),
    '#description' => t('.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_events'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Events Endpoint.'),
    '#default_value' => variable_get('aicapp_data_endpoint_events', '/api/v1/events'),
    '#description' => t('Query for an event via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_exhibitions'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Exhibitions Endpoint.'),
    '#default_value' => variable_get('aicapp_data_endpoint_exhibitions', '/api/v1/exhibitions'),
    '#description' => t('Query for an exhibition via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_artworks'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Artworks endpoint.'),
    '#default_value' => variable_get('aicapp_data_endpoint_artworks', '/api/v1/artworks'),
    '#description' => t('Query for an artwork via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_galleries'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Galleries endpoint.'),
    '#default_value' => variable_get('aicapp_data_endpoint_galleries', '/api/v1/galleries'),
    '#description' => t('Query for a gallery via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_images'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Images endpoint.'),
    '#default_value' => variable_get('aicapp_data_endpoint_images', '/api/v2/images'),
    '#description' => t('Query for a image via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['import_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import Remote Data'),
    '#suffix' => t('Click this button to load remote events and exhitions.'),
  );
  $form['aicapp_deprecated'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Deprecated'),
    '#tree' => FALSE,
  );
  $form['aicapp_deprecated']['aicapp_artwork_query'] = array(
    '#type' => 'textarea',
    '#title' => t('Artwork query URL'),
    '#default_value' => variable_get('aicapp_artwork_query', ''),
    '#rows' => 4,
    '#description' => t("Use {{rows}}, {{start}}, {{term}}, and {{value}} to note where different values should be used."),
  );
  $form['aicapp_deprecated']['aicapp_gallery_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Gallery query URL'),
    '#default_value' => variable_get('aicapp_gallery_query', ''),
    '#size' => 200,
    '#maxlength' => 256,
    '#description' => t("Use {{rows}}, {{start}}, {{term}}, and {{value}} to note where different values should be used."),
  );
  return system_settings_form($form);
}

/**
 * Admin setting form callback.
 */
function aicapp_admin_migrate() {
  $form = array();
  $all_migrations_complete = variable_get('aicapp_migrate_v1_v2', FALSE);
  // Data migration between v1 and v2.
  // @TODO show an overview of what has and hasn't been migrated.
  if (0) {
    $all_migrations_complete = TRUE;
    $overview = t('All migrations complete');
  }
  $form['aicapp_data'] = array(
    '#type' => 'fieldset',
    '#collapsible' => $all_migrations_complete,
    '#collapsed' => $all_migrations_complete,
    '#title' => t('Data migration'),
    '#tree' => TRUE,
  );
  if ($all_migrations_complete) {
    $form['aicapp_data']['migration_repeat'] = array(
      '#prefix' => t('All data has been migrated.'),
      '#type' => 'submit',
      '#value' => t('Repeat Migration'),
    );
  }
  else {
    // @TODO check which types have been or haven't been migrated. Only list those
    // that still require migration.
    $options = array(
      'audio' => t('Audio'),
      'object' => t('Objects'),
      'tour' => t('Tours'),
    );
    $form['aicapp_data']['migration_type'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Select content type(s) to migrate:'),
      '#options' => $options,
      '#default_value' => array('audio'),
      '#description' => t('Choose content type(s) to migrate.'),
    );
    $form['aicapp_data']['migration_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Migrate Data'),
    );
  }
  return $form;
}

/**
 * Admin setting validation callback.
 */
function aicapp_admin_validate($form, &$form_state) {
  if (strpos($form_state['values']['aicapp_image_large_crop_aspect'], ':') === FALSE && !empty($form_state['values']['aicapp_image_large_crop_aspect'])) {
    $form_state['values']['aicapp_image_large_crop_aspect'] = $form_state['values']['aicapp_image_large_crop_aspect'] . ':1';
  }
  if (strpos($form_state['values']['aicapp_image_thumb_crop_aspect'], ':') === FALSE && !empty($form_state['values']['aicapp_image_thumb_crop_aspect'])) {
    $form_state['values']['aicapp_image_thumb_crop_aspect'] = $form_state['values']['aicapp_image_thumb_crop_aspect'] . ':1';
  }
  $form_state['values']['aicapp_image_full_crop'] = trim($form_state['values']['aicapp_image_full_crop']);
  $form_state['values']['aicapp_image_thumb_crop'] = trim($form_state['values']['aicapp_image_thumb_crop']);

  // Save the variable as a lowercase string for use in JS.
  if ($form_state['values']['aicapp_image_thumb_crop_lock'] == FALSE) {
    // Leave as lowercase for use in JS.
    $form_state['values']['aicapp_image_thumb_crop_lock'] = false;
  }
  else {
    // Leave as lowercase for use in JS.
    $form_state['values']['aicapp_image_thumb_crop_lock'] = 'true';
  }
  // Save the variable as a lowercase string for use in JS.
  if ($form_state['values']['aicapp_image_large_crop_lock'] == FALSE) {
    // Leave as lowercase for use in JS.
    $form_state['values']['aicapp_image_large_crop_lock'] = false;
  }
  else {
    $form_state['values']['aicapp_image_large_crop_lock'] = 'true';
  }
  // @TODO move to submit.
  if ($form_state['clicked_button']['#value'] === 'Import Remote Data') {
    $a = array();
    $b = array();
    aicapp_load_remote_form_submit($a, $b);
  }
}

/**
 * Admin api form callback.
 */
function aicapp_admin_api_validate($form, &$form_state) {
  $query = $form_state['values']['aicapp_artwork_query'];
  if (substr($query, 0, 7) !== "http://" && substr($query, 0, 8) !== "https://") {
    form_set_error('aicapp_artwork_query', t('You must enter a URL.'));
  }
  if (strpos($query, '{{term}}') === FALSE) {
    form_set_error('aicapp_artwork_query', t('You must include {{term}} somewhere in your URL.'));
  }
  $query = $form_state['values']['aicapp_gallery_query'];
  if (substr($query, 0, 7) !== "http://" && substr($query, 0, 8) !== "https://") {
    form_set_error('aicapp_gallery_query', t('You must enter a URL.'));
  }
  if (strpos($query, '{{term}}') === FALSE) {
    form_set_error('aicapp_gallery_query', t('You must include {{term}} somewhere in your URL.'));
  }
  // Check if Image Server value is a URL.
  if (!filter_var($form_state['values']['aicapp_image_server_url'], FILTER_VALIDATE_URL)) {
    form_set_error('aicapp_image_server_url', t('You must provide a valid URL for the Image Server.'));
  }
}

/**
 * Admin setting validation callback.
 */
function aicapp_admin_migrate_validate($form, &$form_state) {
  if ($form_state['clicked_button']['#value'] === 'Repeat Migration') {
    variable_set('aicapp_migrate_v1_v2', FALSE);
    return;
  }
  if ($form_state['clicked_button']['#value'] === 'Migrate Data') {
    global $language;
    $type = $form_state['values']['aicapp_data']['migration_type'];
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
         ->entityCondition('bundle', $type, '=');
    switch ($type) {
      default:
        drupal_set_message('There were no items to migrate');
      break;
      case AICAPP_TYPE_AUDIO:
        $query->propertyCondition('language', LANGUAGE_NONE, '=');
        $result = $query->execute();
        if (!empty($result['node'])) {
          $count = count($result['node']);
          if ($count < 1) {
            drupal_set_message('There were no audio items to migrate');
            break;
          }
          foreach ($result['node'] as $nid => $obj) {
            $node = node_load($nid);
            $node->language = $language->language;
            node_save($node);
          }
          drupal_set_message('All audio nodes have been set to English if no language had been set.');

        }
        break;

      case AICAPP_TYPE_OBJECT:
        // Load all objects that have no audio commentary items.
        $query->addTag('no_commentary_items');
        $result = $query->execute();
        if (!empty($result['node'])) {
          $i = 0;
          foreach ($result['node'] as $nid => $obj) {
            $changed = FALSE;
            $to_add = array();
            $node = node_load($nid);
            $found = array(
              'field_object_selector_numbers' => NULL,
              'field_object_selector_number' => NULL,
              'field_object_audio' => NULL,
            );
            // Check for field_object_selector_numbers, field_object_selector_number, and field_object_audio
            if (!empty($node->field_object_selector_numbers[LANGUAGE_NONE])) {
              $found['field_object_selector_numbers'] = $node->field_object_selector_numbers[LANGUAGE_NONE];
            }
            if (!empty($node->field_object_selector_number[LANGUAGE_NONE])) {
              $found['field_object_selector_number'] = $node->field_object_selector_number[LANGUAGE_NONE];
            }
            if (!empty($node->field_object_audio[LANGUAGE_NONE])) {
              // Uses 'nid' as value key instead of 'value'
              $found['field_object_audio'] = $node->field_object_audio[LANGUAGE_NONE];
            }
            // In terms of selector numbers, there are 3 cases.
            $count_multi = count($found['field_object_selector_numbers']);
            $count_audio = count($found['field_object_audio']);
            if ($count_multi === 1) {
              // One of each, make sure they are the same.
              if ($found['field_object_selector_number'] &&
                $found['field_object_selector_number'][0]['value'] === $found['field_object_selector_numbers'][0]['value']) {


                // Numbers match, there is 1 matching selector being used.
                $to_add[$i]['selector'][] = $found['field_object_selector_number'][0]['value'];
                if ($count_audio) {
                  foreach ($found['field_object_audio'] as $k => $v) {
                    $to_add[$i]['audio'][] = $found['field_object_audio'][$k]['nid'];
                  }
                }
                else {
                  // No audio found unset this selector to add.
                  unset($to_add[$i]['selector']);
                }
              }
              else {
                // Number will take precendence.
                $to_add[$i]['selector'][] = $found['field_object_selector_number'][0]['value'];
                $to_add[$i]['selector'][] = $found['field_object_selector_numbers'][LANGUAGE_NONE][0]['value'];
                // Multi and single do not match. Check audio count.
                if ($count_audio > 1) {
                  foreach ($found['field_object_audio'] as $k => $v) {
                    $to_add[$i]['audio'][] = $found['field_object_audio'][$k]['nid'];
                  }

                }
                elseif ($count_audio === 1) {
                  $to_add[$i]['audio'][] = $found['field_object_audio'][0]['nid'];
                  unset($to_add[$i]['selector'][1]);
                }
                else {
                  // No audio found unset this selector to add.
                  unset($to_add[$i]['selector']);
                }
              }
            }
            elseif ($count_multi > 1) {
              foreach ($found['field_object_selector_numbers'][LANGUAGE_NONE] as $k => $v) {
                $to_add[$i]['selector'][] = $found['field_object_selector_numbers'][$k]['value'];
              }
              if ($count_audio) {
                foreach ($found['field_object_audio'][LANGUAGE_NONE] as $k => $v) {
                  $to_add[$i]['audio'][] = $found['field_object_audio'][$k]['nid'];
                }
              }
              else {
                unset($to_add[$i]['selector']);
              }
            }
            elseif ($found['field_object_selector_number']) {
              // No multiple count but found one selector
              $to_add[$i]['selector'][] = $found['field_object_selector_number'][0]['value'];
              if ($count_audio) {
                foreach ($found['field_object_audio'] as $k => $v) {
                  $to_add[$i]['audio'][] = $found['field_object_audio'][$k]['nid'];
                }
              }
            }
            else {
              $i++;
              continue;
            }
            // Check if there are currently audio commentary items.
            if (!empty($node->field_audio_commentary[LANGUAGE_NONE])) {
              foreach ($node->field_audio_commentary[LANGUAGE_NONE] as $key => $value) {
                $field_collection_item_values[] = $value['value'];
              }
            }
            // Add the new audio commentary
            if (!empty($to_add[$i]) && !empty($to_add[$i]['audio']) && empty($field_collection_item_values)) {
              foreach ($to_add[$i]['audio'] as $k => $audio_id) {
                // Setup the values in the structure expected by the field_collection entity.
                $item_values = array(
                  'field_name' => 'field_audio_commentary',
                  'field_audio_commentary_audio' => array(
                    LANGUAGE_NONE => array(array(
                      'target_id' => $audio_id
                    )),
                  ),
                );
                if (isset($to_add[$i]['selector'][$k])) {
                  $item_values['field_object_selector_number'] = array(
                    LANGUAGE_NONE => array(array(
                      'value' => $to_add[$i]['selector'][$k]
                    ))
                  );
                }
                $fc_item = entity_create('field_collection_item', $item_values);
                $fc_item->setHostEntity('node', $node);
                $fc_item->save();
              }
              $changed = TRUE;
            }
            if ($changed === TRUE) {
              node_save($node);
            }
            $i++;
          }
          drupal_set_message('All object nodes have been set to English if no language had been set.');

        }
        break;

      case AICAPP_TYPE_TOUR:
        // Load all tous.
        $query->addTag('no_tour_stop');
        // Five at a time.
        $query->range(0, 5);
        $result = $query->execute();
        if (!empty($result['node'])) {
          $i = 0;
          foreach ($result['node'] as $nid => $obj) {
            $changed = FALSE;
            $to_add = array();
            $node = node_load($nid);
            $found = array(
              'field_t_object' => NULL,
              'field_t_audio' => NULL,
              'field_t_audio_bumper' => NULL,
            );

            // Check for field_object_selector_numbers, field_object_selector_number, and field_object_audio
            if (!empty($node->field_stops2[LANGUAGE_NONE])) {
              $ids = array();
              foreach ($node->field_stops2[LANGUAGE_NONE] as $key => $value) {
                $ids[] = $value['value'];
              }
              $stops = field_collection_item_load_multiple($ids);
              $stops = current($stops);
              foreach ($found as $field => $n) {
                if (!isset($stops->{$field}[LANGUAGE_NONE])) {
                  continue;
                }
                foreach ($stops->{$field}[LANGUAGE_NONE] as $id => $stop) {
                  $found[$field][] = $stop['nid'];
                }
              }
            }
            // In terms of selector numbers, there are 3 cases.
            $count_object = count($found['field_t_object']);
            $count_audio = count($found['field_t_audio']);
            $count_bumper = count($found['field_t_audio_bumper']);
            if ($count_object > $count_audio) {

            }
            elseif ($count_object < $count_audio) {

            }
            // Check if there are currently audio commentary items.
            if (!empty($node->field_tour_stops[LANGUAGE_NONE])) {
              foreach ($node->field_tour_stops[LANGUAGE_NONE] as $key => $value) {
                $field_collection_item_values[] = $value['value'];
              }
            }
            // Add the new audio commentary
            if (!empty($found['field_t_audio']) && empty($field_collection_item_values)) {
              foreach ($found['field_t_audio'] as $k => $audio_id) {
                // Setup the values in the structure expected by the field_collection entity.
                $item_values = array(
                  'field_name' => 'field_tour_stops',
                  'field_t_object' => array(
                    LANGUAGE_NONE => array(array(
                      'target_id' => $audio_id,
                    ))
                  )
                );
                if (isset($found['field_t_audio'][$k])) {
                  $item_values['field_t_audio'] = array(
                    LANGUAGE_NONE => array(array(
                      'target_id' => $found['field_t_audio'][$k],
                    ))
                  );
                }
                if (isset($found['field_t_audio_bumper'][$k])) {
                  $item_values['field_t_audio_bumper'] = array(
                    LANGUAGE_NONE => array(array(
                      'target_id' => $found['field_t_audio_bumper'][$k],
                    ))
                  );
                }
                $fc_item = entity_create('field_collection_item', $item_values);
                $fc_item->setHostEntity('node', $node);
                $fc_item->save();
              }
              $changed = TRUE;
            }

            // Set language to en.
            if ($node->language === LANGUAGE_NONE) {
              $node->language = $language->language;
              $changed = TRUE;
            }
            if ($changed === TRUE) {
              node_save($node);
            }
            $i++;
          }
          drupal_set_message(t('All tour nodes have been set to English if no language had been set, and tour stops have been udpated.'));

        }
        break;
    }
  }
}
