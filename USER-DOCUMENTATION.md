![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Mobile App CMS User Documentation

For a broad overview of how to use Drupal 7, please see their documentation on
[Adminstering Drupal 7 sites](https://www.drupal.org/docs/7/administering-drupal-7-site).
This documentation assumes you have a basic understanding of how to administer
content in Drupal 7, and this might be helpful for futher context.

This documentation covers utilizing the various features of the Mobile App CMS and requirements for publishing data for consumption by the mobile application.


## Adding Audio Files

1. In the Audio section of the CMS select "Add new audio file" at the top of the page.
2. Fill in the "Title" for the new audio file.
3. Choose a file to upload. Audio files must be formatted as MP3 for consumption by the mobile application.
4. (optional) Add an audio transcript for the file. This will be displayed in the application under any object that utilizes the audio file.
5. (optional) Add any credits that should accompany the audio file. This will be displayed on the object page underneath the audio transcript if one is included. If there is no audio transcript the credits will appear in place of the transcript in the object view.

Notes: Audio files must be "published" to be used in the mobile application. By default audio files are set to published on creation.


## Adding Galleries

Gallery locations are managed by the Art Institute of Chicago's collections database and are imported into the CMS automatically via our Solr API. Click on the "Refresh Gallery List" button, and all public galleries will automatically be loaded into the CMS.

This process is dependant on our data schema. If you've connected your own Solr API to the CMS via `/admin/settings/aic-api`, you'll need to update [`aicapp.module`](sites/all/modules/custom/aicapp/aicapp.module) to reflect your data schema.


## Adding Objects

Objects are managed by the Art Institute of Chicago's collections database and are imported into the CMS via our Solr API. Objects can be added to the CMS by clicking "Find new Object to add" at the top of the Objects page. You can search for an object by Title, Reference Number, or Object ID, then select it to add to the CMS.

Selecting an object will create a new object record in the CMS and auto-populated with the available data from Solr. For the object to display in the app, you must add an Object Selector Number, which is used by the mobile app's number pad.

In addition you may add multiple audio files to an object. Each object must have at least one associated with it in order for the object to display in the mobile app. The first audio file added to an object record will be used as the default audio file, which is played when looking up objects via object selector number in the app.

You may then use the interactive map to drag and drop the red location pin at the geographic location of the object. This information is used by the mobile app to display objects on a map.

If your object is missing an image you may supply a custom image. All objects must have an image in order to be displayed in the app. If your image is imported as a part of the Solr record you may select custom crops for display in the app via the thumbnail and large image crop tools. Custom crops for custom uploaded images are not supported at this time.


## Adding Tours

You may use the tours section of the CMS to create groups of objects as collections for audio tours.

Select the "Create new tour" option in the Tours section and add a title. Next, you must upload an image that will be used as a cover image for your tour. The "Description" should be a short one sentence line as it displays persistently in the tours section. The "Intro" may be a longer paragraph-length description of your tour. The app user can expand the overview and see the "Intro" alongside the "Description". 

Duration is an optional field that is used to display the length of the tour in minutes.

Tour Audio is the first "stop" on the tour and is in place to act as an audio overview and introduction for every tour.

You may add multiple artworks/objects to the Tour Stops section. Each object must be accompanied by an audio file. This section will allow you to dynamically search through objects and audio files that have been uploaded to the CMS.

At the bottom of the tour page you may assign a "Tour Banner". Tour banners are flags that display in the app tours section and may be used to highlight new or featured tours.

[INCLUDE IMAGE EXAMPLE OF A BANNER]


## Publishing changes to the app

You must first queue changes for publishing by selecting the "Submit for Publishing" button at the top left side of the CMS page. After the changes have been submitted for publishing select the "Publish" button to trigger the publication of new data to the app.

Notes: The Art Institute of Chicago maintains CMSs privately and as such the mobile app may not directly communicate with the CMS. In our installation the publish button triggers a sync process between our CMS server and a public facing server which hosts the `appData.json` file, audio files, and any custom images for consumption by the mobile app. 
