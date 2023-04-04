jQuery(document).ready(function($) {
	var attribution = new ol.control.Attribution({
		collapsible: false
	});
	$('.map').each(function() {
		
		var block_id=$(this).data('target');
		var lat=$(this).data('lat');
		var lng=$(this).data('lng');
		var address=$(this).data('address');
		var marker=$(this).data('marker');
		var block_zoom=$(this).data('zoom');
		
		var map = new ol.Map({
			controls: ol.control.defaults({attribution: false}).extend([attribution]),
			layers: [
				new ol.layer.Tile({
					source: new ol.source.OSM({
						url: 'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
						attributions: [ ol.source.OSM.ATTRIBUTION ],
						maxZoom: 19
					})
				})
			],
			target: 'map_'+block_id,
			view: new ol.View({
				center: ol.proj.fromLonLat([lng, lat]),
				maxZoom: 19,
				zoom: block_zoom
			})
		});
		
		 var container = document.getElementById('popup_'+block_id);
 var content = document.getElementById('popup-content_'+block_id);
 var closer = document.getElementById('popup-closer_'+block_id);
 
 var overlay = new ol.Overlay({
     element: container,
     autoPan: true,
     autoPanAnimation: {
         duration: 250
     }
 });
 map.addOverlay(overlay);
 
	content.innerHTML = address;
	//overlay.setPosition(ol.proj.fromLonLat([lng, lat]));
 
		var iconFeature = new ol.Feature({
			geometry: new ol.geom.Point(ol.proj.fromLonLat([lng, lat])),
		});

		var iconStyle = new ol.style.Style({
			image: new ol.style.Icon({
				anchor: [12, 24],
				anchorXUnits: 'pixels',
				anchorYUnits: 'pixels',
				src: marker
			})
		});

		iconFeature.setStyle(iconStyle);
 
 
		var layer = new ol.layer.Vector({
			source: new ol.source.Vector({
				features: [iconFeature]
			})
		});
 
 
		map.addLayer(layer);
 

 

map.on('singleclick', function (event) {
     if (map.hasFeatureAtPixel(event.pixel) === true) {
         var coordinate = event.coordinate;
		 
         content.innerHTML = address.replace(/br/g, "<br>")
         overlay.setPosition(coordinate);
     } else {
         overlay.setPosition(undefined);
         closer.blur();
     }
 });
 
 
 closer.onclick = function() {
     overlay.setPosition(undefined);
     closer.blur();
     return false;
 };


		
		
		
		
		
		
		
		
		
		
		
		
		
	});

});