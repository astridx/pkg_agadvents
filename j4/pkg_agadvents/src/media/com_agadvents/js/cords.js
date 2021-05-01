document.addEventListener('DOMContentLoaded', function () {
	"use strict";

	var catimage = document.getElementById('cordsmap').getAttribute('data-catimage');
	var latlngfield = document.getElementById('jform_cords');
	var oldcords = latlngfield.value;
	var newcords = latlngfield.value;
	var editableLayers = new L.FeatureGroup();
	var editableLayers = new L.FeatureGroup();

	var center = [500, 500];

	// Create the map
	var map = L.map('cordsmap', {
		crs: L.CRS.Simple,
		minZoom: -5
	});

	map.on('load', function (e) {
		//var polygon = L.polygon(cords, {color: 'red'}).addTo(editableLayers);
		var cords = oldcords.split(" ");
		var cordsArray = new Array();
		for (let i = 0; i < cords.length; i++) {
			cordsArray.push(cords[i].split(","));
		}
		
		var startpolygon = L.polygon(cordsArray, { color: 'red' }).addTo(editableLayers);

	});

	map.setView(center, 6);
	map.addLayer(editableLayers);

	var bounds = [[0, 0], [1000, 1000]];
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
		});

		editableLayers.addLayer(layer);
	});


	map.on('click', function (e) {
		//alert(e.latlng.toString().replace(")", " ").replace("LatLng(", " "));
	});

});
