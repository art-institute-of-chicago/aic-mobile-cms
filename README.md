![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Art Institute of Chicago Official Mobile App CMS
> A Drupal 7 site to administer content

The Art Insitute of Chicago [Official Mobile App](http://extras.artic.edu/new-mobile/) is your personal,
pocket-sized guide to our collection. The mobile experience merges location-aware technology with audio
storytelling, letting the art speak to you. The Art Institute offers nearly a million square feet to
explore—the Official Mobile App will be your guide.

The Art Institute of Chicago's mobile app launched in the iOS App Store on August 10, 2016. It's
still in place today and is being maintained by a team of internal developers.

Please note that while we took steps to generalize this project, it is not meant to be a plug-and-play 
product for your institution. You may need to substantially modify the CMS, beyond basic configuration. 


## Features

The Mobile App CMS has the following features:

* Create audio tours through the grouping of artworks
* Tag artworks with latitude/longitude data for placement on the mobile app's map
* Sort and track assets such as artworks, gallery locations, audio files, and images
* Search and populate collection-related data within the CMS via the museum collection's [Solr API](https://lucene.apache.org/solr/)


## Overview

The Mobile App consists of two parts: an iOS app written in Swift, and this repository--a content authoring
system using the Drupal framework. The frontend iOS app code can be found here:

https://github.com/art-institute-of-chicago/aic-mobile-ios

The app allows users to interact with the museum both on location and remotely through custom audio content
and indoor location tracking. This CMS is a Drupal 7 site with custom modules for authoring data to publish
to the app with integration with the Art Institute's collections database.

Content entered into the CMS is published to a JSON file. An example of this file is located [here](https://github.com/art-institute-of-chicago/aic-mobile-ios/blob/master/SampleData/appData.json). Content in this JSON file is then rendered by the frontend iOS app.


## Requirements

You can find information on Drupal 7's requirements [here](https://www.drupal.org/docs/7/system-requirements).
This package has the following requirements:

* [Composer](https://getcomposer.org/) - to install Drush to this project
* MySQL 5.7 or greater - to automatically create a database and user if it doesn't already exist

Our production environment is RedHat 6 and we've testing this code in OSX. All other environments, while they
should work fine, are untested. 


## Installing

This repo contains only the Drupal `sites` directory--the place where all the customizations and contributions
to a base Drupal install are kept. We've included an `install.sh` script that will download and
install the rest of Drupal for you, to make getting the CMS up and running easier.

Enter these commands from the command line to get started:

```shell
# Clone the repo to your computer
git clone https://github.com/art-institute-of-chicago/aic-mobile-cms.git

# Enter the folder that was created by the clone
cd aic-mobile-cms

# Install Drush to this project
composer install

# Run the install script
./install.sh
```

The script will download Drupal and extract its contents, then prompt you for database login info.
If the database and user specified doesn't already exist, the script will create them for you. It then
uses Drush to install Drupal, enable modules, set permissions and some configurations. At the end,
it will show you Drupal's admin password to log into your Drupal site. After the script completes, point the
document root of your web server to the `aic-mobile-cms` folder and your admin site should be up and
running.


### Integration with your Collections API

Creating content currently relies on functionality to retrieve artwork and gallery metadata from a
collections Solr API. You can go to `/admin/settings/aic-api` in your CMS to set the URLs for various
queries, but the parsing of the results is currently hardcoded. Take a look at the functions in
[`aicapp.module`](sites/all/modules/custom/aicapp/aicapp.module) to make changes to reflect your API.
Here are some examples of what your URLs might look like:

```
# Artwork query URL
http://api.yourmuseum.org/solr/select?rows={{rows}}&start={{start}}&fq=document_type:artwork-webcoll&q={{term}}:{{value}}&wt=json

# Gallery query URL
http://api.yourmuseum.org/solr/select?rows={{rows}}&start={{start}}&fq=document_type:gallery&q={{term}}:{{value}}&wt=json
```


### Integration with Google Maps

The CMS uses Google Maps to show where a stop on a tour will be. In order to see the maps when you're
creating content, you'll need to [get an API key](https://developers.google.com/maps/documentation/javascript/get-api-key)
from Google. Once you do, enter your key on the settings page: `/admin/settings/aic-api`.


### Publishing to the app

When you are ready to publish your data, all object information and references to resources such as images and audio files will be saved in an `appData.json` file. This file along with all the resources stored in the CMS will be published to the following locations:
 
* `sites/default/files/appData.json`
* `sites/default/files/audio/`
* `sites/default/files/object-images/`
* `sites/default/files/tour-images/`

You can configure your mobile app to look for this JSON file generated by the CMS. If you deploy the CMS to a server that the app can directly access, you may set the `appData` URL in the mobile app's `Common.swift` file to `http://aic-mobile-cms.your.museum/sites/default/files/appData.json`. In this setup, the "Submit for Publishing" button in the CMS will update changed content and publish it to this file path, and changes will be visible in the app immediately.

If you host the CMS on a server that the mobile app can't access directly, you can create a process to copy the needed app data to a public facing server. When deployed this way the "Submit for Publishing" button will save the `appData.json` file and all resources to the same locations. Then to publish data to the public facing server use the "Publish" button. This button is only visible to users with the Administrator or Publisher role. After clicking "Publish" a text file named `trigger/file_sync_trigger.txt` is created as an indicator to tell a sync process to run. You may use a cron process to check for the presence of this file to trigger your sync process to copy these files to a location that the mobile app can access. Remember to remove the trigger file on completion.

No sample content is included with this install. Currently, content creation is tightly coupled with our
collections Solr API. In future versions, we plan to break this coupling to make sharing this CMS more flexible.


## User Documentation

If you integrate the CMS with your own Solr API, please follow the [User Documentation](USER-DOCUMENTATION.md)
for further instructions on how to create and manage content within the CMS.


## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch.
We like to use [git-flow](https://github.com/nvie/gitflow) to make this process easier.

```bash
# Clone the repo to your computer
git clone https://github.com/your-github-account/aic-mobile-cms.git

# Enter the folder that was created by the clone
cd aic-mobile-cms

# Install Drush to this project
composer install

# Run the install script
./install.sh

# Start a feature branch
git flow start feature yourinitials-good-description-issuenumberifapplicable

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin yourinitials-good-description-issuenumberifapplicable
```

Then on github.com, create a Pull Request to merge your changes into our
`develop` branch.

This project is released with a Contributor Code of Conduct. By participating in
this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We also welcome bug reports and questions under GitHub's [Issues](issues).


## Acknowledgments

Supported by [Bloomberg Philanthropies](http://www.bloomberg.org/)

Design and development by [Potion](http://www.potiondesign.com/)

Additional development by [Josh Biillons](https://github.com/joshbillions/) at [MBLabs](http://www.mblabs.org/)

## Licensing

The code in this project is licensed under the [GNU Affero General Public
License Version 3](LICENSE).
