#!/bin/bash

# Requires:
#  * drush
#  * MySQL 5.7 or greater

# Color markup
R=`tput setaf 1`
G=`tput setaf 2`
Y=`tput setaf 3`
C=`tput setaf 6`
W=`tput sgr0`

echo "${C}Welcome to the Mobile App CMS installer.${W}"

function usage() {
    echo "${W}Usage: ./install.sh [/path/to/mobile-app-cms]${W}"
    echo ""
}


# Test for arguments
# You can specify a folder to the path of your JourneyMaker CMS project,
# otherwise we'll assume it's in your current directory.
DIR_ROOT=$1
if [ ! $DIR_ROOT ] || [ ! -d $DIR_ROOT ]; then
    DIR_ROOT="."
fi


# Test for trailing slash - should not be present
dpath=$(echo $DIR_ROOT | awk '{print substr($0,length,1)}')
if [ $dpath = '/' ]; then
    echo "${R}Error: Please don't include a slash '/' at the end of the path.${W}"
    echo
    usage
    exit
fi

# Clear the install log
echo "${G}Clearing old install log...${W}"
rm -f $DIR_ROOT/install.log
touch $DIR_ROOT/install.log

echo "${G}Downloading Drupal...${W}"
curl -s -k -L -o $DIR_ROOT/drupal-7.53.tar.gz https://ftp.drupal.org/files/projects/drupal-7.53.tar.gz
tar -xz -C $DIR_ROOT -f $DIR_ROOT/drupal-7.53.tar.gz
rm $DIR_ROOT/drupal-7.53.tar.gz
find $DIR_ROOT/drupal-7.53 -maxdepth 1 -not -path "$DIR_ROOT/drupal-7.53" -not -path "$DIR_ROOT/drupal-7.53/sites" -not -path "$DIR_ROOT/drupal-7.53/.gitignore" | xargs -I {} mv {} $DIR_ROOT/
chmod u+w $DIR_ROOT/sites/default
mv $DIR_ROOT/drupal-7.53/sites/default/default.settings.php $DIR_ROOT/sites/default/settings.php
rm -rf $DIR_ROOT/drupal-7.53
mkdir $DIR_ROOT/sites/default/files
mkdir $DIR_ROOT/sites/default/files/object-images
mkdir $DIR_ROOT/sites/default/files/tour-images
mkdir $DIR_ROOT/sites/default/files/audio
chmod -R a+wx $DIR_ROOT/sites/default/files
mkdir $DIR_ROOT/trigger
chmod -R a+wx $DIR_ROOT/trigger

echo "${G}Downloading drush_extras...${W}"
./vendor/bin/drush dl drush_extras --yes >> install.log 2>&1

echo "${G}Setting up Drupal install...${W}"


# Get database info
read -p "${W}Which database are you using [mysql]: ${W}" driver
driver=${driver:-mysql}

read -p "${W}What is the database host? [127.0.0.1]: ${W}" host
host=${host:-127.0.0.1}

read -p "${W}What is database name? (Will be created it if it does not exist) [mobileapp]: ${W}" database
database=${database:-mobileapp}

read -p "${W}What is the database user? (Will be created it if it does not exist) [$database]: ${W}" username
username=${username:-$database}

read -s -p "${W}What is the user password? [$database]: ${W}" password
password=${password:-$database}
echo

read -s -p "${W}What is your database ROOT password? (If not entered, database and user will not be created): ${W}" rootpassword
echo


# Create database if it doesn't exist
if [ $driver = 'mysql' ]; then
    if [ $rootpassword ]; then
	mysql -s -uroot -p${rootpassword} -e "CREATE DATABASE IF NOT EXISTS $database"
    fi
else
    echo "${Y}Warning: not creating database if it doesn't already exists. (install.sh not tested with PostgreSQL)${W}"
fi


# Create username if it doesn't exist
if [ $driver = 'mysql' ]; then
    if [ $rootpassword ]; then
	mysql -s -uroot -p${rootpassword} -e "CREATE USER IF NOT EXISTS '${username}'@'${host}' IDENTIFIED BY '${password}';"
	mysql -s -uroot -p${rootpassword} -e "GRANT ALL PRIVILEGES ON ${database}.* TO '${username}'@'${host}';"
    fi
else
    echo "${Y}Warning: not creating user if it doesn't already exists. (not tested with PostgreSQL)${W}"
fi


# Add it to settings.php
cat <<EOT>> $DIR_ROOT/sites/default/settings.php

\$databases['default']['default'] = array(
  'driver' => '$driver',
  'database' => '$database',
  'username' => '$username',
  'password' => '$password',
  'host' => php_sapi_name() == 'cli' ? '127.0.0.1' : '$host',
  'prefix' => '',
);
EOT


