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

  // Exported menu link: main-menu_app-search-page:app-search.
  $menu_links['main-menu_app-search-page:app-search'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'app-search',
    'router_path' => 'app-search',
    'link_title' => 'App Search Page',
    'options' => array(
      'identifier' => 'main-menu_app-search-page:app-search',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 15,
    'customized' => 1,
  );
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
  // Exported menu link: main-menu_exhibitions:exhibitions.
  $menu_links['main-menu_exhibitions:exhibitions'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'exhibitions',
    'router_path' => 'exhibitions',
    'link_title' => 'Exhibitions',
    'options' => array(
      'identifier' => 'main-menu_exhibitions:exhibitions',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 6,
    'customized' => 0,
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
  t('App Search Page');
  t('Audio');
  t('Exhibitions');
  t('Galleries');
  t('General Info');
  t('Objects');
  t('Tours');

  return $menu_links;
}
