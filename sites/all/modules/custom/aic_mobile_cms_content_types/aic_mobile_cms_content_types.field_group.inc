<?php
/**
 * @file
 * aic_mobile_cms_content_types.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function aic_mobile_cms_content_types_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_audio|node|object|form';
  $field_group->group_name = 'group_audio';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'object';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Audio',
    'weight' => '7',
    'children' => array(),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-audio field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups[''] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_custom_object|node|object|form';
  $field_group->group_name = 'group_custom_object';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'object';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Custom Object Info',
    'weight' => '4',
    'children' => array(
      0 => 'field_artist_name',
      1 => 'field_artist_origin',
      2 => 'field_credit_line',
      3 => 'field_copyright_notice',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Custom Object Info',
      'instance_settings' => array(
        'required_fields' => 0,
        'id' => '',
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups[''] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_gallery_app_v1|node|gallery|form';
  $field_group->group_name = 'group_gallery_app_v1';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'gallery';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Deprecated - App Version 1',
    'weight' => '7',
    'children' => array(
      0 => 'field_floor',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Deprecated - App Version 1',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-gallery-app-v1 field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups[''] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_object_app_v1|node|object|form';
  $field_group->group_name = 'group_object_app_v1';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'object';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Deprecated - App Version 1',
    'weight' => '17',
    'children' => array(
      0 => 'field_object_audio',
      1 => 'field_object_selector_number',
      2 => 'field_object_selector_numbers',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Deprecated - App Version 1',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-object-app-v1 field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups[''] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_tour_app_v1|node|tour|form';
  $field_group->group_name = 'group_tour_app_v1';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'tour';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Deprecated - App Version 1',
    'weight' => '14',
    'children' => array(
      0 => 'field_stops2',
      1 => 'field_floor',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsed',
      'label' => 'Deprecated - App Version 1',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-tour-app-v1 field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups[''] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Audio');
  t('Custom Object Info');
  t('Deprecated - App Version 1');

  return $field_groups;
}
