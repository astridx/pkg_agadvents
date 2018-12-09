//Adminaerea for cropping

jQuery(function ($) {
	var cords = $("#jform_cords_hidden").val().split(",").map(Number);

	$('#target').Jcrop({
		//aspectRatio: 1,
		minSize: 50,
		onSelect: applyCoords,
		onChange: applyCoords,
		bgColor: 'gray',
		setSelect:    cords ,

	});
});
function applyCoords(c) {
	jQuery('#jform_cords').val(c.x + ',' + c.y + ',' + c.x2 + ',' + c.y2);
	jQuery('#jform_cords_hidden').val(c.x + ',' + c.y + ',' + c.w + ',' + c.h);
}
