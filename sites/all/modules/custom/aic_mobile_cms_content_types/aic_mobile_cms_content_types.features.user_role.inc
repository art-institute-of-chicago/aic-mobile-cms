<?php
/**
 * @file
 * aic_mobile_cms_content_types.features.user_role.inc
 */

/**
 * Implements hook_user_default_roles().
 */
function aic_mobile_cms_content_types_user_default_roles() {
  $roles = array();

  // Exported role: admin.
  $roles['admin'] = array(
    'name' => 'admin',
    'weight' => 4,
  );

  // Exported role: editor.
  $roles['editor'] = array(
    'name' => 'editor',
    'weight' => 2,
  );

  // Exported role: publisher.
  $roles['publisher'] = array(
    'name' => 'publisher',
    'weight' => 3,
  );

  return $roles;
}
