(function ($, Drupal) {
  /*global jQuery:false */
  /*global Drupal:false */
  "use strict";
  Drupal.behaviors.aicapp_object_select = {
    attach: function (context) {
      $(".form-autocomplete").on("autocompleteSelect", function (event, node) {
        $(":input[name=add-another]").trigger("mousedown");
     });
    }
  };
})(jQuery, Drupal);
