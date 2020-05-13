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
var points=null;


greenStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 7,
        fill: new ol.style.Fill({color: 'rgba(0,255,0,0.4)'}),
        stroke: new ol.style.Stroke({
            color: [255, 0, 0], width: 2
        })
    })
});

var pinStyle = new ol.style.Style({
    image: new ol.style.Icon({
        src: '/assetCovoiturage/pin.png',
        size: [1000, 1000],
        scale: 0.2,
        anchor: [0.3, 0.3]

    })
});


var pointsLayer = new ol.layer.VectorImage({
    source: new ol.source.Vector(),
    visibility: true,
    style:greenStyle
});

map.addLayer(pointsLayer);

$.ajax({
    url:'/covoiturage/getPoints',
    success:function (data) {
        points=JSON.parse(data);
        addingPoints();

    }
});

function addingPoints(){
    if (!points) setTimeout(addingPoints,200);
    else {
        points.forEach((point)=>{

            var marker = new ol.Feature({
                geometry: new ol.geom.Point([point.x,point.y]),
            });
            marker.set('covoiturageId',point.covoiturageId);

            pointsLayer.getSource().addFeatures([marker]);
        })

    }
}
var selectedPoints=[];





    map.on('pointermove', (event) => {
       // console.log(map.getFeaturesAtPixel(event.pixel));
        if (selectedPoints.length > 0)
            selectedPoints.forEach((point) => {
                point.setStyle(greenStyle);

                selectedPoints.splice(selectedPoints.indexOf(point), 1);

            });
        map.forEachFeatureAtPixel(event.pixel, (feature) => {

            ensemblePoint=sameCovoiturageId(feature.values_.covoiturageId);
            ensemblePoint.forEach((point)=>{


                selectedPoints.push(point);
                point.setStyle(pinStyle);

            });


            return 1;
        })
    });



let owner=null;


map.on('click', (event) => {
    map.forEachFeatureAtPixel(event.pixel, (feature) => {
        $.ajax({
            url:'/covoiturage/getOwner?covoiturageId='+feature.values_.covoiturageId,
            success:function (data) {
                owner=JSON.parse(data);
            }
        });
        addCard(feature.values_covoiturageId);
        return 1;
    });



});
function addCard(covoiturageId){

    if(owner) {
        $('#card')[0].innerHTML="<div class=\"card text-white bg-dark mb-3\" style=\"max-width: 100%;\">" +
            "  <div class=\"card-header\">"+ "Offre de "+ owner.firstName+" "+ owner.lastName+"</div>" +
            "  <div class=\"card-body\">" +
            "    <h5 class=\"card-title\">"+ "email : "+owner.email +"</h5>" +
            "    <p class=\"card-text\">"+"details : "+owner.moreDetails +"</p>" +
            "  </div>" +
            "</div>";
        owner=null;

    }else setTimeout(addCard,300,covoiturageId);


}

function sameCovoiturageId(covoiturageId){
    points=[];
    map.getLayers().array_[1].getSource().getFeatures().forEach((point)=>{


        if(point.values_.covoiturageId==covoiturageId){
            points.push(point);

        }

    });
    return points;

}