# Run Drupal installation
echo "${G}Installing Drupal...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT site-install --db-url=mysql://$username:$password@$host/$database --yes >> install.log 2>&1


# Enable/disable modules
echo "${G}Enabling modules...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT pm-disable --yes \
		   comment \
		   dashboard \
		   overlay \
		   rdf \
		   shortcut \
		   color \
		   bartik \
		   toolbar >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT pm-enable --yes \
		   trigger \
		   admin_menu_toolbar \
		   delete_all \
		   devel \
		   features \
		   aic_mobile_cms_content_types \
		   aic_mobile_cms_node_export \
		   node_export \
		   node_export_features \
		   uuid \
		   field_group \
		   field_permissions \
		   field_readonly \
		   html5_tools \
		   backup_migrate \
		   field_collection_table \
		   logintoboggan \
		   masquerade \
		   pathauto \
		   ckeditor \
		   jquery_update \
		   draggableviews \
		   views_ui \
		   node_reference \
		   aicapp \
		   date \
		   date_api \
		   date_popup \
		   appadmin >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT cache-clear all >> install.log 2>&1


# Adding/removing permissions
echo "${G}Adding permissions...${W}"
echo "${W}Drush takes over from here...${W}"

# These lists of perms generated from production with: ./vendor/bin/drush role-list [role] --no-field-labels --pipe | paste -s -d , -
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'editor' 'access administration menu,access administration pages,access content,access content overview,access contextual links,access draggableviews,add objects,administer nodes,administer search,administer views,cancel account,change own username,create audio content,create gallery content,create tour content,delete any audio content,delete any gallery content,delete any object content,delete any tour content,delete own audio content,delete own basic_page content,delete own gallery content,delete own object content,delete own tour content,delete revisions,edit any audio content,edit any basic_page content,edit any gallery content,edit any object content,edit any page content,edit any tour content,edit own audio content,edit own gallery content,edit own object content,edit own page content,edit own tour content,revert revisions,search content,use advanced search,use text format full_html,view field_gallery_id,view field_gallery_location,view field_in_gallery,view field_object_id,view field_reference_num,view own field_gallery_location,view own field_in_gallery,view own field_object_id,view own field_reference_num,view own unpublished content,view revisions' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'publisher' 'access administration menu,access administration pages,access backup and migrate,access backup files,access content,access content overview,access contextual links,access draggableviews,access user profiles,add objects,administer nodes,administer search,administer views,change own username,create audio content,create gallery content,create tour content,delete any audio content,delete any gallery content,delete any object content,delete any tour content,delete own audio content,delete own basic_page content,delete own gallery content,delete own object content,delete own tour content,delete revisions,edit any audio content,edit any basic_page content,edit any gallery content,edit any object content,edit any page content,edit any tour content,edit own audio content,edit own gallery content,edit own object content,edit own page content,edit own tour content,perform backup,restore from backup,revert revisions,search content,use advanced search,view field_gallery_id,view field_gallery_location,view field_in_gallery,view field_object_id,view field_reference_num,view own field_gallery_location,view own field_in_gallery,view own field_object_id,view own field_reference_num,view own unpublished content,view revisions' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'administrator' 'access administration menu,access administration pages,access all views,access backup and migrate,access backup files,access content,access content overview,access contextual links,access devel information,access draggableviews,access private fields,access site in maintenance mode,access site reports,access user profiles,add objects,administer actions,administer backup and migrate,administer blocks,administer ckeditor,administer content types,administer field collections,administer field permissions,administer fields,administer filters,administer image styles,administer masquerade,administer menu,administer modules,administer nodes,administer pathauto,administer permissions,administer search,administer site configuration,administer software updates,administer taxonomy,administer themes,administer url aliases,administer users,administer views,block IP addresses,bypass node access,cancel account,change own username,create audio content,create basic_page content,create field_gallery_id,create field_object_id,create field_reference_num,create gallery content,create object content,create page content,create tour content,create url aliases,customize ckeditor,delete any audio content,delete any basic_page content,delete any gallery content,delete any object content,delete any page content,delete any tour content,delete backup files,delete own audio content,delete own basic_page content,delete own gallery content,delete own object content,delete own page content,delete own tour content,delete revisions,display drupal links,edit any audio content,edit any basic_page content,edit any gallery content,edit any object content,edit any page content,edit any tour content,edit field_gallery_id,edit field_object_id,edit field_reference_num,edit own audio content,edit own basic_page content,edit own field_gallery_id,edit own field_object_id,edit own field_reference_num,edit own gallery content,edit own object content,edit own page content,edit own tour content,flush caches,masquerade as admin,masquerade as any user,masquerade as user,notify of path changes,perform backup,restore from backup,revert revisions,search content,select account cancellation method,switch users,use advanced search,use ctools import,view field_gallery_id,view field_gallery_location,view field_in_gallery,view field_object_id,view field_reference_num,view own field_gallery_location,view own field_in_gallery,view own field_object_id,view own field_reference_num,view own unpublished content,view revisions,view the administration theme' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'authenticated user' 'use text format full_html' >> install.log 2>&1


