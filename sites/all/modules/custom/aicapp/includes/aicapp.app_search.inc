<?php

/**
 * @file aicapp.app_search.inc.
 */

/**
 *
 */
function aicapp_get_search_page_objects() {
  $objects = array();
  $nids = variable_get('aicapp_search_objects', array());
  $nodes = node_load_multiple($nids);
  // Expand objects for the form.
  $i = -10;
  foreach ($nodes as $nid => $node) {
    $objects[$nid] = array(
      'object' => $node->title,
      'object_id' => !empty($node->field_object_id[LANGUAGE_NONE]) ? $node->field_object_id[LANGUAGE_NONE][0]['value'] : NULL,
      'weight' => $i,
    );
    $i = $i + 10;
  }
  return $objects;
}

/**
 *
 */
function theme_tabledrag_example_simple_form($variables) {

}

/**
 *
 */
function aicapp_app_search_admin($form, &$form_state) {
  $form['#tree'] = TRUE;
  $defaults = array('Essentials Tour', 'Impressionism', 'American Gothic');
  $search_strings = variable_get('aicapp_search_strings', $defaults);
  $form['aicapp_search_strings'] = array(
    '#title' => t('Suggested search strings'),
    '#type' => 'textarea',
    '#description' => t('Enter a list of search string suggestions. Separate strings by line or comma.'),
    '#default_value' => is_array($search_strings) ? implode("\n", $search_strings) : $defaults,
  );
  // Show a view of the currently selected objects.
  $autocomplete_path = 'entityreference/autocomplete/single/field_tour_stop_object/field_collection_item/field_tour_stops/NULL';
  $form['aicapp_app_search_objects_list'] = array(
    '#type' => 'fieldset',
    '#title' => t('"On the Map" Object Suggestions'),
    // Set up the wrapper so that AJAX will be able to replace the fieldset.
    '#prefix' => '<div id="objects-wrapper">',
    '#suffix' => '</div>',
  );
  if (empty($form_state['objects'])) {
    $form_state['objects'] = aicapp_get_search_page_objects();
  }
  $rows = array();
  $row_elements = array();
  $data = array();
  $i = 0;
  foreach ($form_state['objects'] as $nid => $item) {
    // Build the table rows.
    $rows[$i] = array(
      'data' => array(
        // Cell for the cross drag&drop element.
        array('class' => array('entry-cross')),
        // Weight item for the tabledrag.
        array(
          'data' => array(
            '#type' => 'weight',
            '#title' => t('Weight'),
            '#title_display' => 'invisible',
            '#default_value' => $item['weight'],
            '#attributes' => array(
              'class' => array('order-weight'),
            ),
          ),
        ),
        // Object Name textfield.
        array(
          'data' => array(
            '#type' => 'textfield',
            '#name' => 'object-' . $nid,
            '#size' => 50,
            '#disabled' => TRUE,
            '#title' => t('Artwork Object'),
            '#title_display' => 'invisible',
            '#default_value' => $item['object'] . ' (' . $nid . ')',
          ),
        ),
        // Node ID
        array(
          'data' => array(
            '#type' => 'hidden',
            '#title_display' => 'invisible',
            '#default_value' => $nid,
          ),
        ),
        // Remove Object button.
        array(
          'data' => array(
            '#type' => 'submit',
            '#value' => t('Remove this Object'),
            '#name' => 'remove-' . $nid,
            '#submit' => array(
              'aicapp_app_search_add_more_remove_one',
            ),
            '#validate' => array(
              'aicapp_app_search_add_more_remove_validate'
            ),
            '#ajax' => array(
              'callback' => 'aicapp_app_search_add_more_callback',
              'wrapper' => 'objects-wrapper',
            ),
          ),
        ),
      ),
      'class' => array('draggable'),
    );
    // Build rows of the form elements in the table.
    $row_elements[$i] = array(
      'weight' => &$rows[$i]['data'][1]['data'],
      'object' => &$rows[$i]['data'][2]['data'],
      'nid' => &$rows[$i]['data'][3]['data'],
      'operations' => &$rows[$i]['data'][4]['data'],
    );
    $i++;
  }
  // Add to form
  $form['aicapp_app_search_objects_list']['objects'] = array(
    '#prefix' => '<div class="object-table">',
    '#theme' => 'table',
    // The row form elements need to be processed and build,
    // therefore pass them as element children.
    'elements' => $row_elements,
    '#header' => array(
      // Two empty columns for the weigth field and the cross.
      array('data' => NULL, 'colspan' => 2),
      t('Artwork Object (node id)'),
      array('data' => NULL, 'colspan' => 2),
    ),
    '#rows' => $rows,
    '#empty' => t('No Objects have been selected.'),
    '#attributes' => array('id' => 'object-list'),
    '#sufix' => '</div>',
  );
  // Add the associated JS.
  drupal_add_tabledrag('object-list', 'order', 'sibling', 'order-weight');
  $form['#attached']['js'][] = array(
    'type' => 'file',
    'data' => drupal_get_path('module', 'aicapp') . '/js/object-select.js',
  );
  $form['aicapp_app_search_objects_list']['object_search'] = array(
    '#type' => 'textfield',
    '#title' => t('Search for an Artwork Object'),
    '#prefix' => '<div style="display:inline-block;width:100%;">',
    '#suffix' => '</div>',
    '#autocomplete_path' => $autocomplete_path,
    '#attributes' => array('autocomplete' => 'off'),
  );
  $form['aicapp_app_search_objects_list']['add_object'] = array(
    '#type' => 'submit',
    '#name' => 'add-another',
    '#value' => t('Add another Object'),
    '#submit' => array(
      'aicapp_app_search_add_more_add_one',
    ),
    '#validate' => array('aicapp_app_search_add_more_add_validate'),
    '#ajax' => array(
      'callback' => 'aicapp_app_search_add_more_callback',
      'wrapper' => 'objects-wrapper',
    ),
    '#attributes' => array('onclick' => 'this.form.reset(); return false;'),
  );
  $form['actions'] = array(
    '#type' => 'actions',
  );
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
    '#attributes' => array('class' => array('primary', 'button', 'radius')),
  );
  return $form;
}

