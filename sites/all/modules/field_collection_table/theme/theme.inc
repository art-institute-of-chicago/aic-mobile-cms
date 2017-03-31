<?php

/**
 * Print a single row of multiple fields.
 */
function theme_field_collection_table_multiple_value_field($variables) {
  $element = $variables['element'];
  $header = array();
  $cells = array();

  // Order field widgets by their widget weight.
  $instances = field_info_instances($element['#entity_type'], $element['#bundle']);
  uasort($instances, '_field_collection_table_sort_items_widget_helper');
  foreach ($instances as $field_name => $instance) {
    if (empty($element[$field_name])) {
      continue;
    }

    $header[] = _field_collection_table_get_title($element[$field_name]);
    $cells[] = array('data' => $element[$field_name]);

    // Remove the original field to prevent duplicate printing.
    unset($element[$field_name]);
  }

  $element['field_collection_table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => array($cells),
    '#weight' => 0,
  );

  $element['#sorted'] = FALSE;
  return drupal_render_children($element);
}

/**
 * Comparison function for sorting field instances by their widget's weight.
 */
function _field_collection_table_sort_items_widget_helper($a, $b) {
  $a_weight = (is_array($a) && isset($a['widget']['weight']) ? $a['widget']['weight'] : 0);
  $b_weight = (is_array($b) && isset($b['widget']['weight']) ? $b['widget']['weight'] : 0);
  return $a_weight - $b_weight;
}

/**
 * Replacement for theme_field_multiple_value_form().
 *
 * Each field is printed in a separate cell.
 */
function theme_field_collection_table_multiple_value_fields($variables) {
  $element = $variables['element'];
  $output = '';
  /*
   * MN
   * extract display settings if we can find them
   */
  $settings = (isset($element['#instance']['display']['default']['settings']) ?
    $element['#instance']['display']['default']['settings'] :
    array('csscolrow' => FALSE, 'cssfield' => FALSE));


  if (isset($element['#cardinality']) && ($element['#cardinality'] > 1 || $element['#cardinality'] == FIELD_CARDINALITY_UNLIMITED)) {
    $table_id = drupal_html_id($element['#field_name'] . '_values');
    $order_class = $element['#field_name'] . '-delta-order';
    $required = !empty($element['#required']) ? '<span class="form-required" title="' . t('This field is required. ') . '">*</span>' : '';

    $rows = array();

    // Sort items according to '_weight' (needed when the form comes back after
    // preview or failed validation)
    $items = array();
    foreach (element_children($element) as $key) {
      if (!isset($element[$key]['#entity_type'])) {
        if ($key === 'add_more') {
          $add_more_button = &$element[$key];
        }
      }
      else {
        $items[] = &$element[$key];
      }
    }
    usort($items, '_field_sort_items_value_helper');


    // Add header for table dragging.
    if (!$element['#custom_settings']['nodragging']) {

      // check if we need to add css classes for rows and columns
      $classes = array('field-label');
      if (isset($settings['cssrowcol']) && $settings['cssrowcol']) {
        // add css classes to field label tat is in the first column of the header
        $classes = array_merge($classes,array('h1','c1','h1-c1'));
      }
      // check if we need to add css field classes
      if (isset($settings['cssfield']) && $settings['cssfield']) {
        // add css classes to field label tat is in the first column of the header
        $classes = array_merge($classes,array('label_'.$element['#field_name']));
      }

      $header = array(
          array(
              'data' => '<label>' . t('!title: !required', array('!title' => $element['#title'], '!required' => $required)) . "</label>",
              'class' => $classes,
          ),
      );
    }

    // Add the items as table rows.
    foreach ($items as $key => $item) {
      uasort($item, 'element_sort');
      $item['_weight']['#attributes']['class'] = array($order_class);

      // find row number
      $rown = count($rows)+1;
      //initialize cells array
      $cells = array();

      // Add classes for dragging if needed.
      if (!$element['#custom_settings']['nodragging']) {
        // insert css class identifiers if needed
        $classes = array('field-multiple-drag','field_body');
        if (isset($settings['cssrowcol']) && $settings['cssrowcol']) {
          // add css classes to field label that is in the first column of the header
          $classes = array_merge($classes,array('r'.$rown,'c1','r'.$rown.'-c1'));
        }
        // check if we need to add css field classes
        if (isset($settings['cssfield']) && $settings['cssfield']) {
          // add css classes to field label tat is in the first column of the header
          $classes = array_merge($classes,array('drag_r'.$rown.'_'.$element['#field_name'],'td_drag','drag_'.$element['#field_name']));
        }

        // prepare row
        $cells = array(
          array('data' => '', 'class' => $classes),
        );
      }

      foreach (element_children($item) as $field_name) {
        // find column number
        $coln = count($cells)+1;
        // Don't add the _weight
        if (!$element['#custom_settings']['nodragging'] || $field_name != '_weight') {
          // Only add the header once.
          if ($key == 0) {
            // check if we need to add css classes for rows and columns
            $classes = array('field-label');
            if (isset($settings['cssrowcol']) && $settings['cssrowcol']) {
              // add css classes to field label tat is in the first column of the header
              $classes = array_merge($classes,array('h1','c'.$coln,'h1-c'.$coln));
            }
            // check if we need to add css field classes
            if (isset($settings['cssfield']) && $settings['cssfield']) {
              // add css classes to field label tat is in the first column of the header
              $classes = array_merge($classes,array('h1_'.$field_name,'th_'.$field_name));
            }
            $header[] = array(
              'data' => '<label>' . t('!title', array('!title' => _field_collection_table_get_title($item[$field_name]))) . '</label>',
              'class' => $classes,
            );
          }
          // check if we need to add css classes for rows and columns
          $classes = array('field-body');
          if (isset($settings['cssrowcol']) && $settings['cssrowcol']) {
            // add css classes to field label tat is in the first column of the header
            $classes = array_merge($classes,array('r'.$rown,'c'.$coln,'r'.$rown.'-c'.$coln));
          }
          // check if we need to add css field classes
          if (isset($settings['cssfield']) && $settings['cssfield']) {
            // add css classes to field label tat is in the first column of the header
            $classes = array_merge($classes,array('r'.$rown.'_'.$field_name,'td_'.$field_name));
          }
          // append value to cells array
          $cells[] = array(
            'data' => $item[$field_name],
            'class' => $classes);
        }
      }
      // Mark rows as draggable if needed.
      if (!$element['#custom_settings']['nodragging']) {
        $rows[] = array(
          'data' => $cells,
          'class' => array('draggable'),
        );
      }
      else {
        $rows[] = array(
          'data' => $cells,
         );
      }
    }

    $output = array(
      '#prefix' => '<div class="form-item">',
      '#suffix' => '</div>',
    );
    $output['field_collection_table'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#weight' => 20,
      '#attributes' => array(
        'id' => $table_id,
        'class' => array(
          'field-multiple-table',
        ),
      ),
    );
    if (!empty($element['#description'])) {
      $output[] = array(
        '#prefix' => '<div class="description">',
        '#suffix' => '</div>',
        '#markup' => $element['#description'],
        '#weight' => 30,
      );
    }
    if (isset($add_more_button)) {
      $add_more_button['#weight'] = 40;
      $output[] = $add_more_button + array(
        '#prefix' => '<div class="clearfix">',
        '#suffix' => '</div>',
      );
    }

    $output = drupal_render($output);

    // Add table drag
    if (!$element['#custom_settings']['nodragging']) {
      drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);
    }
  }
  else {
    foreach (element_children($element) as $key) {
      $output .= drupal_render($element[$key]);
    }
  }

  return $output;
}

