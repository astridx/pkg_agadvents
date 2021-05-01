document.addEventListener('DOMContentLoaded', function () {
	"use strict";



	var center = [500, 500];

	// Create the map
	var map = L.map('cordsmap', {
		crs: L.CRS.Simple
	}).setView(center, 6);

	var bounds = [[0, 0], [1000, 1000]];
	var image = L.imageOverlay('https://leafletjs.com/examples/crs-simple/uqm_map_400px.png', bounds).addTo(map);
	map.fitBounds(bounds);

	// Initialise the FeatureGroup to store editable layers
	var editableLayers = new L.FeatureGroup();
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


	var editableLayers = new L.FeatureGroup();
	map.addLayer(editableLayers);




	map.on('draw:created', function (e) {
		var layer = e.layer;
		editableLayers.eachLayer(function (layer) {
			editableLayers.removeLayer(layer);
		});		

		editableLayers.addLayer(layer);
	});

});
