// JavaScript Document
(function ($) {
	$(document).ready(function () {
		//display thumbnail and large images
		$( "div#'.$formID.'-crop-rect-add-more-wrapper .fieldset-description" ).append( "<p>&nbsp;</p><p><img id=\"'.$formValue.'\" src=\"'.$thumbUrl.'\" /></p>" );
		//make fields readonly
		$("#field-thumbnail-crop-rect-add-more-wrapper input").prop("readonly", "readonly");
		$("#field-large-image-crop-rect-add-more-wrapper input").prop("readonly", "readonly");
		
		var imageType = "'.$imageType.'";
		var imageWidth = "'.$thumbValues['width'].'";
		var imageHeight = "'.$thumbValues['height'].'";
		if (imageType == "large") {
			var imageWidthMin = 364;
			var imageHeightMin = 200;
		} else {
		var imageWidthMin = "'.$thumbValues['width'].'";
		var imageHeightMin = "'.$thumbValues['height'].'";
		}
		//console.log("imagex2", '.$thumbValues['x2'].');
		//console.log("imagey2", '.$thumbValues['y2'].');
		if ((imageType == "large" && (imageWidth < 364 || imageHeight < 200)) || (imageType == "thumbnail" && imageWidth < 50)) {
			$( "div#'.$formID.'-crop-rect-add-more-wrapper .fieldset-wrapper" ).append( "<p class=\"warning\">WARNING: THIS IMAGE IS SMALLER THAN THE RECOMMENDED MINIMUM AND WILL BE SCALED TO FIT IN THE APP.</p>" );
		}
		$("img#'.$formValue.'").imgAreaSelect({
			x1: '.$thumbValues['x1'].', y1: '.$thumbValues['y1'].', x2: '.$thumbValues['x2'].', y2: '.$thumbValues['y2'].',
			aspectRatio: "'.$aspectRatio.'",
			persistent: true,
			handles: true,
			minWidth: imageWidthMin,
			minHeight: imageHeightMin,
			resizable: '.$resizable.',
			onSelectEnd: function (img, selection) {
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_x][und][0][value]\"]").val(selection.x1);
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_y][und][0][value]\"]").val(selection.y1);
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_x2][und][0][value]\"]").val(selection.x2);
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_y2][und][0][value]\"]").val(selection.y2);
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_width][und][0][value]\"]").val(selection.width);
				$("input[name=\"'.$formValue.'_crop_rect[und][0][field_height][und][0][value]\"]").val(selection.height);             
				}
			});
	});
})(jQuery);',
	array(
	'type' => 'inline',
	'scope' => 'footer',
	'group' => JS_THEME,
	'weight' => 15,
	)
);