<?php

/**
 * Implements hook_drush_command().
 */
function entity_translation_upgrade_drush_command() {
  $items = array();

  $items['entity-translation-upgrade'] = array(
    'description' => "Upgrades all nodes of the specified content type from Content Translation to Entity Translation.",
    'arguments' => array(
      'content_type' => 'Content type of nodes to be upgraded.',
    ),
    'examples' => array(
      'drush entity-translation-upgrade article' => 'Upgrades all nodes of content type "article" from Content Translation to Entity Translation.',
    ),
    'aliases' => array('etu'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_FULL,
  );

  return $items;
}

/**
 * Implements hook_drush_help().
 *
 * @param
 *   A string with the help section
 *
 * @return
 *   A string with the help text for the entity-translation-upgrade command
 */
function entity_translation_upgrade_drush_help($section) {
  switch ($section) {
    case 'drush:entity-translation-upgrade':
      return dt("Brief help for Drush command entity-translation-upgrade.");

    case 'meta:entity_translation_upgrade:title':
      return dt("Entity Translation Upgrade commands");

    case 'meta:entity_translation_upgrade:summary':
      return dt("Upgrading nodes to Entity Translation.");
  }
}

/**
 * Implements drush_hook_COMMAND().
 *
 * @param
 *   The content type of which the nodes shall be upgraded
 *
 * Runs the batch upgrading nodes of the specified content_type to Entity
 * Translation. Lets user choose content type from a list, if argument has
 * not been provided.
 */
function drush_entity_translation_upgrade($content_type = "") {
  // Get all installed content types.
  $available_types = array();
  $available_types_chose = array();
  $available_types_str = '';
  foreach (node_type_get_types() as $type) {
    $available_types[$type->type] = $type->type;
    $available_types_chose[$type->type] = $type->name;
    if (strlen($available_types_str) > 0) {
      $available_types_str .= ', ';
    }
    $available_types_str .= $type->type;
  }

  // If argument content_type is empty, prompt user for content type.
  if (!$content_type) {
    $content_type = drush_choice($available_types_chose, dt('Choose the content type of the nodes to be upgraded to Entity Translation:'));
  }

  // Do content type argument checks.
  if (!$content_type) {
    return TRUE;
  }
  if (strlen($available_types_str) == 0) {
    return drush_set_error(dt('Entity Translation Upgrade cannot run as no content type has been installed.'));
  }
  if (!in_array($content_type, $available_types)) {
    return drush_set_error(dt('"@content_type" is not a valid content type machine name. Please use one of these installed content types as argument: @available_types_str.', array('@content_type' => $content_type, '@available_types_str' => $available_types_str)));
  }

  // Start batch to upgrade nodes of the specified content type.
  $types = array($content_type => $content_type);
  $batch = array(
    'operations' => array(
      array('entity_translation_upgrade_do', array($types)),
      array('entity_translation_upgrade_complete', array()),
    ),
    'finished' => 'entity_translation_upgrade_drush_end',
    'file' => drupal_get_path('module', 'entity_translation_upgrade') . '/entity_translation_upgrade.admin.inc',
    'progressive' => FALSE,
  );
  batch_set($batch);
  drush_backend_batch_process();
}

/**
 * This is the 'finished' batch callback, drush version.
 */
function entity_translation_upgrade_drush_end($success, $results, $operations, $elapsed) {
  // Print result messages.
  if (!empty($results)) {
    $message = format_plural($results['total'], '1 node translation successfully upgraded.', '@count node translations successfully upgraded.');
    $severity = 'ok';
    watchdog('Entity Translation upgrade', '@count node translations successfully upgraded.', array('@count' => $results['total']), WATCHDOG_INFO);
  }
  else {
    $message = t('No node translation available for the upgrade.');
    $severity = 'warning';
  }

  drush_log($message, $severity);
}
