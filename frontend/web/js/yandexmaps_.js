ymaps.ready(init_yandex_maps);

function init_yandex_maps () {
    var ymaps_scale = yandexmap.getAttribute('ymaps_scale');
    if(!ymaps_scale) ymaps_scale = 10;
    var ymaps_lat = yandexmap.getAttribute('ymaps_lat');
    if(!ymaps_lat) ymaps_lat = 55.76;
    var ymaps_lng = yandexmap.getAttribute('ymaps_lng');
    if(!ymaps_lng) ymaps_lng = 37.64;

    alert(window.location);
    var myMap = new ymaps.Map('yandexmap', {
            center: [ymaps_lat, ymaps_lng],
            zoom: ymaps_scale
        }, {
            searchControlProvider: 'yandex#search'
        }),
        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32
        });

    objectManager.objects.options.set('preset', 'islands#greenDotIcon');
    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    //myMap.geoObjects.add(objectManager);
    ymaps.behavior.storage.add('mybehavior', MyBehavior);
    myMap.behaviors.enable('mybehavior');

    //var dataroute = "r=coords%2Fview";
    //var dataroute = "r=center%2Fcoords";
    var dataroute = "";
    var centerid = yandexmap.getAttribute('centerid');
    if(centerid) {
        dataroute = dataroute + "&CenterSearch[id]=" + centerid;
    }
    var region_id = yandexmap.getAttribute('region_id');
    if(region_id) {
        dataroute = dataroute + "&CenterSearch[region]=" + region_id;
    }
    $.ajax({
		    //url: "index.php",
        url: "/center/coords/",
        type: "GET",
        data: dataroute
        //data: "r=coords%2Fview&id=" + yandexmap.getAttribute('centerid')
    }).done(function(data) {
        objectManager.add(data);
    });

    myMap.geoObjects.add(objectManager);

}

function MyBehavior() {
    this.options = new ymaps.option.Manager(); // Менеджер опций
    this.events = new ymaps.event.Manager(); // Менеджер событий
}

MyBehavior.prototype = {
    constructor: MyBehavior,
    enable: function () {
        this._parent.getMap().events.add('click', this._onClick, this);
    },
    disable: function () {
        this._parent.getMap().events.remove('click', this._onClick, this);
    },
    setParent: function (parent) { this._parent = parent; },
    getParent: function () { return this._parent; },
    _onClick: function (e) {
        var coords = e.get('coords');
        document.getElementById("center-gmap_lat").value = coords[0];
        document.getElementById("center-gmap_lng").value = coords[1];
    }
};
