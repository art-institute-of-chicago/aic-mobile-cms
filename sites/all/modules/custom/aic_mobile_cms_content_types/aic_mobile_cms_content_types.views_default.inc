<?php
/**
 * @file
 * aic_mobile_cms_content_types.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function aic_mobile_cms_content_types_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'audio_files';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Audio Files';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Audio Files';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['default_row_class'] = FALSE;
  $handler->display->display_options['style_options']['row_class_special'] = FALSE;
  $handler->display->display_options['style_options']['columns'] = array(
    'nid' => 'nid',
    'field_gallery_number' => 'field_gallery_number',
    'title' => 'title',
    'field_location' => 'field_location',
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
    'field_gallery_number' => array(
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
    'field_location' => array(
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
  $handler->display->display_options['header']['area']['empty'] = TRUE;
  $handler->display->display_options['header']['area']['content'] = '<a href="/node/add/audio">Add new audio file</a>';
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
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  /* Field: Content: Audio File */
  $handler->display->display_options['fields']['field_audio_file']['id'] = 'field_audio_file';
  $handler->display->display_options['fields']['field_audio_file']['table'] = 'field_data_field_audio_file';
  $handler->display->display_options['fields']['field_audio_file']['field'] = 'field_audio_file';
  $handler->display->display_options['fields']['field_audio_file']['click_sort_column'] = 'fid';
  /* Field: Content: Audio Transcript */
  $handler->display->display_options['fields']['field_audio_transcript']['id'] = 'field_audio_transcript';
  $handler->display->display_options['fields']['field_audio_transcript']['table'] = 'field_data_field_audio_transcript';
  $handler->display->display_options['fields']['field_audio_transcript']['field'] = 'field_audio_transcript';
  $handler->display->display_options['fields']['field_audio_transcript']['type'] = 'text_trimmed';
  $handler->display->display_options['fields']['field_audio_transcript']['settings'] = array(
    'trim_length' => '50',
  );
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  $handler->display->display_options['sorts']['title']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['title']['expose']['label'] = 'Title';
  /* Sort criterion: Content: Audio File (field_audio_file:fid) */
  $handler->display->display_options['sorts']['field_audio_file_fid']['id'] = 'field_audio_file_fid';
  $handler->display->display_options['sorts']['field_audio_file_fid']['table'] = 'field_data_field_audio_file';
  $handler->display->display_options['sorts']['field_audio_file_fid']['field'] = 'field_audio_file_fid';
  $handler->display->display_options['sorts']['field_audio_file_fid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_audio_file_fid']['expose']['label'] = 'Audio File Name';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['status']['expose']['label'] = 'Published';
  $handler->display->display_options['filters']['status']['expose']['operator'] = 'status_op';
  $handler->display->display_options['filters']['status']['expose']['identifier'] = 'status';
  $handler->display->display_options['filters']['status']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
  );
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'audio' => 'audio',
  );

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'audio';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Audio';
  $handler->display->display_options['menu']['weight'] = '1';
  $handler->display->display_options['menu']['name'] = 'main-menu';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['audio_files'] = array(
    t('Master'),
    t('Audio Files'),
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
    t('<a href="/node/add/audio">Add new audio file</a>'),
    t('[edit]'),
    t('Title'),
    t('Audio File'),
    t('Audio Transcript'),
    t('Audio File Name'),
    t('Published'),
    t('Page'),
  );
  $export['audio_files'] = $view;

  $view = new view();
  $view->name = 'galleries';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Galleries';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Galleries';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['default_row_class'] = FALSE;
  $handler->display->display_options['style_options']['row_class_special'] = FALSE;
  $handler->display->display_options['style_options']['columns'] = array(
    'nid' => 'nid',
    'field_gallery_number' => 'field_gallery_number',
    'title' => 'title',
    'field_location' => 'field_location',
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
    'field_gallery_number' => array(
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
    'field_location' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
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
  /* Field: Content: Gallery ID */
  $handler->display->display_options['fields']['field_gallery_id']['id'] = 'field_gallery_id';
  $handler->display->display_options['fields']['field_gallery_id']['table'] = 'field_data_field_gallery_id';
  $handler->display->display_options['fields']['field_gallery_id']['field'] = 'field_gallery_id';
  /* Field: Content: Gallery Number */
  $handler->display->display_options['fields']['field_gallery_number']['id'] = 'field_gallery_number';
  $handler->display->display_options['fields']['field_gallery_number']['table'] = 'field_data_field_gallery_number';
  $handler->display->display_options['fields']['field_gallery_number']['field'] = 'field_gallery_number';
  $handler->display->display_options['fields']['field_gallery_number']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['field_gallery_number']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  /* Field: Content: Location */
  $handler->display->display_options['fields']['field_location']['id'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['table'] = 'field_data_field_location';
  $handler->display->display_options['fields']['field_location']['field'] = 'field_location';
  $handler->display->display_options['fields']['field_location']['element_label_colon'] = FALSE;
  /* Field: Content: Floor */
  $handler->display->display_options['fields']['field_floor']['id'] = 'field_floor';
  $handler->display->display_options['fields']['field_floor']['table'] = 'field_data_field_floor';
  $handler->display->display_options['fields']['field_floor']['field'] = 'field_floor';
  /* Sort criterion: Content: Gallery Number (field_gallery_number) */
  $handler->display->display_options['sorts']['field_gallery_number_value']['id'] = 'field_gallery_number_value';
  $handler->display->display_options['sorts']['field_gallery_number_value']['table'] = 'field_data_field_gallery_number';
  $handler->display->display_options['sorts']['field_gallery_number_value']['field'] = 'field_gallery_number_value';
  $handler->display->display_options['sorts']['field_gallery_number_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_gallery_number_value']['expose']['label'] = 'Gallery Number';
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  $handler->display->display_options['sorts']['title']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['title']['expose']['label'] = 'Title';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['status']['expose']['label'] = 'Published';
  $handler->display->display_options['filters']['status']['expose']['operator'] = 'status_op';
  $handler->display->display_options['filters']['status']['expose']['identifier'] = 'status';
  $handler->display->display_options['filters']['status']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
  );
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'gallery' => 'gallery',
  );
  /* Filter criterion: Content: Location (field_location) */
  $handler->display->display_options['filters']['field_location_value']['id'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['table'] = 'field_data_field_location';
  $handler->display->display_options['filters']['field_location_value']['field'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['operator'] = 'empty';
  $handler->display->display_options['filters']['field_location_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_location_value']['expose']['operator_id'] = 'field_location_value_op';
  $handler->display->display_options['filters']['field_location_value']['expose']['label'] = 'Location (field_location)';
  $handler->display->display_options['filters']['field_location_value']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_location_value']['expose']['operator_label'] = 'Location?';
  $handler->display->display_options['filters']['field_location_value']['expose']['operator'] = 'field_location_value_op';
  $handler->display->display_options['filters']['field_location_value']['expose']['identifier'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    5 => 0,
    4 => 0,
  );
  $handler->display->display_options['filters']['field_location_value']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_location_value']['group_info']['label'] = 'Has Lat/Long?';
  $handler->display->display_options['filters']['field_location_value']['group_info']['identifier'] = 'field_location_value';
  $handler->display->display_options['filters']['field_location_value']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'Yes',
      'operator' => 'not empty',
      'value' => '\'\'',
    ),
    2 => array(
      'title' => 'No',
      'operator' => 'empty',
      'value' => '\'\'',
    ),
  );

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'galleries';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Galleries';
  $handler->display->display_options['menu']['weight'] = '1';
  $handler->display->display_options['menu']['name'] = 'main-menu';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['galleries'] = array(
    t('Master'),
    t('Galleries'),
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
    t('[edit]'),
    t('Gallery ID'),
    t('Gallery Number'),
    t('Title'),
    t('Location'),
    t('Floor'),
    t('Published'),
    t('Location (field_location)'),
    t('Location?'),
    t('Has Lat/Long?'),
    t('Page'),
  );
  $export['galleries'] = $view;

  $view = new view();
  $view->name = 'objects';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Objects';
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
  $handler->display->display_options['header']['area']['empty'] = TRUE;
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

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'objects';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Objects';
  $handler->display->display_options['menu']['weight'] = '2';
  $handler->display->display_options['menu']['name'] = 'main-menu';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['objects'] = array(
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
    t('Object ID '),
    t('Has Audio?'),
    t('Has Audio'),
    t('Gallery Location (field_gallery_location)'),
    t('On Display'),
    t('Highlighted Object (field_highlighted_object)'),
    t('Location (field_location)'),
    t('Has Location'),
    t('Page'),
  );
  $export['objects'] = $view;

  $view = new view();
  $view->name = 'tour_stop_audio_commentaries';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'field_collection_item';
  $view->human_name = 'Tour Stop Audio Commentaries';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Relationship: Field collection item: Entity with the Audio Commentary (field_audio_commentary) */
  $handler->display->display_options['relationships']['field_audio_commentary_node']['id'] = 'field_audio_commentary_node';
  $handler->display->display_options['relationships']['field_audio_commentary_node']['table'] = 'field_collection_item';
  $handler->display->display_options['relationships']['field_audio_commentary_node']['field'] = 'field_audio_commentary_node';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_audio_commentary_audio_target_id']['id'] = 'field_audio_commentary_audio_target_id';
  $handler->display->display_options['relationships']['field_audio_commentary_audio_target_id']['table'] = 'field_data_field_audio_commentary_audio';
  $handler->display->display_options['relationships']['field_audio_commentary_audio_target_id']['field'] = 'field_audio_commentary_audio_target_id';
  /* Field: Field collection item: Field collection item ID */
  $handler->display->display_options['fields']['item_id']['id'] = 'item_id';
  $handler->display->display_options['fields']['item_id']['table'] = 'field_collection_item';
  $handler->display->display_options['fields']['item_id']['field'] = 'item_id';
  $handler->display->display_options['fields']['item_id']['exclude'] = TRUE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['relationship'] = 'field_audio_commentary_node';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: Object ID */
  $handler->display->display_options['fields']['field_object_id']['id'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['table'] = 'field_data_field_object_id';
  $handler->display->display_options['fields']['field_object_id']['field'] = 'field_object_id';
  $handler->display->display_options['fields']['field_object_id']['relationship'] = 'field_audio_commentary_node';
  $handler->display->display_options['fields']['field_object_id']['label'] = '';
  $handler->display->display_options['fields']['field_object_id']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_object_id']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'field_audio_commentary_node';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'field_audio_commentary_audio_target_id';
  $handler->display->display_options['fields']['nid_1']['exclude'] = TRUE;
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'field_audio_commentary_audio_target_id';
  $handler->display->display_options['fields']['type']['exclude'] = TRUE;
  /* Field: Entity translation: Title: translated */
  $handler->display->display_options['fields']['title_field_et']['id'] = 'title_field_et';
  $handler->display->display_options['fields']['title_field_et']['table'] = 'field_data_title_field';
  $handler->display->display_options['fields']['title_field_et']['field'] = 'title_field_et';
  $handler->display->display_options['fields']['title_field_et']['relationship'] = 'field_audio_commentary_audio_target_id';
  $handler->display->display_options['fields']['title_field_et']['label'] = '';
  $handler->display->display_options['fields']['title_field_et']['element_label_colon'] = FALSE;
  /* Field: Entity translation: Track Title: translated */
  $handler->display->display_options['fields']['field_track_title_et']['id'] = 'field_track_title_et';
  $handler->display->display_options['fields']['field_track_title_et']['table'] = 'field_data_field_track_title';
  $handler->display->display_options['fields']['field_track_title_et']['field'] = 'field_track_title_et';
  $handler->display->display_options['fields']['field_track_title_et']['relationship'] = 'field_audio_commentary_audio_target_id';
  $handler->display->display_options['fields']['field_track_title_et']['label'] = '';
  $handler->display->display_options['fields']['field_track_title_et']['element_label_colon'] = FALSE;
  /* Contextual filter: Content: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['relationship'] = 'field_audio_commentary_node';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['summary_options']['items_per_page'] = '25';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['relationship'] = 'field_audio_commentary_node';
  $handler->display->display_options['filters']['type']['value'] = array(
    'object' => 'object',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '0';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'title_field_et' => 'title_field_et',
    'item_id' => 0,
    'nid' => 0,
    'title_field' => 0,
    'nid_1' => 0,
    'type' => 0,
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;

  /* Display: Entity Reference Audio Bumper */
  $handler = $view->new_display('entityreference', 'Entity Reference Audio Bumper', 'entityreference_2');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'title_field_et' => 'title_field_et',
    'item_id' => 0,
    'nid' => 0,
    'title_field' => 0,
    'nid_1' => 0,
    'type' => 0,
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $translatables['tour_stop_audio_commentaries'] = array(
    t('Master'),
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
    t('field_audio_commentary'),
    t('Content entity referenced from field_audio_commentary_audio'),
    t('Field collection item ID'),
    t('.'),
    t(','),
    t('Nid'),
    t('Type'),
    t('All'),
    t('Entity Reference'),
    t('Entity Reference Audio Bumper'),
  );
  $export['tour_stop_audio_commentaries'] = $view;

  $view = new view();
  $view->name = 'tours';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Tours';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Tours';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'edit_node' => 'edit_node',
    'title' => 'title',
    'field_catchphrase' => 'field_catchphrase',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'edit_node' => array(
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
    'field_catchphrase' => array(
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
  $handler->display->display_options['header']['area']['empty'] = TRUE;
  $handler->display->display_options['header']['area']['content'] = '<a href="/node/add/tour">Create new Tour</a>';
  $handler->display->display_options['header']['area']['format'] = 'full_html';
  /* Field: Draggableviews: Content */
  $handler->display->display_options['fields']['draggableviews']['id'] = 'draggableviews';
  $handler->display->display_options['fields']['draggableviews']['table'] = 'node';
  $handler->display->display_options['fields']['draggableviews']['field'] = 'draggableviews';
  $handler->display->display_options['fields']['draggableviews']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['draggableviews']['hide_alter_empty'] = FALSE;
  $handler->display->display_options['fields']['draggableviews']['draggableviews']['ajax'] = 1;
  /* Field: Content: Edit link */
  $handler->display->display_options['fields']['edit_node']['id'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['edit_node']['field'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['label'] = '';
  $handler->display->display_options['fields']['edit_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['edit_node']['text'] = '[edit]';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Description */
  $handler->display->display_options['fields']['field_description']['id'] = 'field_description';
  $handler->display->display_options['fields']['field_description']['table'] = 'field_data_field_description';
  $handler->display->display_options['fields']['field_description']['field'] = 'field_description';
  $handler->display->display_options['fields']['field_description']['type'] = 'text_trimmed';
  $handler->display->display_options['fields']['field_description']['settings'] = array(
    'trim_length' => '150',
  );
  /* Field: Content: Published */
  $handler->display->display_options['fields']['status']['id'] = 'status';
  $handler->display->display_options['fields']['status']['table'] = 'node';
  $handler->display->display_options['fields']['status']['field'] = 'status';
  $handler->display->display_options['fields']['status']['not'] = 0;
  /* Field: Content: Tour Banner */
  $handler->display->display_options['fields']['field_tour_banner']['id'] = 'field_tour_banner';
  $handler->display->display_options['fields']['field_tour_banner']['table'] = 'field_data_field_tour_banner';
  $handler->display->display_options['fields']['field_tour_banner']['field'] = 'field_tour_banner';
  /* Sort criterion: Draggableviews: Weight */
  $handler->display->display_options['sorts']['weight']['id'] = 'weight';
  $handler->display->display_options['sorts']['weight']['table'] = 'draggableviews_structure';
  $handler->display->display_options['sorts']['weight']['field'] = 'weight';
  $handler->display->display_options['sorts']['weight']['draggableviews_setting_view'] = 'tours:page';
  $handler->display->display_options['sorts']['weight']['draggableviews_setting_new_items_bottom_list'] = 1;
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
    4 => 0,
  );
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'tour' => 'tour',
  );
  /* Filter criterion: Content: Tour Banner (field_tour_banner) */
  $handler->display->display_options['filters']['field_tour_banner_value']['id'] = 'field_tour_banner_value';
  $handler->display->display_options['filters']['field_tour_banner_value']['table'] = 'field_data_field_tour_banner';
  $handler->display->display_options['filters']['field_tour_banner_value']['field'] = 'field_tour_banner_value';
  $handler->display->display_options['filters']['field_tour_banner_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_tour_banner_value']['expose']['operator_id'] = 'field_tour_banner_value_op';
  $handler->display->display_options['filters']['field_tour_banner_value']['expose']['label'] = 'Tour Banner (field_tour_banner)';
  $handler->display->display_options['filters']['field_tour_banner_value']['expose']['operator'] = 'field_tour_banner_value_op';
  $handler->display->display_options['filters']['field_tour_banner_value']['expose']['identifier'] = 'field_tour_banner_value';
  $handler->display->display_options['filters']['field_tour_banner_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    5 => 0,
    4 => 0,
  );

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'tours';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Tours';
  $handler->display->display_options['menu']['weight'] = '4';
  $handler->display->display_options['menu']['name'] = 'main-menu';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['tours'] = array(
    t('Master'),
    t('Tours'),
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
    t('<a href="/node/add/tour">Create new Tour</a>'),
    t('Content'),
    t('[edit]'),
    t('Title'),
    t('Description'),
    t('Published'),
    t('Tour Banner'),
    t('Tour Banner (field_tour_banner)'),
    t('Page'),
  );
  $export['tours'] = $view;

  return $export;
}
