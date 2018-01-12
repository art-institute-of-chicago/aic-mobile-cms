<?php
/**
 * @file
 * aic_mobile_cms_content_types.features.language.inc
 */

/**
 * Implements hook_locale_default_languages().
 */
function aic_mobile_cms_content_types_locale_default_languages() {
  $languages = array();

  // Exported language: en.
  $languages['en'] = array(
    'language' => 'en',
    'name' => 'English',
    'native' => 'English',
    'direction' => 0,
    'enabled' => 1,
    'plurals' => 0,
    'formula' => '',
    'domain' => '',
    'prefix' => '',
    'weight' => -10,
  );
  // Exported language: es.
  $languages['es'] = array(
    'language' => 'es',
    'name' => 'Spanish',
    'native' => 'Español',
    'direction' => 0,
    'enabled' => 1,
    'plurals' => 0,
    'formula' => '',
    'domain' => '',
    'prefix' => 'es',
    'weight' => -9,
  );
  // Exported language: zh-hant.
  $languages['zh-hant'] = array(
    'language' => 'zh-hant',
    'name' => 'Chinese, Traditional',
    'native' => '繁體中文',
    'direction' => 0,
    'enabled' => 1,
    'plurals' => 0,
    'formula' => '',
    'domain' => '',
    'prefix' => 'zh-hant',
    'weight' => -7,
  );
  return $languages;
}
