<?php
/**
 * @file
 * aic_mobile_cms_content_types.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function aic_mobile_cms_content_types_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access content'.
  $permissions['access content'] = array(
    'name' => 'access content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'access content overview'.
  $permissions['access content overview'] = array(
    'name' => 'access content overview',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'add objects'.
  $permissions['add objects'] = array(
    'name' => 'add objects',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'aicapp',
  );

  // Exported permission: 'administer aic api settings'.
  $permissions['administer aic api settings'] = array(
    'name' => 'administer aic api settings',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'aicapp',
  );

  // Exported permission: 'administer content types'.
  $permissions['administer content types'] = array(
    'name' => 'administer content types',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'administer entity translation'.
  $permissions['administer entity translation'] = array(
    'name' => 'administer entity translation',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'entity_translation',
  );

  // Exported permission: 'administer features'.
  $permissions['administer features'] = array(
    'name' => 'administer features',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'features',
  );

  // Exported permission: 'administer field collections'.
  $permissions['administer field collections'] = array(
    'name' => 'administer field collections',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'field_collection',
  );

  // Exported permission: 'administer fieldgroups'.
  $permissions['administer fieldgroups'] = array(
    'name' => 'administer fieldgroups',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'field_group',
  );

  // Exported permission: 'administer fields'.
  $permissions['administer fields'] = array(
    'name' => 'administer fields',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'field',
  );

  // Exported permission: 'administer languages'.
  $permissions['administer languages'] = array(
    'name' => 'administer languages',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'locale',
  );

  // Exported permission: 'administer nodes'.
  $permissions['administer nodes'] = array(
    'name' => 'administer nodes',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'administer pathauto'.
  $permissions['administer pathauto'] = array(
    'name' => 'administer pathauto',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'pathauto',
  );

  // Exported permission: 'administer taxonomy'.
  $permissions['administer taxonomy'] = array(
    'name' => 'administer taxonomy',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'taxonomy',
  );

  // Exported permission: 'create audio content'.
  $permissions['create audio content'] = array(
    'name' => 'create audio content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create basic_page content'.
  $permissions['create basic_page content'] = array(
    'name' => 'create basic_page content',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create exhibition content'.
  $permissions['create exhibition content'] = array(
    'name' => 'create exhibition content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create gallery content'.
  $permissions['create gallery content'] = array(
    'name' => 'create gallery content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create map_annotation content'.
  $permissions['create map_annotation content'] = array(
    'name' => 'create map_annotation content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create map_floor content'.
  $permissions['create map_floor content'] = array(
    'name' => 'create map_floor content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create object content'.
  $permissions['create object content'] = array(
    'name' => 'create object content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create page content'.
  $permissions['create page content'] = array(
    'name' => 'create page content',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create tour content'.
  $permissions['create tour content'] = array(
    'name' => 'create tour content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any audio content'.
  $permissions['delete any audio content'] = array(
    'name' => 'delete any audio content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any basic_page content'.
  $permissions['delete any basic_page content'] = array(
    'name' => 'delete any basic_page content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );


  // Exported permission: 'delete any exhibition content'.
  $permissions['delete any exhibition content'] = array(
    'name' => 'delete any exhibition content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any gallery content'.
  $permissions['delete any gallery content'] = array(
    'name' => 'delete any gallery content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any map_annotation content'.
  $permissions['delete any map_annotation content'] = array(
    'name' => 'delete any map_annotation content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any map_floor content'.
  $permissions['delete any map_floor content'] = array(
    'name' => 'delete any map_floor content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any object content'.
  $permissions['delete any object content'] = array(
    'name' => 'delete any object content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any page content'.
  $permissions['delete any page content'] = array(
    'name' => 'delete any page content',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any tour content'.
  $permissions['delete any tour content'] = array(
    'name' => 'delete any tour content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own audio content'.
  $permissions['delete own audio content'] = array(
    'name' => 'delete own audio content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own basic_page content'.
  $permissions['delete own basic_page content'] = array(
    'name' => 'delete own basic_page content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own exhibition content'.
  $permissions['delete own exhibition content'] = array(
    'name' => 'delete own exhibition content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own gallery content'.
  $permissions['delete own gallery content'] = array(
    'name' => 'delete own gallery content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own map_annotation content'.
  $permissions['delete own map_annotation content'] = array(
    'name' => 'delete own map_annotation content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own map_floor content'.
  $permissions['delete own map_floor content'] = array(
    'name' => 'delete own map_floor content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own object content'.
  $permissions['delete own object content'] = array(
    'name' => 'delete own object content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own page content'.
  $permissions['delete own page content'] = array(
    'name' => 'delete own page content',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own tour content'.
  $permissions['delete own tour content'] = array(
    'name' => 'delete own tour content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete revisions'.
  $permissions['delete revisions'] = array(
    'name' => 'delete revisions',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete terms in categories'.
  $permissions['delete terms in categories'] = array(
    'name' => 'delete terms in categories',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'taxonomy',
  );

  // Exported permission: 'edit any audio content'.
  $permissions['edit any audio content'] = array(
    'name' => 'edit any audio content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any basic_page content'.
  $permissions['edit any basic_page content'] = array(
    'name' => 'edit any basic_page content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any exhibition content'.
  $permissions['edit any exhibition content'] = array(
    'name' => 'edit any exhibition content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any gallery content'.
  $permissions['edit any gallery content'] = array(
    'name' => 'edit any gallery content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any map_annotation content'.
  $permissions['edit any map_annotation content'] = array(
    'name' => 'edit any map_annotation content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any map_floor content'.
  $permissions['edit any map_floor content'] = array(
    'name' => 'edit any map_floor content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any object content'.
  $permissions['edit any object content'] = array(
    'name' => 'edit any object content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any page content'.
  $permissions['edit any page content'] = array(
    'name' => 'edit any page content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any tour content'.
  $permissions['edit any tour content'] = array(
    'name' => 'edit any tour content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own audio content'.
  $permissions['edit own audio content'] = array(
    'name' => 'edit own audio content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own basic_page content'.
  $permissions['edit own basic_page content'] = array(
    'name' => 'edit own basic_page content',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own exhibition content'.
  $permissions['edit own exhibition content'] = array(
    'name' => 'edit own exhibition content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own gallery content'.
  $permissions['edit own gallery content'] = array(
    'name' => 'edit own gallery content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own map_annotation content'.
  $permissions['edit own map_annotation content'] = array(
    'name' => 'edit own map_annotation content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own map_floor content'.
  $permissions['edit own map_floor content'] = array(
    'name' => 'edit own map_floor content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own object content'.
  $permissions['edit own object content'] = array(
    'name' => 'edit own object content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own page content'.
  $permissions['edit own page content'] = array(
    'name' => 'edit own page content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own tour content'.
  $permissions['edit own tour content'] = array(
    'name' => 'edit own tour content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit terms in categories'.
  $permissions['edit terms in categories'] = array(
    'name' => 'edit terms in categories',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'taxonomy',
  );

  // Exported permission: 'export nodes'.
  $permissions['export nodes'] = array(
    'name' => 'export nodes',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node_export',
  );

  // Exported permission: 'export own nodes'.
  $permissions['export own nodes'] = array(
    'name' => 'export own nodes',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'node_export',
  );

  // Exported permission: 'generate features'.
  $permissions['generate features'] = array(
    'name' => 'generate features',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'features',
  );

  // Exported permission: 'manage features'.
  $permissions['manage features'] = array(
    'name' => 'manage features',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'features',
  );

  // Exported permission: 'notify of path changes'.
  $permissions['notify of path changes'] = array(
    'name' => 'notify of path changes',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'pathauto',
  );

  // Exported permission: 'rename features'.
  $permissions['rename features'] = array(
    'name' => 'rename features',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'features',
  );

  // Exported permission: 'revert revisions'.
  $permissions['revert revisions'] = array(
    'name' => 'revert revisions',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'toggle field translatability'.
  $permissions['toggle field translatability'] = array(
    'name' => 'toggle field translatability',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'entity_translation',
  );

  // Exported permission: 'translate any entity'.
  $permissions['translate any entity'] = array(
    'name' => 'translate any entity',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'entity_translation',
  );

  // Exported permission: 'translate interface'.
  $permissions['translate interface'] = array(
    'name' => 'translate interface',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'locale',
  );

  // Exported permission: 'translate node entities'.
  $permissions['translate node entities'] = array(
    'name' => 'translate node entities',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'entity_translation',
  );

  // Exported permission: 'view own unpublished content'.
  $permissions['view own unpublished content'] = array(
    'name' => 'view own unpublished content',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  // Exported permission: 'view revisions'.
  $permissions['view revisions'] = array(
    'name' => 'view revisions',
    'roles' => array(
      'admin' => 'admin',
      'editor' => 'editor',
      'publisher' => 'publisher',
    ),
    'module' => 'node',
  );

  return $permissions;
}
