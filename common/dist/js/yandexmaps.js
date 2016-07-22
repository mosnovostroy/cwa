ymaps.ready(init_yandex_maps);

function init_yandex_maps () {
    var myMap = new ymaps.Map('yandexmap', {
            center: [55.76, 37.64],
            zoom: 10
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
    myMap.geoObjects.add(objectManager);

    ymaps.behavior.storage.add('mybehavior', MyBehavior);
    myMap.behaviors.enable('mybehavior');

    var dataroute = "r=coords%2Fview";
    var centerid = yandexmap.getAttribute('centerid');
    if(centerid) {
        dataroute = dataroute + "&id=" + centerid;
    }
    $.ajax({
        url: "http://localhost/cwa/frontend/web/index.php",
        type: "GET",
        data: dataroute
        //data: "r=coords%2Fview&id=" + yandexmap.getAttribute('centerid')
    }).done(function(data) {
        objectManager.add(data);
    });

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
