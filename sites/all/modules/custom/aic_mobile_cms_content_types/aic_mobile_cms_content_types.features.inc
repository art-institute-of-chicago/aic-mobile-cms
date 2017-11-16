<?php
/**
 * @file
 * aic_mobile_cms_content_types.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function aic_mobile_cms_content_types_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function aic_mobile_cms_content_types_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function aic_mobile_cms_content_types_node_info() {
  $items = array(
    'audio' => array(
      'name' => t('Audio'),
      'base' => 'node_content',
      'description' => t('Audio files for tours and art objects'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'basic_page' => array(
      'name' => t('Page'),
      'base' => 'node_content',
      'description' => t('A page (like the home page)'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'event' => array(
      'name' => t('Event'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Event Title'),
      'help' => '',
    ),
    'exhibition' => array(
      'name' => t('Exhibition'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Exhibition Title'),
      'help' => '',
    ),
    'gallery' => array(
      'name' => t('Gallery'),
      'base' => 'node_content',
      'description' => t('The node type to hold gallery content'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'map_annotation' => array(
      'name' => t('Map Annotation'),
      'base' => 'node_content',
      'description' => t('Options such as Restroom, Gift Shop, etc.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'map_floor' => array(
      'name' => t('Map Floor'),
      'base' => 'node_content',
      'description' => t('This is used to store a pdf map of the interior walls of each floor of the museum.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'object' => array(
      'name' => t('Object'),
      'base' => 'node_content',
      'description' => t('Holds content about objects in the museum'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'page' => array(
      'name' => t('General Info'),
      'base' => 'node_content',
      'description' => t('There should only be one of these. '),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'tour' => array(
      'name' => t('Tour'),
      'base' => 'node_content',
      'description' => t('File type to hold Tours. '),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
