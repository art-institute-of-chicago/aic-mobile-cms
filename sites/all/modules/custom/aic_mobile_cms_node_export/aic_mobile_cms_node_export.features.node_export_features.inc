<?php
/**
 * @file
 * aic_mobile_cms_node_export.features.node_export_features.inc
 */

/**
 * Implements hook_node_export_features_default().
 */
function aic_mobile_cms_node_export_node_export_features_default() {
  $node_export = array(
  'code_string' => 'array(
  (object) array(
      \'changed\' => NULL,
      \'comment\' => \'0\',
      \'created\' => NULL,
      \'data\' => \'a:5:{s:16:"ckeditor_default";s:1:"t";s:20:"ckeditor_show_toggle";s:1:"t";s:14:"ckeditor_width";s:4:"100%";s:13:"ckeditor_lang";s:2:"en";s:18:"ckeditor_auto_lang";s:1:"t";}\',
      \'field_museum_hours\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Open daily 10:30 a.m. to 5:00 p.m. and on Thursday until 8:00 p.m.\',
            \'format\' => NULL,
            \'safe_value\' => \'Open daily 10:30 a.m. to 5:00 p.m. and on Thursday until 8:00 p.m.\',
          ),
        ),
      ),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_timestamp\' => NULL,
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'tshah\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'16\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Museum Information\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'page\',
      \'uid\' => \'16\',
      \'uuid\' => \'76c56e7a-6f43-4d7b-9608-08251d0f308f\',
      \'vid\' => NULL,
      \'vuuid\' => \'08dc2144-888a-446a-9518-e6874b1b55a3\',
    ),
  (object) array(
      \'body\' => array(
        \'und\' => array(
          array(
            \'value\' => "<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">This button will cause the system to compare all objects with the current SOLR data.&nbsp;</span></p>\\r\\n\\r\\n<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">For objects that have been moved into galleries (per SOLR) but are currently marked as \\"Not on display\\" in Drupal:&nbsp;&nbsp;It will send out an email to tell you which objects have recently been put into a gallery, update their gallery number field, and set \\"in Gallery\\" to checked. It will also set these objects to unpublished so you can review &amp; enter lat / long values if needed.</span></p>\\r\\n\\r\\n<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">For objects that were in galleries but now are not (per SOLR), but are currently marked as in a gallery in Drupal: &nbsp;It will send out an email to tell you which objects have been removed from galleries, and will unpublish these objects from Drupal (so they will not be sent to the mobile app).&nbsp;</span></p>\\r\\n",
            \'summary\' => \'\',
            \'format\' => \'full_html\',
            \'safe_value\' => "<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">This button will cause the system to compare all objects with the current SOLR data. </span></p>\\n<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">For objects that have been moved into galleries (per SOLR) but are currently marked as \\"Not on display\\" in Drupal:  It will send out an email to tell you which objects have recently been put into a gallery, update their gallery number field, and set \\"in Gallery\\" to checked. It will also set these objects to unpublished so you can review &amp; enter lat / long values if needed.</span></p>\\n<p><span style=\\"color: rgb(10, 10, 10); font-family: Roboto, Helvetica, Verdana, sans-serif; line-height: 20.8001px; background-color: rgb(254, 254, 254);\\">For objects that were in galleries but now are not (per SOLR), but are currently marked as in a gallery in Drupal:  It will send out an email to tell you which objects have been removed from galleries, and will unpublish these objects from Drupal (so they will not be sent to the mobile app). </span></p>\\n",
            \'safe_summary\' => \'\',
          ),
        ),
      ),
      \'changed\' => NULL,
      \'comment\' => \'0\',
      \'created\' => NULL,
      \'data\' => \'a:5:{s:16:"ckeditor_default";s:1:"t";s:20:"ckeditor_show_toggle";s:1:"t";s:14:"ckeditor_width";s:4:"100%";s:13:"ckeditor_lang";s:2:"en";s:18:"ckeditor_auto_lang";s:1:"t";}\',
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_timestamp\' => NULL,
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'tshah\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'16\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => "Update Objects\' Gallery Status",
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'basic_page\',
      \'uid\' => \'16\',
      \'uuid\' => \'cad63926-3723-466a-a394-47a88017741c\',
      \'vid\' => NULL,
      \'vuuid\' => \'a931d7cd-0010-4d1a-bebd-289424fad221\',
    ),
  (object) array(
      \'body\' => array(
        \'und\' => array(
          array(
            \'value\' => "<p>This is where you can manage the data that gets loaded into the museum\'s mobile app. &nbsp; Once you have finished adding objects&nbsp;or making edits here,&nbsp;be sure to click on \\"Submit for Publishing\\". This creates the data file that the Mobile app is able to access.&nbsp;</p>\\r\\n\\r\\n<p>The \\"refresh gallery list\\" will update the list of galleries, pulled from the SOLR database. &nbsp;It will compare and overwrite any existing galleries, and add any new ones. &nbsp;This also occurs automatically, once each night.&nbsp;</p>\\r\\n\\r\\n<p>The \\"Publish\\" button will create a trigger to cause the web server to sync content&nbsp;files.&nbsp;</p>\\r\\n",
            \'summary\' => \'\',
            \'format\' => \'full_html\',
            \'safe_value\' => "<p>This is where you can manage the data that gets loaded into the museum\'s mobile app.   Once you have finished adding objects or making edits here, be sure to click on \\"Submit for Publishing\\". This creates the data file that the Mobile app is able to access. </p>\\n<p>The \\"refresh gallery list\\" will update the list of galleries, pulled from the SOLR database.  It will compare and overwrite any existing galleries, and add any new ones.  This also occurs automatically, once each night. </p>\\n<p>The \\"Publish\\" button will create a trigger to cause the web server to sync content files. </p>\\n",
            \'safe_summary\' => \'\',
          ),
        ),
      ),
      \'changed\' => NULL,
      \'comment\' => \'0\',
      \'created\' => NULL,
      \'data\' => \'a:5:{s:16:"ckeditor_default";s:1:"t";s:20:"ckeditor_show_toggle";s:1:"t";s:14:"ckeditor_width";s:4:"100%";s:13:"ckeditor_lang";s:2:"en";s:18:"ckeditor_auto_lang";s:1:"t";}\',
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_timestamp\' => NULL,
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'tshah\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'1\',
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'16\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Welcome to the Mobile App Admin tool\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'basic_page\',
      \'uid\' => \'16\',
      \'uuid\' => \'df9d0bbb-9a0c-4ee6-81c8-798eb1728e53\',
      \'vid\' => NULL,
      \'vuuid\' => \'2a2437e0-5feb-4a62-9eda-2004f2f7bbd7\',
    ),
)',
);
  return $node_export;
}
