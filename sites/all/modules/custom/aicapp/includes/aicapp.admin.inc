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
  $form['aicapp_tour_vocabular_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Tour category vocabulary name'),
    '#default_value' => variable_get('aicapp_tour_vocabular_name', 'categories'),
    '#size' => 200,
    '#maxlength' => 256,
    '#description' => t('The machine name of the vocabulary used to stored tour category terms.'),
  );
  $form['aicapp_env_fqdm'] = array(
    '#type' => 'textfield',
    '#title' => t('Domain name for the current environment.'),
    '#default_value' => variable_get('aicapp_env_fqdm', 'http://aicweb10.artic.edu'),
    '#size' => 200,
    '#maxlength' => 256,
    '#description' => t('Enter a URL for the current server environment. This will preceed any local URLs. Some examples are http://aicweb10.artic.edu, http://aicweb14.artic.edu, http://de-mobile-dev.artic.edu, etc.'),
  );

  $form['aicapp_ops'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
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
  $form['aicapp_ops']['aicapp_data_aggregator']['import_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import Events & Exhitions'),
    '#prefix' => '<div>',
    '#suffix' => t('Click this button to load events and exhitions from the data hub.') . '</div>',
    '#submit' => array('aicapp_load_remote_form_submit'),
  );
  $form['aicapp_images'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Image Cropping'),
    '#tree' => FALSE,
  );
  $form['aicapp_images']['aicapp_image_object'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#title' => t('Object Node Cropping Defaults'),
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
function aicapp_admin_submit($form, &$form_state) {
  if ($form_state['clicked_button']['#value'] === 'Import Remote Data') {
    aicapp_load_remote_form_submit();
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
function aicapp_load_remote_form_submit($form, &$form_state, $start = 0, $limit_count = 50) {
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
      'endpoint' => AICAPP_DATA_API_HOST . AICAPP_DATA_API_ENDPOINT_EXHIBITION,
      'remote' => array(),
    ),
  );
  // Fetch all recent remote objects.
  foreach ($defaults as $type => &$remote) {
    // An object ID was passed and is not empty, query based on object ID.
    $options = array(
      'size' => $limit_count,
      'from' => $start,
    );
    $page_start = 2;
    $url = $remote['endpoint'];
    if ($type === AICAPP_TYPE_EVENT) {
      $options['http'] = 'POST';
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
    // Query the data aggregrator.
    $results = _aicapp_fetch_data($url, $options, TRUE);
    if (empty($results['data'])) {
      continue;
    }

    foreach ($results['data'] as $remote_record) {
      if ($type === AICAPP_TYPE_EXHIBITION) {
        // Skip any that are closed?
        // if ($remote_record['status'] === 'Closed') {
        //   continue;
        // }
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
                // Skip any that are closed?
                // if ($remote_record['status'] === 'Closed') {
                //   continue;
                // }
              }
              else if ($type === AICAPP_TYPE_EVENT) {
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
    if (!empty($remote['to_import'])) {
      $query->fieldCondition('field_imported_object_id', 'value', $remote['to_import'], 'IN');
    }
    $result = $query->execute();
    if (isset($result['node'])) {
      $nids = array_keys($result['node']);
      $remote['local'] = entity_load('node', $nids);
    }
    // Loop through cached objects and save updated values from API.
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
      // Title
      $title = htmlspecialchars_decode(
        html_entity_decode($to_import['title'], ENT_QUOTES, 'UTF-8'),
        ENT_QUOTES
      );
      $ewrapper->title->set($title);
      // Object ID
      $ewrapper->field_imported_object_id->set($to_import['id']);
      // Object JSON
      $ewrapper->field_imported_object->set(json_encode($to_import));
      // Exhibition only fields.
      if ($type === AICAPP_TYPE_EXHIBITION) {
        // Exhibition dates.
        $start = new DateTime($to_import['aic_start_at']);
        $end = new DateTime($to_import['aic_end_at']);
        $ewrapper->field_exhibition_dates->set(array(
          'value' => $start->format('Y-m-d H:i:s'),
          'value2' => $end->format('Y-m-d H:i:s'),
        ));
      }
      // Event only fields.
      elseif ($type === AICAPP_TYPE_EVENT) {
        $start = new DateTime($to_import['start_at']);
        $end = new DateTime($to_import['end_at']);
        // Event start and end dates
        $ewrapper->field_event_dates->set(array(
          'value' => $start->format('Y-m-d H:i:s'),
          'value2' => $end->format('Y-m-d H:i:s'),
        ));
        if (strtotime($to_import['end_at']) < strtotime('now')) {
          // Unpubish and demote this node.
          $ewrapper->status->set(0);
          $ewrapper->promote->set(0);
        }
      }
      // Now just save the wrapper and the entity
      $ewrapper->save();
      // Get the key value for this remote id.
      $remote_key = array_search($remote_id, $remote['to_import']);
      // Unset this item from the import stack.
      if (!empty($remote['to_import'][$remote_key])) {
        unset($remote['to_import'][$remote_key]);
      }
    }
    // Add any new remote objects that do not exist locally.
    foreach ($remote['to_import'] as $remote_id) {
      if (empty($remote['remote'][$remote_id])) {
        continue;
      }
      // Update node fields with values from remote enities, but only if
      // remote updated date is more recent than local date.
      $to_import = $remote['remote'][$remote_id];
      // Creating a new object $node and setting its 'type' and uid property.
      $values = array(
        'type' => $type,
        'uid' => 1,
        'status' => 1,
        'promote' => 0,
      );
      $entity = entity_create('node', $values);

      // The entity is now created, but we have not yet simplified use of it.
      // Now create an entity_metadata_wrapper around the new node entity
      // to make getting and setting values easier.
      $ewrapper = entity_metadata_wrapper('node', $entity);
      // Title
      $title = htmlspecialchars_decode(
        html_entity_decode($to_import['title'], ENT_QUOTES, 'UTF-8'),
        ENT_QUOTES
      );
      $ewrapper->title->set($title);
      // Object ID.
      $ewrapper->field_imported_object_id->set($to_import['id']);
      // Object JSON.
      $ewrapper->field_imported_object->set(json_encode($to_import));

      if ($type === AICAPP_TYPE_EXHIBITION) {
        // Exhibition dates..
        $start = new DateTime($to_import['start_at']);
        $end = new DateTime($to_import['end_at']);
        $ewrapper->field_exhibition_dates->set(array(
          'value' => $start->format('Y-m-d H:i:s'),
          'value2' => $end->format('Y-m-d H:i:s'),
        ));
      }
      elseif ($type === AICAPP_TYPE_EVENT) {
        $start = new DateTime($to_import['start_at']);
        $end = new DateTime($to_import['end_at']);
        // Event start and end dates.
        $ewrapper->field_event_dates->set(array(
          'value' => $start->format('Y-m-d H:i:s'),
          'value2' => $end->format('Y-m-d H:i:s'),
        ));
        // Description.
        $ewrapper->field_event_description->set($to_import['description']);
        // Short Description.
        $ewrapper->field_event_short_description->set($to_import['short_description']);
      }
      try {
        // Now just save the wrapper and the entity.
        $ewrapper->save();
      }
      catch (Exception $e) {
        drupal_set_message(t("An error occurred when importing events: @e", array('@e' => $e)), 'error');
      }
    }
  }
}
