<?php
/**
 * @file
 * aic_mobile_cms_content_types.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function aic_mobile_cms_content_types_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: main-menu_audio:audio.
  $menu_links['main-menu_audio:audio'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'audio',
    'router_path' => 'audio',
    'link_title' => 'Audio',
    'options' => array(
      'identifier' => 'main-menu_audio:audio',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: main-menu_dahsboard:featured.
  $menu_links['main-menu_dahsboard:featured'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'featured',
    'router_path' => 'featured',
    'link_title' => 'Dahsboard',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_dahsboard:featured',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -42,
    'customized' => 1,
  );
  // Exported menu link: main-menu_galleries:galleries.
  $menu_links['main-menu_galleries:galleries'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'galleries',
    'router_path' => 'galleries',
    'link_title' => 'Galleries',
    'options' => array(
      'identifier' => 'main-menu_galleries:galleries',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: main-menu_general-info:node/1.
  $menu_links['main-menu_general-info:node/1'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/1',
    'router_path' => 'node/%',
    'link_title' => 'General Info',
    'options' => array(
      'identifier' => 'main-menu_general-info:node/1',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Exported menu link: main-menu_map-annotations:annotations.
  $menu_links['main-menu_map-annotations:annotations'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'annotations',
    'router_path' => 'annotations',
    'link_title' => 'Map Annotations',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_map-annotations:annotations',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -43,
    'customized' => 1,
  );
  // Exported menu link: main-menu_map-floors:annotations.
  $menu_links['main-menu_map-floors:annotations'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'annotations',
    'router_path' => 'annotations',
    'link_title' => 'Map Floors',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_map-floors:annotations',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -44,
    'customized' => 1,
  );
  // Exported menu link: main-menu_objects:annotations.
  $menu_links['main-menu_objects:annotations'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'annotations',
    'router_path' => 'annotations',
    'link_title' => 'Objects',
    'options' => array(
      'identifier' => 'main-menu_objects:annotations',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -46,
    'customized' => 1,
  );
  // Exported menu link: main-menu_objects:objects.
  $menu_links['main-menu_objects:objects'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'objects',
    'router_path' => 'objects',
    'link_title' => 'Objects',
    'options' => array(
      'identifier' => 'main-menu_objects:objects',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -47,
    'customized' => 1,
  );
  // Exported menu link: main-menu_tours:tours.
  $menu_links['main-menu_tours:tours'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'tours',
    'router_path' => 'tours',
    'link_title' => 'Tours',
    'options' => array(
      'identifier' => 'main-menu_tours:tours',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -45,
    'customized' => 1,
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Audio');
  t('Dahsboard');
  t('Galleries');
  t('General Info');
  t('Map Annotations');
  t('Map Floors');
  t('Objects');
  t('Tours');

  return $menu_links;
}
