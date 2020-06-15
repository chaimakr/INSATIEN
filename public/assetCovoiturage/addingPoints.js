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

/*
styles for map points
 */

var redStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 7,
        fill: new ol.style.Fill({color: 'rgba(255,0,0,0.4)'}),
        stroke: new ol.style.Stroke({
            color: [255, 0, 0], width: 2
        })
    })
});


var yellowStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 7,
        fill: new ol.style.Fill({color: 'rgba(255,255,0,0.4)'}),
        stroke: new ol.style.Stroke({
            color: [255, 0, 0], width: 2
        })
    })
});

var insatStyle = new ol.style.Style({
    image: new ol.style.Icon({
        src: '/assetCovoiturage/insat.png',
        size: [1000, 1000],
        scale: 0.2,
        anchor: [0.15, 0.2]

    }),
    zIndex:2
});


var pointsLayer = new ol.layer.VectorImage({
    source: new ol.source.Vector(),
    visibility: true,
    style: redStyle,
});



var insat = new ol.Feature({
    geometry: new ol.geom.Point(ol.proj.transform([10.195966872553878, 36.84317149268317], 'EPSG:4326', 'EPSG:3857')),
});


pointsLayer.getSource().addFeatures([insat]);

map.addLayer(pointsLayer);


countMarkers = 0;
initialPoints();

pointDiv = $('#pointDiv')[0];



map.on('click', (event) => {
    // console.log(event.coordinate);

    var marker = new ol.Feature({
        geometry: new ol.geom.Point(event.coordinate),
    });
    marker.id_ = countMarkers;

    pointsLayer.getSource().addFeatures([marker]);

    newPointDiv = "<div id=\"point" + countMarkers + "\" class=\"card text-white bg-dark mb-3\" style=\"width: 100%;\">" +

        "                <div class=\"card-body\">" +
        "                    <h5 class=\"card-title\"> Point :" + "</h5>" +
        "                    <small class=\"card-text\"> [" + marker.getGeometry().flatCoordinates + "]</small>" +
        "                    <button onclick=\"remove(" + countMarkers + ")\" class=\"btn btn-primary\">remove point</button>" +
        "                </div>" +
        "            </div>"

    pointDiv.appendChild(document.createRange().createContextualFragment(newPointDiv));

    countMarkers++;
});




selectedPoints = [];
selectedCards = [];


map.on('pointermove', (event) => {
    if (selectedPoints.length > 0 && selectedCards.length > 0) {




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

    insat.setStyle(redStyle);
    map.forEachFeatureAtPixel(event.pixel, (feature) => {
        if(feature==insat) {
            console.log('insat');
            insat.setStyle(insatStyle);
            return 0;
        }
        card = document.querySelector('#point' + feature.id_);
        selectedCards.push(card);
        card.classList.remove("bg-dark");
        card.classList.add("bg-info");
        selectedPoints.push(feature);
        feature.setStyle(yellowStyle);
        $('#pointDiv')[0].scrollTop = -160 + 160 * Array.prototype.indexOf.call($('#pointDiv')[0].children, $('#point' + feature.id_)[0]);
        return 1;
    })
});



/*
add coordinates of points to the hidden input  on form submit
 */
function jsonPoints() {
    let json = [];
    let point;
    pointsLayer.getSource().getFeatures().forEach((feature) => {
        // json+=('{"x":"'+point.getGeometry().flatCoordinates[0]+'",'+'{"y":"'+point.getGeometry().flatCoordinates[1]+'"');

        if(feature!=insat){
            point = new Object();
            point.x = feature.getGeometry().flatCoordinates[0];
            point.y = feature.getGeometry().flatCoordinates[1];
            json.push(point);
        }

    });


    $('#form_points')[0].value = JSON.stringify(json);

}




function checkType() {

    if ($('#form_type')[0].value === 'oneWay')
        $('#form_returnTime')[0].parentNode.style.display = 'none';
    else $('#form_returnTime')[0].parentNode.style.display = 'block';

}



function remove(id) {
    pointsLayer.getSource().removeFeature(searchMarker(id));
    $('#point' + id)[0].remove();

};



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




/*
utiliser si un covoiturage est modifié alors on charge ses points initiales
 */

function initialPoints() {

    points = JSON.parse($('#form_points')[0].value);
    points.forEach((point) => {
        var marker = new ol.Feature({
            geometry: new ol.geom.Point([point.x, point.y]),
        });
        marker.id_ = countMarkers;

        pointsLayer.getSource().addFeatures([marker]);

        newPointDiv = "<div id=\"point" + countMarkers + "\" class=\"card text-white bg-dark mb-3\" style=\"width: 100%;\">" +

            "                <div class=\"card-body\">" +
            "                    <h5 class=\"card-title\"> Point :" + "</h5>" +
            "                    <small class=\"card-text\"> [" + marker.getGeometry().flatCoordinates + "]</small>" +
            "                    <button onclick=\"remove(" + countMarkers + ")\" class=\"btn btn-primary\">remove point</button>" +
            "                </div>" +
            "            </div>"

        pointDiv.appendChild(document.createRange().createContextualFragment(newPointDiv));

        countMarkers++;
    });

}


