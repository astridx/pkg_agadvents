document.addEventListener('DOMContentLoaded', function () {
	"use strict";

	var catimage = document.getElementById('cordsmap').getAttribute('data-catimage');
	var latlngfield = document.getElementById('jform_cords');
	var lnglatfield = document.getElementById('jform_cordsimagemap');
	var oldcords = latlngfield.value;
	var newcords ="";
	var newcordsimagemap="";
	var editableLayers = new L.FeatureGroup();

	var newImg = new Image();
	newImg.src = catimage;
	var height = newImg.height;
    var width = newImg.width;
	//alert (height + " " + width);
	var center = [height/2, width/2];

	// Create the map
	var map = L.map('cordsmap', {
		crs: L.CRS.Simple,
		minZoom: -5
	});

	map.on('load', function (e) {
		var cords = oldcords.split(" ... ")[0].replaceAll(", ", ",").replaceAll("  ", " ").split(" ");
		var cordsArray = new Array();

		for (let i = 0; i < cords.length; i++) {
			if(cords[i] !== ""){
				cordsArray.push(cords[i].split(","));
				console.log(cords[i].split(","));
			}
		}
		
		var startpolygon = L.polygon(cordsArray, { color: 'red' }).addTo(editableLayers);

	});

	map.setView(center, 6);
	map.addLayer(editableLayers);

	var bounds = [[0, 0], [height,width]];
	var image = L.imageOverlay(catimage, bounds).addTo(map);
	map.fitBounds(bounds);

	// Initialise the FeatureGroup to store editable layers
	map.addLayer(editableLayers);


	var drawPluginOptions = {
		position: 'topleft',
		draw: {
			polyline: {
				shapeOptions: {
					color: '#f357a1',
					weight: 10
				}
			},
			polygon: {
				allowIntersection: false, // Restricts shapes to simple polygons
				drawError: {
					color: '#e1e100', // Color the shape will turn when intersects
					message: '<strong>Polygon draw does not allow intersections!<strong> (allowIntersection: false)' // Message that will show when intersect
				},
				shapeOptions: {
					color: '#bada55'
				}
			},
			circle: false, // Turns off this drawing tool
			rectangle: false,
			marker: false,
			circlemarker: false,
			polyline: false
		}
	};


	// Initialise the draw control and pass it the FeatureGroup of editable layers
	var drawControl = new L.Control.Draw(drawPluginOptions);
	map.addControl(drawControl);






	map.on('draw:created', function (e) {
		var layer = e.layer;

		editableLayers.eachLayer(function (layer) {
			editableLayers.removeLayer(layer);
			latlngfield.value = newcords;
			lnglatfield.value = newcordsimagemap;
			newcords = "";
			newcordsimagemap = "";
		});

		editableLayers.addLayer(layer);
	});


	map.on('click', function (e) {
		newcordsimagemap = newcordsimagemap + " " + parseInt(e.latlng.lng) + "," + (parseInt(height)-parseInt(e.latlng.lat)) + " ";
		newcords = newcords + " " + parseInt(e.latlng.lat) + "," + parseInt(e.latlng.lng) + " ";

	});
	
	
});
