<?php
/**
 * @file
 * aic_export_content.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function aic_export_content_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'object_export';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Object Export';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Objects';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['distinct'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'nid' => 'nid',
    'title' => 'title',
    'field_reference_num' => 'field_reference_num',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'nid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_reference_num' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Header: Global: Text area */
  $handler->display->display_options['header']['area']['id'] = 'area';
  $handler->display->display_options['header']['area']['table'] = 'views';
  $handler->display->display_options['header']['area']['field'] = 'area';
  $handler->display->display_options['header']['area']['content'] = '<a href="/objects/search">Find new Object to Add</a>   |   <a href="/objects/noimg">Objects with no CITI / SOLR Image</a>  |   <a href="/objects/check-objects-gallery">Update Objects\' Galleries</a>
';
  $handler->display->display_options['header']['area']['format'] = 'full_html';
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['text'] = '[edit]';
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['path'] = 'node/[nid]/edit';
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Reference Number */
  $handler->display->display_options['fields']['field_reference_num']['id'] = 'field_reference_num';
  $handler->display->display_options['fields']['field_reference_num']['table'] = 'field_data_field_reference_num';
  $handler->display->display_options['fields']['field_reference_num']['field'] = 'field_reference_num';
  /* Field: Content: Object ID */
  $handler->display->display_options['fields']['field_object_id']['id'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['table'] = 'field_data_field_object_id';
  $handler->display->display_options['fields']['field_object_id']['field'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Gallery Location */
  $handler->display->display_options['fields']['field_gallery_location']['id'] = 'field_gallery_location';
  $handler->display->display_options['fields']['field_gallery_location']['table'] = 'field_data_field_gallery_location';
  $handler->display->display_options['fields']['field_gallery_location']['field'] = 'field_gallery_location';
  /* Field: Content: Location */
  $handler->display->display_options['fields']['field_location']['id'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['table'] = 'field_data_field_location';
  $handler->display->display_options['fields']['field_location']['field'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['label'] = 'Lat/Long';
  /* Field: Content: Audio */
  $handler->display->display_options['fields']['field_object_audio']['id'] = 'field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['table'] = 'field_data_field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['field'] = 'field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['delta_offset'] = '0';
  /* Field: Field: Object Selector Number */
  $handler->display->display_options['fields']['field_object_selector_number']['id'] = 'field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['table'] = 'field_data_field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['field'] = 'field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Published */
  $handler->display->display_options['fields']['status']['id'] = 'status';
  $handler->display->display_options['fields']['status']['table'] = 'node';
  $handler->display->display_options['fields']['status']['field'] = 'status';
  $handler->display->display_options['fields']['status']['not'] = 0;
  /* Field: Content: Highlighted Object */
  $handler->display->display_options['fields']['field_highlighted_object']['id'] = 'field_highlighted_object';
  $handler->display->display_options['fields']['field_highlighted_object']['table'] = 'field_data_field_highlighted_object';
  $handler->display->display_options['fields']['field_highlighted_object']['field'] = 'field_highlighted_object';
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  /* Field: Content: Audio Commentary */
  $handler->display->display_options['fields']['field_audio_commentary']['id'] = 'field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['table'] = 'field_data_field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['field'] = 'field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['settings'] = array(
    'add' => 'Add',
    'edit' => 'Edit',
    'translate' => '',
    'delete' => 'Delete',
    'description' => 1,
    'view_mode' => 'full',
  );
  $handler->display->display_options['fields']['field_audio_commentary']['delta_offset'] = '0';
  /* Field: Content: Object Selector Numbers */
  $handler->display->display_options['fields']['field_object_selector_numbers']['id'] = 'field_object_selector_numbers';
  $handler->display->display_options['fields']['field_object_selector_numbers']['table'] = 'field_data_field_object_selector_numbers';
  $handler->display->display_options['fields']['field_object_selector_numbers']['field'] = 'field_object_selector_numbers';
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  $handler->display->display_options['sorts']['title']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['title']['expose']['label'] = 'Title';
  /* Sort criterion: Content: Gallery Location (field_gallery_location) */
  $handler->display->display_options['sorts']['field_gallery_location_value']['id'] = 'field_gallery_location_value';
  $handler->display->display_options['sorts']['field_gallery_location_value']['table'] = 'field_data_field_gallery_location';
  $handler->display->display_options['sorts']['field_gallery_location_value']['field'] = 'field_gallery_location_value';
  $handler->display->display_options['sorts']['field_gallery_location_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_gallery_location_value']['expose']['label'] = 'Gallery Location';
  /* Sort criterion: Content: Object ID (field_object_id) */
  $handler->display->display_options['sorts']['field_object_id_value']['id'] = 'field_object_id_value';
  $handler->display->display_options['sorts']['field_object_id_value']['table'] = 'field_data_field_object_id';
  $handler->display->display_options['sorts']['field_object_id_value']['field'] = 'field_object_id_value';
  $handler->display->display_options['sorts']['field_object_id_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_object_id_value']['expose']['label'] = 'Object ID ';
  /* Sort criterion: Field: Object Selector Number (field_object_selector_number) */
  $handler->display->display_options['sorts']['field_object_selector_number_value']['id'] = 'field_object_selector_number_value';
  $handler->display->display_options['sorts']['field_object_selector_number_value']['table'] = 'field_data_field_object_selector_number';
  $handler->display->display_options['sorts']['field_object_selector_number_value']['field'] = 'field_object_selector_number_value';
  $handler->display->display_options['sorts']['field_object_selector_number_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_object_selector_number_value']['expose']['label'] = 'Object Selector Number';
  /* Sort criterion: Content: Nid */
  $handler->display->display_options['sorts']['nid']['id'] = 'nid';
  $handler->display->display_options['sorts']['nid']['table'] = 'node';
  $handler->display->display_options['sorts']['nid']['field'] = 'nid';
  $handler->display->display_options['sorts']['nid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['nid']['expose']['label'] = 'Nid';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 'All';
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['exposed'] = TRUE;
  $handler->display->display_options['filters']['status']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['status']['expose']['label'] = 'Published';
  $handler->display->display_options['filters']['status']['expose']['operator'] = 'status_op';
  $handler->display->display_options['filters']['status']['expose']['identifier'] = 'status';
  $handler->display->display_options['filters']['status']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    5 => 0,
    4 => 0,
  );
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'object' => 'object',
  );
  /* Filter criterion: Content: Audio (field_object_audio) */
  $handler->display->display_options['filters']['field_object_audio_nid']['id'] = 'field_object_audio_nid';
  $handler->display->display_options['filters']['field_object_audio_nid']['table'] = 'field_data_field_object_audio';
  $handler->display->display_options['filters']['field_object_audio_nid']['field'] = 'field_object_audio_nid';
  $handler->display->display_options['filters']['field_object_audio_nid']['operator'] = 'not empty';
  $handler->display->display_options['filters']['field_object_audio_nid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['operator_id'] = 'field_object_audio_nid_op';
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['label'] = 'Has Audio?';
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['operator_label'] = 'Has Audio?';
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['operator'] = 'field_object_audio_nid_op';
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['identifier'] = 'field_object_audio_nid';
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['required'] = TRUE;
  $handler->display->display_options['filters']['field_object_audio_nid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    5 => 0,
    4 => 0,
  );
  $handler->display->display_options['filters']['field_object_audio_nid']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_object_audio_nid']['group_info']['label'] = 'Has Audio';
  $handler->display->display_options['filters']['field_object_audio_nid']['group_info']['identifier'] = 'field_object_audio_nid';
  $handler->display->display_options['filters']['field_object_audio_nid']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'Yes',
      'operator' => 'not empty',
      'value' => array(
        'all' => 0,
        267 => 0,
        266 => 0,
        268 => 0,
        269 => 0,
        1029 => 0,
        1015 => 0,
        1019 => 0,
        1018 => 0,
        1016 => 0,
        261 => 0,
        263 => 0,
        259 => 0,
        265 => 0,
        255 => 0,
        1027 => 0,
        1028 => 0,
        1017 => 0,
        1034 => 0,
        1024 => 0,
        1032 => 0,
        253 => 0,
        256 => 0,
        226 => 0,
        1013 => 0,
        1030 => 0,
        1025 => 0,
        1035 => 0,
        1011 => 0,
        257 => 0,
        258 => 0,
        264 => 0,
        254 => 0,
        252 => 0,
        235 => 0,
        227 => 0,
        1014 => 0,
        1031 => 0,
        1010 => 0,
        1033 => 0,
        260 => 0,
        1012 => 0,
        1020 => 0,
        1026 => 0,
      ),
    ),
    2 => array(
      'title' => 'No',
      'operator' => 'empty',
      'value' => array(
        'all' => 0,
        267 => 0,
        266 => 0,
        268 => 0,
        269 => 0,
        1029 => 0,
        1015 => 0,
        1019 => 0,
        1018 => 0,
        1016 => 0,
        261 => 0,
        263 => 0,
        259 => 0,
        265 => 0,
        255 => 0,
        1027 => 0,
        1028 => 0,
        1017 => 0,
        1034 => 0,
        1024 => 0,
        1032 => 0,
        253 => 0,
        256 => 0,
        226 => 0,
        1013 => 0,
        1030 => 0,
        1025 => 0,
        1035 => 0,
        1011 => 0,
        257 => 0,
        258 => 0,
        264 => 0,
        254 => 0,
        252 => 0,
        235 => 0,
        227 => 0,
        1014 => 0,
        1031 => 0,
        1010 => 0,
        1033 => 0,
        260 => 0,
        1012 => 0,
        1020 => 0,
        1026 => 0,
      ),
    ),
  );
  /* Filter criterion: Content: Gallery Location (field_gallery_location) */
  $handler->display->display_options['filters']['field_gallery_location_value']['id'] = 'field_gallery_location_value';
  $handler->display->display_options['filters']['field_gallery_location_value']['table'] = 'field_data_field_gallery_location';
  $handler->display->display_options['filters']['field_gallery_location_value']['field'] = 'field_gallery_location_value';
  $handler->display->display_options['filters']['field_gallery_location_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_gallery_location_value']['expose']['operator_id'] = 'field_gallery_location_value_op';
  $handler->display->display_options['filters']['field_gallery_location_value']['expose']['label'] = 'Gallery Location (field_gallery_location)';
  $handler->display->display_options['filters']['field_gallery_location_value']['expose']['operator'] = 'field_gallery_location_value_op';
  $handler->display->display_options['filters']['field_gallery_location_value']['expose']['identifier'] = 'field_gallery_location_value';
  $handler->display->display_options['filters']['field_gallery_location_value']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_gallery_location_value']['group_info']['label'] = 'On Display';
  $handler->display->display_options['filters']['field_gallery_location_value']['group_info']['identifier'] = 'field_gallery_location_value';
  $handler->display->display_options['filters']['field_gallery_location_value']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'Yes',
      'operator' => '!=',
      'value' => 'Currently not on display',
    ),
    2 => array(
      'title' => 'No',
      'operator' => '=',
      'value' => 'Currently not on display',
    ),
    3 => array(
      'title' => '',
      'operator' => '=',
      'value' => '',
    ),
    4 => array(
      'title' => '',
      'operator' => '=',
      'value' => '',
    ),
  );
  /* Filter criterion: Content: Highlighted Object (field_highlighted_object) */
  $handler->display->display_options['filters']['field_highlighted_object_value']['id'] = 'field_highlighted_object_value';
  $handler->display->display_options['filters']['field_highlighted_object_value']['table'] = 'field_data_field_highlighted_object';
  $handler->display->display_options['filters']['field_highlighted_object_value']['field'] = 'field_highlighted_object_value';
  $handler->display->display_options['filters']['field_highlighted_object_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_highlighted_object_value']['expose']['operator_id'] = 'field_highlighted_object_value_op';
  $handler->display->display_options['filters']['field_highlighted_object_value']['expose']['label'] = 'Highlighted Object (field_highlighted_object)';
  $handler->display->display_options['filters']['field_highlighted_object_value']['expose']['operator'] = 'field_highlighted_object_value_op';
  $handler->display->display_options['filters']['field_highlighted_object_value']['expose']['identifier'] = 'field_highlighted_object_value';
  $handler->display->display_options['filters']['field_highlighted_object_value']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_highlighted_object_value']['group_info']['label'] = 'Highlighted Object';
  $handler->display->display_options['filters']['field_highlighted_object_value']['group_info']['identifier'] = 'field_highlighted_object_value';
  $handler->display->display_options['filters']['field_highlighted_object_value']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'No',
      'operator' => 'or',
      'value' => array(
        0 => '0',
      ),
    ),
    2 => array(
      'title' => 'Yes',
      'operator' => 'or',
      'value' => array(
        1 => '1',
      ),
    ),
  );
  /* Filter criterion: Content: Location (field_location) */
  $handler->display->display_options['filters']['field_location_value']['id'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['table'] = 'field_data_field_location';
  $handler->display->display_options['filters']['field_location_value']['field'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_location_value']['expose']['operator_id'] = 'field_location_value_op';
  $handler->display->display_options['filters']['field_location_value']['expose']['label'] = 'Location (field_location)';
  $handler->display->display_options['filters']['field_location_value']['expose']['operator'] = 'field_location_value_op';
  $handler->display->display_options['filters']['field_location_value']['expose']['identifier'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_location_value']['group_info']['label'] = 'Has Location';
  $handler->display->display_options['filters']['field_location_value']['group_info']['identifier'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'No',
      'operator' => 'empty',
      'value' => 'NULL',
    ),
    2 => array(
      'title' => 'Yes',
      'operator' => 'not empty',
      'value' => '*',
    ),
  );

  /* Display: Data export */
  $handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['text'] = '[edit]';
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['path'] = 'node/[nid]/edit';
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Reference Number */
  $handler->display->display_options['fields']['field_reference_num']['id'] = 'field_reference_num';
  $handler->display->display_options['fields']['field_reference_num']['table'] = 'field_data_field_reference_num';
  $handler->display->display_options['fields']['field_reference_num']['field'] = 'field_reference_num';
  /* Field: Content: Object ID */
  $handler->display->display_options['fields']['field_object_id']['id'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['table'] = 'field_data_field_object_id';
  $handler->display->display_options['fields']['field_object_id']['field'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Gallery Location */
  $handler->display->display_options['fields']['field_gallery_location']['id'] = 'field_gallery_location';
  $handler->display->display_options['fields']['field_gallery_location']['table'] = 'field_data_field_gallery_location';
  $handler->display->display_options['fields']['field_gallery_location']['field'] = 'field_gallery_location';
  /* Field: Content: Location */
  $handler->display->display_options['fields']['field_location']['id'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['table'] = 'field_data_field_location';
  $handler->display->display_options['fields']['field_location']['field'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['label'] = 'Lat/Long';
  /* Field: Content: Audio */
  $handler->display->display_options['fields']['field_object_audio']['id'] = 'field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['table'] = 'field_data_field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['field'] = 'field_object_audio';
  $handler->display->display_options['fields']['field_object_audio']['delta_offset'] = '0';
  /* Field: Field: Object Selector Number */
  $handler->display->display_options['fields']['field_object_selector_number']['id'] = 'field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['table'] = 'field_data_field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['field'] = 'field_object_selector_number';
  $handler->display->display_options['fields']['field_object_selector_number']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Object Selector Numbers */
  $handler->display->display_options['fields']['field_object_selector_numbers']['id'] = 'field_object_selector_numbers';
  $handler->display->display_options['fields']['field_object_selector_numbers']['table'] = 'field_data_field_object_selector_numbers';
  $handler->display->display_options['fields']['field_object_selector_numbers']['field'] = 'field_object_selector_numbers';
  /* Field: Content: Audio Commentary */
  $handler->display->display_options['fields']['field_audio_commentary']['id'] = 'field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['table'] = 'field_data_field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['field'] = 'field_audio_commentary';
  $handler->display->display_options['fields']['field_audio_commentary']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_audio_commentary']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_audio_commentary']['settings'] = array(
    'view_mode' => 'token',
  );
  $handler->display->display_options['fields']['field_audio_commentary']['delta_offset'] = '0';
  /* Field: Content: Published */
  $handler->display->display_options['fields']['status']['id'] = 'status';
  $handler->display->display_options['fields']['status']['table'] = 'node';
  $handler->display->display_options['fields']['status']['field'] = 'status';
  $handler->display->display_options['fields']['status']['not'] = 0;
  /* Field: Content: Highlighted Object */
  $handler->display->display_options['fields']['field_highlighted_object']['id'] = 'field_highlighted_object';
  $handler->display->display_options['fields']['field_highlighted_object']['table'] = 'field_data_field_highlighted_object';
  $handler->display->display_options['fields']['field_highlighted_object']['field'] = 'field_highlighted_object';
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['path'] = 'objects/export';
  $translatables['object_export'] = array(
    t('Master'),
    t('Objects'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('<a href="/objects/search">Find new Object to Add</a>   |   <a href="/objects/noimg">Objects with no CITI / SOLR Image</a>  |   <a href="/objects/check-objects-gallery">Update Objects\' Galleries</a>
'),
    t('[edit]'),
    t('Title'),
    t('Reference Number'),
    t('Object ID'),
    t('Gallery Location'),
    t('Lat/Long'),
    t('Audio'),
    t('Object Selector Number'),
    t('Published'),
    t('Highlighted Object'),
    t('Nid'),
    t('Audio Commentary'),
    t('Object Selector Numbers'),
    t('Object ID '),
    t('Has Audio?'),
    t('Has Audio'),
    t('Gallery Location (field_gallery_location)'),
    t('On Display'),
    t('Highlighted Object (field_highlighted_object)'),
    t('Location (field_location)'),
    t('Has Location'),
    t('Data export'),
  );
  $export['object_export'] = $view;

  return $export;
}