./vendor/bin/drush --root=$DIR_ROOT role-remove-perm 'authenticated user' 'view own field_gallery_id,view field_gallery_id,use text format filtered_html,access content,access content overview' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-remove-perm 'anonymous user' 'use text format filtered_html,access content,access content overview' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-remove-perm 'administrator' 'execute php code,administer fieldgroups,create field_gallery_location,edit own field_gallery_location,edit field_gallery_location,create field_in_gallery,edit own field_in_gallery,edit field_in_gallery,create field_thumbnail_image,edit own field_thumbnail_image,edit field_thumbnail_image,view own field_thumbnail_image,view field_thumbnail_image,create field_large_image,edit own field_large_image,edit field_large_image,view own field_large_image,view field_large_image,use text format filtered_html' >> install.log 2>&1









# Set theme
echo "${G}Setting theme...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT vset theme_default appadmin >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT pm-disable --yes bartik >> install.log 2>&1


# Set up blocks
echo "${G}Setting up blocks...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush block-configure data_search --module=aicapp --region=content --yes >> install.log 2>&1
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'data_search'))->fields(array('pages' => \"node/add/object\nobjects/add\", 'title' => '<none>'))->execute();"
./vendor/bin/drush block-configure form --module=search --region=header --yes >> install.log 2>&1
./vendor/bin/drush block-configure --delta=masquerade --module=masquerade --region=sidebar_first --yes >> install.log 2>&1
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'masquerade','delta' => 'masquerade'))->fields(array('title' => '<none>'))->execute();"
./vendor/bin/drush block-disable navigation --module=system --yes >> install.log 2>&1
./vendor/bin/drush block-disable login --module=user --yes >> install.log 2>&1
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'updates_pending'))->fields(array('title' => '<none>', 'visibility' => 0))->execute();"
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'gallery_count'))->fields(array('title' => '<none>', 'pages' => 'galleries'))->execute();"
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'info_for_no-image_page'))->fields(array('pages' => 'objects/noimg'))->execute();"
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'data_buttons'))->fields(array('title' => '<none>', 'visibility' => 0))->execute();"
./vendor/bin/drush php-eval "db_merge('block')->key(array('theme' => 'AppAdmin','module' => 'aicapp','delta' => 'publish_butn'))->fields(array('title' => '<none>', 'visibility' => 0))->execute();"

./vendor/bin/drush --root=$DIR_ROOT cache-clear all >> install.log 2>&1


# Create test users
echo "${G}Creating test users...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT user-create TestEditor --mail="editor@test.com" --password="TestEditor" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT user-add-role "editor" TestEditor >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT user-create TestPublisher --mail="publisher@test.com" --password="TestPublisher" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT user-add-role "publisher" TestPublisher >> install.log 2>&1


# Set configurations
echo "${G}Setting configurations...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT vset site_name "Mobile App Admin tool" >> install.log 2>&1
php -r "print json_encode(array('2','3'));" | ./vendor/bin/drush --root=$DIR_ROOT vset --format=json masquerade_quick_switches - >> install.log 2>&1
php -r "print json_encode(array('3'));" | ./vendor/bin/drush --root=$DIR_ROOT vset --format=json masquerade_admin_roles - >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT vset node_admin_theme 0 >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT vset site_frontpage "content/welcome-mobile-app-admin-tool" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT vset site_403 "toboggan/denied" >> install.log 2>&1







# Set admin password
echo "${G}Setting admin password...${W}"
echo "${W}Drush takes over from here...${W}"

drpassword=$(date | md5 | base64 | head -c 12)
./vendor/bin/drush user-password admin --password="$drpassword" >> install.log 2>&1


# All done!
echo "${G}Mobile App CMS install complete!${W}"

echo "${G}Congrats! You've successfully installed the Mobile App CMS. Point the document root of${W}"
echo "${G}your web server to the mobile-app-cms folder and your admin site should be up and running.${W}"
echo
echo "${G}You can log in with the following credentials:${W}"
echo
echo "${G}   Username: admin${W}"
echo "${G}   Password: $drpassword${W}"
echo
echo "${G}   Username: TestEditor${W}"
echo "${G}   Password: TestEditor${W}"
echo
echo "${G}   Username: TestPublisher${W}"
echo "${G}   Password: TestPublisher${W}"
echo
echo "${G}You'll need to connect the CMS to your own Collections API in order for it work properly, in this${W}"
echo "${G}version. Go to admin/settings/aic-api of your site and enter the queries to use.${W}"
