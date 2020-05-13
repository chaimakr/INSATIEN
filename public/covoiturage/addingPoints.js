var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        center: ol.proj.transform([10.195966872553878, 36.84317149268317], 'EPSG:4326', 'EPSG:3857'),
        zoom: 12,
        // extent: ol.proj.transform([7,30], 'EPSG:4326','EPSG:3857').concat(ol.proj.transform([13,37.5], 'EPSG:4326','EPSG:3857'))
    })

});


redStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 7,
        fill: new ol.style.Fill({color: 'rgba(255,0,0,0.4)'}),
        stroke: new ol.style.Stroke({
            color: [255, 0, 0], width: 2
        })
    })
});


yellowStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 7,
        fill: new ol.style.Fill({color: 'rgba(255,255,0,0.4)'}),
        stroke: new ol.style.Stroke({
            color: [255, 0, 0], width: 2
        })
    })
});


var pointsLayer = new ol.layer.VectorImage({
    source: new ol.source.Vector(),
    visibility: true,
    style: redStyle,
});


map.addLayer(pointsLayer);


countMarkers = 0;


pointDiv = document.querySelector('#pointDiv');


map.on('click', (event) => {
    // console.log(event.coordinate);

    var marker = new ol.Feature({
        geometry: new ol.geom.Point(event.coordinate),
    });
    marker.id_ = countMarkers;
    console.log(marker);
    pointsLayer.getSource().addFeatures([marker]);

    newPointDiv = "<div id=\"point" + countMarkers + "\" class=\"card text-white bg-dark mb-3\" style=\"width: 18rem;\">" +

        "                <div class=\"card-body\">" +
        "                    <h5 class=\"card-title\"> Point" + countMarkers + "</h5>" +
        "                    <p class=\"card-text\"> text content.</p>" +
        "                    <button onclick=\"remove(" + countMarkers + ")\" class=\"btn btn-primary\">remove point</button>" +
        "                </div>" +
        "            </div>"

    pointDiv.appendChild(document.createRange().createContextualFragment(newPointDiv));

    countMarkers++;
});



function searchMarker(id) {
    let point;

    pointsLayer.getSource().getFeatures().forEach((feature) => {
        if (id == feature.id_) {
            point = feature;
            return true;
        }
    });
    return point;

};



function remove(id) {
    pointsLayer.getSource().removeFeature(searchMarker(id));
    document.querySelector('#point' + id).remove();

};

selectedPoints = [];
selectedCards = [];

map.on('pointermove', (event) => {
    if (selectedPoints.length > 0 && selectedCards.length>0) {
        selectedPoints.forEach((point) => {
            point.setStyle(redStyle);

            selectedPoints.splice(selectedPoints.indexOf(point), 1);

        });
        selectedCards.forEach((card) => {
            card.classList.add("bg-dark");
            card.classList.remove("bg-info");

            selectedCards.splice(selectedCards.indexOf(card), 1);

        })


    }
    map.forEachFeatureAtPixel(event.pixel, (feature) => {
        card = document.querySelector('#point' + feature.id_);
        selectedCards.push(card);
        card.classList.remove("bg-dark");
        card.classList.add("bg-info");
        selectedPoints.push(feature);
        feature.setStyle(yellowStyle);
        return 1;
    })
});
