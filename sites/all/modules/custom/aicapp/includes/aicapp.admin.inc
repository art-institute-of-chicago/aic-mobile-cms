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