/**
 * Validation callback.
 */
function aicapp_app_search_admin_validate($form, &$form_state) {
  $objects = array();
  $values = $form_state['values']['aicapp_app_search_objects_list'];
  if (empty($form_state['values']['aicapp_search_strings'])) {
    form_set_error('aicapp_search_strings', t('You must enter at least suggested search string.'));
    return;
  }
  // First replace any spaces with commas.
  $search_strings = explode(',', preg_replace("/\r|\n/", ',', trim($form_state['values']['aicapp_search_strings'])));
  if (count($search_strings)) {
    $search_strings = array_filter(array_unique($search_strings));
    $form_state['values']['aicapp_search_strings'] = $search_strings;
  }
  else {
    form_set_error('aicapp_search_strings', 'If providing more than one seach suggestion, add a comma or new line between suggested search string. Make sure there is no comma at the end.');
  }
  if (empty($form_state['values']['aicapp_app_search_objects_list']['objects']['elements']) && $form_state['clicked_button']['#value'] !== 'Remove this Object') {
    form_set_error('aicapp_app_search_objects_list', t('One ore more Objects must be selected.'));
  }
}

/**
 * Sumbit callback.
 */
function aicapp_app_search_admin_submit($form, &$form_state) {
  $nids = array();
  if (!empty($form_state['values']['aicapp_app_search_objects_list']['objects']['elements'])) {
    usort($form_state['values']['aicapp_app_search_objects_list']['objects']['elements'], 'aic_app_sort_row_elements');
  }
  foreach ($form_state['values']['aicapp_app_search_objects_list']['objects']['elements'] as $item) {
    $nids[] = $item['nid'];
  }
  // Persist values.
  variable_set('aicapp_search_objects', $nids);
  variable_set('aicapp_search_strings', $form_state['values']['aicapp_search_strings']);
}

/**
 * Add more callback.
 */
function aicapp_app_search_add_more_callback($form, $form_state) {
  return $form['aicapp_app_search_objects_list'];
}

/**
 * Validation callback for adding an object.
 */
function aicapp_app_search_add_more_add_validate($form, &$form_state) {
  $values = $form_state['values']['aicapp_app_search_objects_list'];
  $search_item = $values['object_search'];
  preg_match('/\((\d+)\)$/', trim($search_item, '"'), $matches);
  if (empty($matches[1])) {
    form_set_error('aicapp_app_search_objects_list][object_search]', t('Object not found.'));
    return;
  }
  else {
    $node = node_load($matches[1]);
    $new = array(
      'nid' => $matches[1],
      'object' => $node->title,
      'weight' => 999,
    );
    $form_state['values']['aicapp_app_search_objects_list']['objects']['elements'][] = $new;
  }
}

/**
 * Add more add one more object callback.
 */
function aicapp_app_search_add_more_add_one($form, &$form_state) {
  $form_state['input'] = array();
  $latest_object = array_slice($form_state['values']['aicapp_app_search_objects_list']['objects']['elements'], -1, 1);
  if (!empty($latest_object[0])) {
    $form_state['objects'] += array(
      $latest_object[0]['nid'] => array(
        'object' => $latest_object[0]['object'],
      ),
    );
  }
  $nids = array();
  foreach ($form_state['values']['aicapp_app_search_objects_list']['objects']['elements'] as $key => $item) {
    $nids[] = $item['nid'];
  }
  variable_set('aicapp_search_objects', $nids);
  $form_state['rebuild'] = TRUE;
}

/**
 * Validation callback for adding an object.
 */
function aicapp_app_search_add_more_remove_validate($form, &$form_state) {

}

/**
 * Add more remove one more object callback.
 */
function aicapp_app_search_add_more_remove_one($form, &$form_state) {
  if (!empty($form_state['clicked_button']['#name'])) {
    $item_to_remove = str_replace('remove-', '', $form_state['clicked_button']['#name']);
    if (!empty($form_state['objects'][$item_to_remove])) {
      unset($form_state['objects'][$item_to_remove]);
    }
  }
  $nids = array();
  foreach ($form_state['objects'] as $key => $item) {
    $nids[] = $key;
  }
  variable_set('aicapp_search_objects', $nids);
  $form_state['rebuild'] = TRUE;
}

/**
 * Helper function for sorting entry weights.
 */
function aic_app_sort_row_elements($a, $b) {
  if (isset($a['weight']) && isset($b['weight'])) {
    return $a['weight'] < $b['weight'] ? -1 : 1;
  }
  return 0;
}