/**
 * Implements template_preprocess_HOOK__PATTERN().
 */
function template_preprocess_table__field_collection_table(&$variables) {
  if (empty($variables['settings'])) {
    return;
  }
  if (isset($variables['settings']['empty'])) {
    _field_collection_table_hide_empty($variables);
  }
}

/**
 * Remove columns that are entirely empty.
 */
function _field_collection_table_hide_empty(&$variables) {
  $rows = $variables['rows'];

  $count = array();
  foreach ($rows as $row_delta => $row) {
    foreach ($row['data'] as $column_delta => $column) {
      if (!isset($count[$column_delta])) {
        $count[$column_delta] = 0;
      }
      if (isset($column['data']['#empty'])) {
        $count[$column_delta]++;
      }
    }
  }
  foreach ($count as $column_delta => $column) {
    if ($column === count($rows)) {
      foreach ($rows as $row_delta => $row) {
        unset($variables['rows'][$row_delta]['data'][$column_delta]);
        unset($variables['header'][$column_delta]);
      }
    }
  }
}

/**
 * Derivative of theme_table() solely for the HOOK_preprocess_table__PATTERN().
 */
function theme_table__field_collection_table($variables) {
  return theme('table', $variables);
}

/**
 * Helps find the title of the field, as it could be in several places.
 */
function _field_collection_table_get_title($field) {
  $title = '';

  if (isset($field['#language']) && isset($field[$field['#language']])) {
    $language = $field['#language'];
    if (isset($field[$language]['#title'])) {
      $title = $field[$language]['#title'];
    }
    elseif (isset($field[$language][0]['#title'])) {
      $title = $field[$language][0]['#title'];
    }
  }
  elseif (isset($field['#title'])) {
    $title = empty($field['#is_weight']) ? $field['#title'] : t('Order');
  }
  elseif (isset($field['#value'])) {
    $title = $field['#value'];
  }

  return $title;
}
