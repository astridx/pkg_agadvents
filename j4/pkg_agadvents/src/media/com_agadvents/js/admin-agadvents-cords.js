document.addEventListener('DOMContentLoaded', function(){
	"use strict";
	setTimeout(function() {
		if (document.formvalidator) {
			document.formvalidator.setHandler('cords', function (value) {

				var returnedValue = false;

				var regex = /^([0-9,.]+)$/i;

				if (regex.test(value))
					returnedValue = true;

				return returnedValue;
			});
			//console.log(document.formvalidator);
		}
	}, (1000));
});
