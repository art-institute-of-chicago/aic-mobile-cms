(function ($, Drupal) {
  /*global jQuery:false */
  /*global Drupal:false */
  "use strict";
  Drupal.aicapp_crop = Drupal.aicapp_crop || {
    uploadsPresent : 0,
    iasObjects : []
  };
  Drupal.behaviors.aicapp_crop = {
    attach: function (context) {
      var iasObjects = Drupal.aicapp_crop.iasObjects || [],
         aicSettings = Drupal.settings.aicapp_crop || false,
         aicImages = aicSettings.images || [],
         aicImageSmallWarning = '<p class="warning">' + Drupal.t("WARNING: THIS IMAGE IS SMALLER THAN THE RECOMMENDED MINIMUM AND WILL BE SCALED TO FIT IN THE APP.") + '</p>';

      // Update the crop selection objects if the context is a form.
      // This is usually after ajax has run after inital page load.
      if (iasObjects.length && context[0].tagName === 'FORM') {
          // Update each selection object.
        iasObjects.forEach(function(element, index) {
          // This will update each imgAreaSelect objects.
          element.update();
        });
      }
      // Attach once to the document body.
      $(document.body, context).once('document-body', function() {
        if (aicSettings && aicImages.length && context == '[object HTMLDocument]') {
          aicSettings.images.forEach(function(element, index) {
            var $cropInputs = $(element.crop_inputs_selector),
               cropDescription = '#' + element.wrapperID + '-crop-add-more-wrapper .fieldset-description',
               cropWrapper = '#' + element.wrapperID + '-crop-add-more-wrapper .fieldset-wrapper',
               imageType = element.type,
               imageHeight = element.height,
               imageWidth = element.width,
               previewClass = 'crop-preview-' + imageType,
               imagePreview = '<div class="' + previewClass + '" style="position:relative;"><img id="' + element.id + '"' + ' src="' + element.url + '" /></div>',
               // Options for the ImageAreaSelect plugin.
               imgAreaSelectOptions = {
                 x1: element.x1,
                 y1: element.y1,
                 x2: element.x2,
                 y2: element.y2,
                 parent : '.' + previewClass,
                 persistent: true,
                 handles: true,
                 onSelectEnd: function (img, selection) {
                   $('input[name="' + element.id + '_crop[und][0][field_x][und][0][value]"]').val(selection.x1);
                   $('input[name="' + element.id + '_crop[und][0][field_y][und][0][value]"]').val(selection.y1);
                   $('input[name="' + element.id + '_crop[und][0][field_x2][und][0][value]"]').val(selection.x2);
                   $('input[name="' + element.id + '_crop[und][0][field_y2][und][0][value]"]').val(selection.y2);
                   $('input[name="' + element.id + '_crop[und][0][field_width][und][0][value]"]').val(selection.width);
                   $('input[name="' + element.id + '_crop[und][0][field_height][und][0][value]"]').val(selection.height);
                 },
                 minWidth : element.minWidth,
                 minHeight : element.minHeight,
                 resizable : element.resizable ? true : false,
                 aspectRatio : element.aspectRatio
               },
               ias = false;

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
              if ($('.' + previewClass).length === 0) {
                // Append the preview image.
                $(cropDescription).append(imagePreview);
                // Initialize the crop tool.
                ias = $('img#' + element.id).imgAreaSelect({ instance: true });
                ias.setOptions(imgAreaSelectOptions);
                // Add the crop tool instance to a global variable.
                Drupal.aicapp_crop.iasObjects.push(ias);
              }
            }
          });
        }
      }); // End once()
    }
  };
})(jQuery, Drupal);
