<?php
/**
 * @file
 * aic_mobile_cms_floor_map_export.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function aic_mobile_cms_floor_map_export_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
