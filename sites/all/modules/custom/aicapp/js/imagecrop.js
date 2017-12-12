(function ($, Drupal) {
  /*global jQuery:false */
  /*global Drupal:false */
  "use strict";
  Drupal.aicapp_crop = Drupal.aicapp_crop || {};
  Drupal.aicapp_crop = {
    uploadPresent : false
  }
  Drupal.behaviors.aicapp_crop = {
    attach: function (context) {
      var domObjects = {
           '$form' : $('form.entityform'),
           '$field_image' : $('.field-image', context),
           '$field_thumb' : $('.field-thumb', context),
           '$field_large' : $('.field-large', context)
         },
         uploadPresent = Drupal.aicapp_crop.uploadPresent,
         aicSettings = Drupal.settings.aicapp_crop || false,
         aicImages = aicSettings.images || [],
         aicImageSmallWarning = '<p class="warning">' + Drupal.t("WARNING: THIS IMAGE IS SMALLER THAN THE RECOMMENDED MINIMUM AND WILL BE SCALED TO FIT IN THE APP.") + '</p>';

      if (aicSettings && aicImages.length) {
        aicSettings.images.forEach(function(element, index) {
          var $cropInputs = $(element.crop_inputs_selector),
             cropDescription = '#' + element.wrapperID + '-crop-rect-add-more-wrapper .fieldset-description',
             cropWrapper = '#' + element.wrapperID + '-crop-rect-add-more-wrapper .fieldset-wrapper',
             imageType = element.type,
             imageHeight = element.height,
             imageWidth = element.width,
             imagePreview = '<p>&nbsp;</p><p><img id="' + element.id + '"' + ' src="' + element.url + '" /></p>',
             imgAreaSelectOptions = {
               x1: element.x1,
               y1: element.y1,
               x2: element.x2,
               y2: element.y2,
               persistent: true,
               handles: true,
               onSelectEnd: function (img, selection) {
                 $('input[name="' + element.id + '_crop_rect[und][0][field_x][und][0][value]"]').val(selection.x1);
                 $('input[name="' + element.id + '_crop_rect[und][0][field_y][und][0][value]"]').val(selection.y1);
                 $('input[name="' + element.id + '_crop_rect[und][0][field_x2][und][0][value]"]').val(selection.x2);
                 $('input[name="' + element.id + '_crop_rect[und][0][field_y2][und][0][value]"]').val(selection.y2);
                 $('input[name="' + element.id + '_crop_rect[und][0][field_width][und][0][value]"]').val(selection.width);
                 $('input[name="' + element.id + '_crop_rect[und][0][field_height][und][0][value]"]').val(selection.height);
               },
               minWidth : element.minWidth,
               minHeight : element.minHeight,
               resizable : element.resizable ? true : false,
               aspectRatio : element.aspectRatio
             };

          if ((imageType == "large" && (imageWidth < imgAreaSelectOptions.minWidth || imageHeight < imgAreaSelectOptions.minHeight))
          || (imageType == "thumbnail" && imageWidth < imgAreaSelectOptions.minWidth)) {
            if ($(cropWrapper).length) {
              $(cropWrapper).find('p.warning').remove();
              $(cropWrapper).append(aicImageSmallWarning);
            }
          }
          // make fields readonly
          $cropInputs.prop('readonly', 'readonly');

          if ($(cropDescription).length) {
            $(cropDescription).append(imagePreview);
          }
          // Initialize the crop tool.
          $('img#' + element.id).imgAreaSelect(imgAreaSelectOptions);
          //var ias = $('img#' + element.id).imgAreaSelect({ instance: true });
        });
      }
      // Attach once to document
      // $(document.body).once('document-body', function() {
      //   // Listen on the following events/context
      //   $(this)
      //   .on('EVENT', '.item', function(e) {
      //
      //   })
      //
      // });
    }
  };
})(jQuery, Drupal);
