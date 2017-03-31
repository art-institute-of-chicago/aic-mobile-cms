#/bin/bash

# Color markup
R=`tput setaf 1`
G=`tput setaf 2`
Y=`tput setaf 3`
C=`tput setaf 6`
W=`tput sgr0`

echo "${C}Mobile App CMS uninstaller${W}"

echo "${W}This script will remove all the files created by the install.sh script. ${W}"
echo "${W}Some commands run as sudo, so you will be asked to enter your password.${W}"
echo

echo "${G}Removing Drupal files...${W}"
sudo rm -rf .editorconfig \
     CHANGELOG.txt \
     COPYRIGHT.txt \
     INSTALL.mysql.txt \
     INSTALL.pgsql.txt \
     INSTALL.sqlite.txt \
     INSTALL.txt \
     LICENSE.txt \
     MAINTAINERS.txt \
     README.txt \
     UPGRADE.txt \
     authorize.php \
     cron.php \
     includes \
     index.php \
     install.php \
     misc \
     modules \
     profiles \
     robots.txt \
     scripts \
     themes \
     update.php \
     web.config \
     xmlrpc.php \
     sites/default/settings.php \
     sites/default/files \
     install.log

echo "${G}Changing permissions on sites/default folder...${W}"
chmod u+w sites/default

echo "${G}Done${W}"
echo "${G}Please note that the database and user that was created in the install.sh script has not been deleted.${W}"

