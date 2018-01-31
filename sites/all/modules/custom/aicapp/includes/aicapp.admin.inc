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
  $vocabularies = array();
  foreach (taxonomy_get_vocabularies() as $item) {
    $key = $item->machine_name;
    $value = $item->name;
    $vocabularies[$key] = $value;
  }
  $form['aicapp_tour_vocabular_name'] = array(
    '#type' => 'select',
    '#options' => $vocabularies,
    '#title' => t('Tour category vocabulary name'),
    '#default_value' => variable_get('aicapp_tour_vocabular_name', array('categories')),
    '#description' => t('The machine name of the vocabulary used to stored tour category terms.'),
  );
  $form['aicapp_env_fqdm'] = array(
    '#type' => 'textfield',
    '#title' => t('Domain name for the current environment.'),
    '#default_value' => variable_get('aicapp_env_fqdm', 'http://aic-mobile-tours.artic.edu'),
    '#size' => 200,
    '#maxlength' => 256,
    '#description' => t('Enter a URL for the current server environment. This will preceed any local URLs. Some examples are http://aic-mobile-tours.artic.edu, http://aicweb14.artic.edu, http://de-mobile-dev.artic.edu, etc.'),
  );
  $form['aicapp_ops'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('Operations'),
    '#tree' => FALSE,
  );
  $form['aicapp_ops']['ops_galleries_sync'] = array(
    '#type' => 'submit',
    '#value' => t('Refresh Gallery List'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to sync the list of galleries.') . '</div>',
    '#submit' => array('aicapp_loadgalleries_form_submit'),
  );
  $form['aicapp_ops']['ops_json_generate'] = array(
    '#type' => 'submit',
    '#value' => t('Generate JSON'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to generate and create the JSON file.') . '</div>',
    '#submit' => array('aicapp_gendata_form_submit'),
  );
  $form['aicapp_ops']['ops_publish_json'] = array(
    '#type' => 'submit',
    '#value' => t('Publish JSON'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to create a text file that triggers a cron job to sync JSON files with the front-end server.') . '</div>',
    '#submit' => array('aicapp_file_sync_form_submit'),
  );
  $form['aicapp_ops']['ops_object_gallery_status'] = array(
    '#type' => 'submit',
    '#value' => t('Send Gallery Status Emails'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to send email notifications about objects that are no longer on display or have moved locations.') . '</div>',
    '#submit' => array('aicapp_objectGalleryStatus_submit'),
  );
  $form['aicapp_ops']['aicapp_data_aggregator']['import_events_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import Events'),
    '#prefix' => '<h5>' . t('Events & Exhibitions') . '</h5><div>',
    '#suffix' => t('Click this button to load or update events from the data hub.') . '</div>',
    '#submit' => array('aicapp_load_remote_events_form_submit'),
  );
  $form['aicapp_ops']['aicapp_data_aggregator']['import_exhibitions_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import Exhibitions'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to load or update exhibitions from the data hub.') . '</div>',
    '#submit' => array('aicapp_load_remote_exhibitions_form_submit'),
  );
  $suffix_message = t('Click this button to update all Object image URLs from the data hub.');
  if (module_exists('aic_update_object')) {
    $object_nids = variable_get('aic_update_object_nids', array());
    if (empty($object_nids) || count($object_nids) < 100) {
      // Load all objects, including unpublished, because we compare if objects
      // previously not in a gallery (and unpublished) are now displayed.
      $artwork_objects = node_load_multiple(array(), array('type' => AICAPP_TYPE_OBJECT));
      $object_nids += array_keys($artwork_objects);
      variable_set('aic_update_object_nids', $object_nids);
    }
    else {
      $suffix_message .= ' ' . format_plural(count($object_nids),
        '1 Object needs to be updated.',
        '@count Objects remaining.'
      );
    }
    $form['aicapp_ops']['aicapp_data_aggregator']['update_object_images'] = array(
      '#type' => 'submit',
      '#value' => t('Update Object Images'),
      '#prefix' => '<h5>' . t('Artwork Objects') . '</h5><div>',
      '#suffix' => $suffix_message . '</div>',
      '#submit' => array('aicapp_update_object_images'),
    );
  }
  $form['aicapp_ops']['version_compare'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('JSON Version Compare'),
  );
  $compare_options = array(
    'prod__v1' => 'Production v1',
    'prod__v2' => 'Production v2',
    'stage__v1' => 'Staging v1',
    'stage__v2' => 'Staging v2',
    'test__v1' => 'Testing v1',
    'test__v2' => 'Testing v2',
    'local__v1' => 'Local v1',
    'local__v2' => 'Local v2',
  );
  $json_nodes = drupal_map_assoc(array(
    'dashboard',
    'general_info',
    'galleries',
    'objects',
    'audio_files',
    'tours',
    'annontations',
    'map_floors',
    'tour_categories',
  ));
  $form['aicapp_ops']['version_compare']['aicapp_json_compare_base'] = array(
    '#type' => 'select',
    '#title' => t('Base JSON'),
    '#default_value' => variable_get('aicapp_json_compare_base', array('prod__v1')),
    '#options' => $compare_options,
    '#description' => t('Select a JSON file to compare another to.'),
  );
  $form['aicapp_ops']['version_compare']['aicapp_json_compare_head'] = array(
    '#type' => 'select',
    '#title' => t('Comparison JSON'),
    '#default_value' => variable_get('aicapp_json_compare_head', array('test__v1')),
    '#options' => $compare_options,
    '#description' => t('Select a JSON file.'),
  );
  $form['aicapp_ops']['version_compare']['aicapp_json_compare_node'] = array(
    '#type' => 'select',
    '#title' => t('JSON node/element to compare'),
    '#default_value' => variable_get('aicapp_json_compare_node', array('tours')),
    '#options' => $json_nodes,
    '#description' => t('Select a JSON node to compare.'),
  );
  $form['aicapp_ops']['version_compare']['aicapp_json_compare_keys_only'] = array(
    '#type' => 'checkbox',
    '#title' => t('Only compare key values'),
    '#default_value' => variable_get('aicapp_json_compare_keys_only', 1),
    '#description' => t('If checked, only keys/properties (not values) will be compared. If unchecked, then the values will also be compared to determine difference.'),
  );
  $form['aicapp_ops']['version_compare']['aicapp_json_compare_key_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Compare only this key/node.'),
    '#default_value' => variable_get('aicapp_json_compare_key_name', ''),
    '#size' => 64,
    '#maxlength' => 64,
    '#description' => t('Enter a key, node/property name to compare. All keys will be compared if this is empty.'),
  );
  $form['aicapp_ops']['version_compare']['aicapp_json_compare'] = array(
    '#type' => 'submit',
    '#value' => t('Compare JSON'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to compare 2 JSON files.') . '</div>',
    '#submit' => array('aicapp_json_compare_form_submit'),
  );
  $form['aicapp_images'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Images'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_object'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Object Node Cropping Defaults'),
    '#prefix' => '<h4>' . t('Cropping Defaults') . '</h4>',
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_crop'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large image crop dimensions'),
    '#default_value' => variable_get('aicapp_image_large_crop', '!800,800'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
    '#prefix' => '<h4>' . t('Large Images') . '</h4>',
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_crop_aspect'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large image crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_large_crop_aspect', '1.82:1'),
    '#description' => t('Leave empty to allow for no fixed aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_crop_lock'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow large image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_large_crop_lock', 0)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_min_width'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large Image Minimum Width'),
    '#default_value' => variable_get('aicapp_image_large_min_width', '364'),
    '#description' => t('The minimum width in pixels for the artwork object image.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_large_min_height'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large Image Minimum Height'),
    '#default_value' => variable_get('aicapp_image_large_min_height', '200'),
    '#description' => t('The minimum height in pixels for the artwork object image.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_image_thumb_crop'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop dimensions'),
    '#default_value' => variable_get('aicapp_image_thumb_crop', ' !112,112'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
    '#prefix' => '<h4>' . t('Thumbnails') . '</h4>',
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
    '#title' => t('Allow thumbnail image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_thumb_crop_lock', 1)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_object_image_min_width'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail Minimum Width'),
    '#default_value' => variable_get('aicapp_object_image_min_width', '-1'),
    '#description' => t('The minimum width in pixels for the artwork object image.  A value of -1 will set the minimum to the size of the preview thumbnail image.'),
  );
  $form['aicapp_images']['aicapp_image_object']['aicapp_object_image_min_height'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail Minimum Height'),
    '#default_value' => variable_get('aicapp_object_image_min_height', '-1'),
    '#description' => t('The minimum height in pixels for the artwork object image. A value of -1 will set the minimum to the size of the preview thumbnail image.'),
  );
  $form['aicapp_images']['aicapp_image_tour'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Tour Node Cropping Defaults'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_large_crop_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large image crop dimensions'),
    '#default_value' => variable_get('aicapp_image_large_crop_tour', '!800,800'),
    '#description' => t('Dimensions of the image to use for previewing a crop.'),
    '#prefix' => '<h4>' . t('Large Images') . '</h4>',
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_large_crop_aspect_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large image crop aspect ratio'),
    '#default_value' => variable_get('aicapp_image_large_crop_aspect_tour', '1.82:1'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_large_crop_lock_tour'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow large image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_large_crop_lock_tour', 0)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_tour_image_large_min_width'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large Image Minimum Width'),
    '#default_value' => variable_get('aicapp_tour_image_large_min_width', '364'),
    '#description' => t('The minimum width in pixels for the tour image.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_tour_image_large_min_height'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Large Image Minimum Height'),
    '#default_value' => variable_get('aicapp_tour_image_large_min_height', '200'),
    '#description' => t('The minimum height in pixels for the tour image.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_image_thumb_crop_tour'] = array(
    '#type' => 'textfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail crop dimensions'),
    '#default_value' => variable_get('aicapp_image_thumb_crop_tour', '!112,112'),
    '#prefix' => '<h4>' . t('Thumbnails') . '</h4>',
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
    '#title' => t('Allow thumbnail image aspect ratio to be resized'),
    '#default_value' => (variable_get('aicapp_image_thumb_crop_lock_tour', 1)) ? 1 : 0,
    '#description' => t('Whether or not to lock the crop dimensions to this aspect ratio.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_tour_image_min_width'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail Minimum Width'),
    '#default_value' => variable_get('aicapp_tour_image_min_width', '-1'),
    '#description' => t('The minimum width in pixels for the tour image.  A value of -1 will set the minimum to the size of the preview thumbnail image.'),
  );
  $form['aicapp_images']['aicapp_image_tour']['aicapp_tour_image_min_height'] = array(
    '#type' => 'numberfield',
    '#size' => 25,
    '#maxlength' => 25,
    '#title' => t('Thumbnail Minimum Width'),
    '#default_value' => variable_get('aicapp_tour_image_min_height', '-1'),
    '#description' => t('The minimum height in pixels for the tour image.  A value of -1 will set the minimum to the size of the preview thumbnail image.'),
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
    '#description' => t(''),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_events'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Events endpoint'),
    '#default_value' => variable_get('aicapp_data_endpoint_events', '/api/v1/events'),
    '#description' => t('Query for an event via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_exhibitions'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Exhibitions endpoint'),
    '#default_value' => variable_get('aicapp_data_endpoint_exhibitions', '/api/v1/exhibitions'),
    '#description' => t('Query for an exhibition via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_artworks'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Artworks endpoint'),
    '#default_value' => variable_get('aicapp_data_endpoint_artworks', '/api/v1/artworks'),
    '#description' => t('Query for an artwork via this endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_galleries'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Places endpoint'),
    '#default_value' => variable_get('aicapp_data_endpoint_galleries', '/api/v1/galleries'),
    '#description' => t('Query for a gallery via the galleries endpoint.'),
  );
  $form['aicapp_data_aggregator']['aicapp_data_endpoint_images'] = array(
    '#type' => 'textfield',
    '#size' => 200,
    '#maxlength' => 256,
    '#title' => t('Images endpoint'),
    '#default_value' => variable_get('aicapp_data_endpoint_images', '/api/v2/images'),
    '#description' => t('Query for a image via this endpoint.'),
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
 * Admin setting validation callback.
 */
function aicapp_admin_validate($form, &$form_state) {
  if (strpos($form_state['values']['aicapp_image_large_crop_aspect'], ':') === FALSE && !empty($form_state['values']['aicapp_image_large_crop_aspect'])) {
    $form_state['values']['aicapp_image_large_crop_aspect'] = $form_state['values']['aicapp_image_large_crop_aspect'] . ':1';
  }
  if (strpos($form_state['values']['aicapp_image_thumb_crop_aspect'], ':') === FALSE && !empty($form_state['values']['aicapp_image_thumb_crop_aspect'])) {
    $form_state['values']['aicapp_image_thumb_crop_aspect'] = $form_state['values']['aicapp_image_thumb_crop_aspect'] . ':1';
  }
  $form_state['values']['aicapp_image_large_crop'] = trim($form_state['values']['aicapp_image_large_crop']);
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
}

/**
 * Admin setting validation callback.
 */
function aicapp_json_compare_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  // parse our the file details.
  $types = array(
    'base' => explode('__', $values['aicapp_json_compare_base']),
    'head' => explode('__', $values['aicapp_json_compare_head']),
  );
  // JSON node to compare;
  $json_node = $values['aicapp_json_compare_node'];
  $arrays = array();
  foreach ($types as $type => $options) {
    if (empty($options[0]) || empty($options[1])) {
      return;
    }
    $filename = 'http://aic-mobile-tours.artic.edu';
    switch ($options[0]) {
      default:
        break;
      case 'prod':
        $filename .= '/sites/default/files';
        break;

      case 'stage':
        $filename .= '/sites/default_' . $options[0] . '/files';
        break;

      case 'test':
        $filename .= '/sites/default_' . $options[0] . '/files';
        break;

      case 'local':
        $filename = variable_get('file_public_path', conf_path());
        break;
    }
    switch ($options[1]) {
      default:
        break;
      case 'v1':
        $filename .= '/' . AICAPP_JSON_FILENAME . '.json';
        break;

      case 'v2':
        $filename .= '/' . AICAPP_JSON_FILENAME . '-' . AICAPP_JSON_V2 . '.json';
        break;
    }
    $json = file_get_contents($filename);
    if (empty($json)) {
      return;
    }
    $arrays[$type] = json_decode($json, TRUE);
    if (!isset($arrays[$type][$json_node])) {
      return;
    }
    $arrays[$type . '_info'] = array(
      'url' => $filename,
    );
    $arrays[$type] = $arrays[$type][$json_node];
  }
  $keys_only = !empty($values['aicapp_json_compare_keys_only']) ?:FALSE;
  $compare_key = !empty($values['aicapp_json_compare_key_name']) ? $values['aicapp_json_compare_key_name'] : NULL;
  // Compare to JSON files.
  $diff['Missing in base version'] = aicapp_array_diff_assoc_recursive($arrays['head'], $arrays['base'], $keys_only, $compare_key);
  // From the base first.
  $diff['Missing in comparison version'] = aicapp_array_diff_assoc_recursive($arrays['base'], $arrays['head'], $keys_only, $compare_key);
  if (function_exists('dpm')) {
    drupal_set_message(t('Comparing the JSON from: %base to %head', array(
      '%base' => $arrays['base_info']['url'],
      '%head' => $arrays['head_info']['url'],
    )));
    dpm($diff);
  }
  else {
    drupal_set_message(t('Comparing the JSON from: %base to %head', array(
      '%base' => $arrays['head_info']['url'],
      '%head' => $arrays['base_info']['url'],
    )));
    drupal_set_message('The Devel module must be enabled to see comparison results.');
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
 * Save remote objects to nodes.
 */
function aicapp_update_object_images($form, &$form_state) {
  aicapp_update_object_image_data();
}

/**
 * Save remote objects to nodes.
 */
function aicapp_load_remote_events_form_submit($form, &$form_state, $start = 0, $limit_count = 50) {
  aicapp_load_remote_form_submit($form, $form_state, AICAPP_TYPE_EVENT, $start, $limit_count);
}

/**
 * Save remote objects to nodes.
 */
function aicapp_load_remote_exhibitions_form_submit($form, &$form_state, $start = 0, $limit_count = 50) {
  aicapp_load_remote_form_submit($form, $form_state, AICAPP_TYPE_EXHIBITION, $start, $limit_count);
}

/**
 * Save remote objects to nodes.
 */
function aicapp_load_remote_form_submit($form, &$form_state, $remote_type, $start = 0, $limit_count = 50) {
  $now = new DateTime();
  $remote_objects = array();
  $max_page = 10;
  $page = 'page=';
  // Define what we're going to import.
  $defaults = array(
    AICAPP_TYPE_EVENT => array(
      'endpoint' => AICAPP_DATA_API_HOST . AICAPP_DATA_API_ENDPOINT_EVENT . '/search',
      'remote' => array(),
    ),
    AICAPP_TYPE_EXHIBITION => array(
      'endpoint' => AICAPP_DATA_API_HOST . AICAPP_DATA_API_ENDPOINT_EXHIBITION . '/search',
      'remote' => array(),
    ),
  );
  // Fetch all recent remote objects.
  foreach ($defaults as $type => &$remote) {
    if ($type !== $remote_type) {
      continue;
    }
    // An object ID was passed and is not empty, query based on object ID.
    $options = array(
      'size' => $limit_count,
      'from' => $start,
      'http' => 'POST',
    );
    $page_start = 2;
    $url = $remote['endpoint'];
    if ($type === AICAPP_TYPE_EVENT) {
      $options['query'] = json_encode(array(
        '_source' => true,
        'sort' => array(
          'start_at',
          'end_at'
        ),
        'query' => array(
          'bool' => array(
            'must' => array(
              array(
                'range' => array(
                  'start_at' => array(
                    'lte' => 'now+2w',
                  )
                ),
              ),
              array(
                'range' => array(
                  'end_at' => array(
                    'gte' => 'now',
                  )
                ),
              ),
            ),
          )
        )
      ));
    }
    elseif ($type ===  AICAPP_TYPE_EXHIBITION) {
      $options['query'] = json_encode(array(
        '_source' => true,
        'sort' => array(
          'aic_start_at',
          'aic_end_at'
        ),
        'query' => array(
          'bool' => array(
            'must' => array(
              array(
                'range' => array(
                  'aic_start_at' => array(
                    'lte' => 'now+2w',
                  )
                ),
              ),
              array(
                'range' => array(
                  'aic_end_at' => array(
                    'gte' => 'now',
                  )
                ),
              ),
            ),
          )
        )
      ));
    }
    // Query the data aggregrator.
    $results = _aicapp_fetch_data($url, $options, TRUE);
    if (empty($results['data'])) {
      continue;
    }

    foreach ($results['data'] as $remote_record) {
      if ($type === AICAPP_TYPE_EXHIBITION) {
        // Skip any that are closed?
        if ($now > new DateTime($remote_record['aic_end_at'])) {
          # Now is greater than Event end time, i.e. in the past.
          continue;
        }
      }
      elseif ($type === AICAPP_TYPE_EVENT) {
        // Skip any that have end dates in the past.
        if ($now > new DateTime($remote_record['end_at'])) {
          # Now is greater than Event end time, i.e. in the past.
          continue;
        }
      }
      $remote['remote'][$remote_record['id']] = $remote_record;
    }

    // Now loop though up to $max_page adding records that are active and
    // having an end date in the past.
    if (isset($results['pagination']) && isset($results['pagination']['next_url'])) {
      $loop_url = $results['pagination']['next_url'];
      while ($page_start <= $max_page) {
        $loop_json = file_get_contents($loop_url);
        if ($loop_json) {
          $decoded_loop = json_decode($loop_json, TRUE);
          if (isset($decoded_loop['data'])) {
            foreach ($decoded_loop['data'] as $remote_record) {
              if ($type === AICAPP_TYPE_EXHIBITION) {
                if ($now > new DateTime($remote_record['aic_end_at'])) {
                  # Now is greater than Event end time, i.e. in the past.
                  continue;
                }
              }
              elseif ($type === AICAPP_TYPE_EVENT) {
                // Skip any that have end dates in the past.
                if ($now > new DateTime($remote_record['end_at'])) {
                  # Now is greater than Event end time, i.e. in the past.
                  continue;
                }
              }
              $remote['remote'][$remote_record['id']] = $remote_record;
            }
          }
          $loop_url = isset($decoded_loop['pagination']) ? $decoded_loop['pagination']['next_url'] : NULL;
        }
        $page_start++;
      }
    }

    // Fetch all locally saved objects using the key of remote ids.
    $remote['local'] = array();
    $remote['to_import'] = array_keys($remote['remote']);
    // Find nodes that match the id of the remote object.
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', $type, '=');
    $result = $query->execute();
    if (isset($result['node'])) {
      $nids = array_keys($result['node']);
      $remote['local'] = entity_load('node', $nids);
    }
    $count = 0;
    // Loop through objects and save updated values from API.
    foreach ($remote['local'] as $local_node) {
      // Load an entity.
      $ewrapper = entity_metadata_wrapper('node', $local_node);
      $remote_id = $ewrapper->field_imported_object_id->value();
      if (empty($remote['remote'][$remote_id])) {
        continue;
      }
      // Update node fields with values from remote enities, but only if remote
      // updated date is more recent than local date.
      // Update all local objects that have local update date previous to last
      // updated remote object dates.
      $to_import = $remote['remote'][$remote_id];
      _aicapp_save_remote_event($to_import, $type, $ewrapper, $local_node);
      // Get the key value for this remote id.
      $remote_key = array_search($remote_id, $remote['to_import']);
      // Unset this item from the import stack.
      if (!empty($remote['to_import'][$remote_key])) {
        unset($remote['to_import'][$remote_key]);
      }
      $count++;
    }
    // Add any new remote objects that do not exist locally.
    foreach ($remote['to_import'] as $remote_id) {
      if (empty($remote['remote'][$remote_id])) {
        continue;
      }
      // Update node fields with values from remote enities, but only if
      // remote updated date is more recent than local date.
      _aicapp_save_remote_event($remote['remote'][$remote_id], $type);
      $count++;
    }
  }
  if ($count) {
    $message = format_plural($count,
      '1 record has been updated.',
      '@count records have been updated.'
    );
    watchdog('aic_import_remote', $message);
    drupal_set_message($message);
    // Clear drupal cache.
    drupal_flush_all_caches();
  }
}

/**
 * Helper function to update event or exhibiton nodes.
 */
function _aicapp_save_remote_event(&$to_import, $type, $entity_wrapper = NULL) {
  $timezone = variable_get('date_default_timezone', 'America/Chicago');
  $date_values = array(
    'value' => '',
    'value2' => '',
    'offset' => '',
    'offset2' => '',
    'timezone' => $timezone,
  );
  if (empty($entity_wrapper)) {
    // Creating a new object $node and setting its 'type' and uid property.
    $entity = entity_create('node', array(
      'type' => $type,
      'uid' => 1,
      'status' => 1,
      'promote' => 0,
    ));
    // The entity is now created, but we have not yet simplified use of it.
    // Now create an entity_metadata_wrapper around the new node entity
    // to make getting and setting values easier.
    $entity_wrapper = entity_metadata_wrapper('node', $entity);
  }
  // Title
  $title = html_entity_decode($to_import['title'], ENT_QUOTES, 'UTF-8');
  $entity_wrapper->title->set($title);
  // Object ID.
  $entity_wrapper->field_imported_object_id->set($to_import['id']);
  // Object JSON.
  $entity_wrapper->field_imported_object->set(json_encode($to_import));
  // Start and end dates.
  if ($type === AICAPP_TYPE_EVENT) {
    $start = new DateTime($to_import['start_at']);
    $end = new DateTime($to_import['end_at']);
  }
  elseif ($type === AICAPP_TYPE_EXHIBITION) {
    $start = new DateTime($to_import['aic_start_at']);
    $end = new DateTime($to_import['aic_end_at']);
  }
  $tz = $start->getTimezone();
  $date_values['value'] = $start->getTimestamp();
  $date_values['value2'] = $end->getTimestamp();
  $date_values['offset'] = $tz->getName();
  $date_values['offset2'] = $tz->getName();
  // Make sure all date value are not negative.
  if ($date_values['value'] <= 0 || $date_values['value2'] <= 0 || $date_values['value'] >= 2147483648 || $date_values['value2'] >= 2147483648) {
    return;
  }
  if ($type === AICAPP_TYPE_EXHIBITION) {
    // Set Exhibition dates..
    $entity_wrapper->field_exhibition_dates->set($date_values);
  }
  elseif ($type === AICAPP_TYPE_EVENT) {
    // Event start and end dates.
    $entity_wrapper->field_event_dates->set($date_values);
    // Description.
    $entity_wrapper->field_event_description->set($to_import['description']);
    // Short Description.
    $entity_wrapper->field_event_short_description->set($to_import['short_description']);
  }
  if ($end < new DateTime()) {
    // Unpubish and demote this node.
    $entity_wrapper->status->set(0);
    $entity_wrapper->promote->set(0);
  }
  try {
    // Now just save the wrapper and the entity.
    $entity_wrapper->save();
  }
  catch (Exception $e) {
    drupal_set_message(t("An error occurred when importing an #type: @e", array('#type' => $type, '@e' => $e)), 'error');
  }
}

/**
 * Helper function to update object image data.
 */
function aicapp_update_object_image_data($hard_limit = 100) {
  $by_object_id =  array();
  $node_ids = variable_get('aic_update_object_nids', array());

  $sliced_node_ids = array_slice($node_ids, 0, $hard_limit);
  if (empty($sliced_node_ids)) {
    // Load all objects, including unpublished, because we compare if objects
    // previously not in a gallery (and unpublished) are now displayed.
    $arwork_objects = node_load_multiple(array(), array('type' => AICAPP_TYPE_OBJECT));
  }
  else {
    $arwork_objects = node_load_multiple($sliced_node_ids, array('type' => AICAPP_TYPE_OBJECT));
  }
  $object_node_ids = array();
  foreach ($arwork_objects as $object) {
    if (!is_object($object)) {
      continue;
    }
    $object_entity = entity_metadata_wrapper('node', $object);
    $object_id = $object_entity->field_object_id->value();
    if (!empty($object_id)) {
      $by_object_id[$object_id] = array(
        '#node' => $object,
        '#entity' => $object_entity,
      );
    }
    elseif (($key = array_search($object->nid, $node_ids)) !== FALSE) {
      unset($node_ids[$key]);
    }
  }
  // Query the data aggregrator for recent data using the object ids.
  $options = array(
    'ids' => array_keys($by_object_id),
    'limit' => count($by_object_id),
    'fields' => 'id,preferred_image_iiif_url,preferred_image_id',
  );

  $url = AICAPP_DATA_API_HOST . AICAPP_DATA_API_ENDPOINT_ARTWORKS;
  $results = _aicapp_fetch_data($url, $options);
  if (empty($results)) {
    $message = 'No results were found when searching for artwork objects.';
    watchdog('aic_update_object', $message);
    // Set the remaining objects that need to be updated.
    variable_set('aic_update_object_nids', $node_ids);
    return;
  }
  $count = 0;
  foreach ($results as $item) {
    $id = isset($item['id']) ? $item['id'] : NULL;
    // If the results were missing an id or that node object was not found.
    if (empty($id) || !isset($by_object_id[$id]) || empty($item['preferred_image_id'])) {
      if (empty($item['preferred_image_id'])) {
        $message = 'Object record ' . $object->nid . ' does not have a preferred image id.';
        watchdog('aic_update_object', $message);
        drupal_set_message($message);
      }
      continue;
    }
    // Grab the node object, and update its fields.
    $object = $by_object_id[$id]['#node'];
    // Update image fields.
    _aicapp_update_image_fields($object, $item['preferred_image_id']);
    try {
      // Set a special property to skip presave hook.
      $object->skip_hooks_presave = TRUE;
      node_save($object);
      $count++;
      if (($key = array_search($object->nid, $node_ids)) !== FALSE) {
        unset($node_ids[$key]);
      }
    }
    catch (Exception $e) {
      $replacements = array(
        '%id' => $object->nid,
        '%e' => !empty($e->errorInfo[2]) ? $e->errorInfo[2] : '',
      );
      $message = t('There was a problem updating an object record: %id. %e', $replacements);
      watchdog('aic_update_object', $message, array(), WATCHDOG_ERROR);
      drupal_set_message($message);
      continue;
    }
  }
  if ($count) {
    // Set the remaining objects that need to be updated.
    variable_set('aic_update_object_nids', $node_ids);
    $message = format_plural($count,
      '1 Object record has been updated.',
      '@count Object records have been updated.'
    );
    watchdog('aic_update_object', $message);
    // Clear drupal cache.
    drupal_flush_all_caches();
    drupal_set_message($message);
  }
}

function aicapp_array_diff_assoc_recursive($array1, $array2, $keys_only = FALSE, $compare_key = NULL, $sort = 'ksort') {
  $difference = array();
  foreach ($array1 as $key => $value) {
    if ($keys_only && array_key_exists($key, $array2)) {
      // Do not count this as different.
      continue;
    }
    elseif (is_array($value) || is_object($value)) {
      if (!array_key_exists($key, $array2) || (!is_array($array2[$key] && !is_object($array2[$key])))) {
        $difference[$key] = $value;
        if ((is_array($value) || is_object($value)) && !empty($compare_key)) {
          $difference[$key] = array_intersect_key((array) $value, array($compare_key => TRUE));
        }
      }
      else {
        $new_diff = aicapp_array_diff_assoc_recursive((array) $value, (array) $array2[$key], $keys_only, $compare_key, $sort);
        if (!empty($new_diff)) {
          if (!empty($sort) && function_exists($sort)) {
            $sort($new_diff);
          }
          $difference[$key] = $new_diff;
          if ((is_array($new_diff) || is_object($new_diff)) && !empty($compare_key)) {
            $difference[$key] = array_intersect_key((array) $new_diff, array($compare_key => TRUE));
          }
        }
      }
    }
    elseif (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
      $difference[$key] = $value;
      if ((is_array($value) || is_object($value)) && !empty($compare_key)) {
        $difference[$key] = array_intersect_key((array) $value, array($compare_key => TRUE));
      }
    }
  }
  if (!empty($sort) && function_exists($sort)) {
    $sort($difference);
  }
  return $difference;
}
