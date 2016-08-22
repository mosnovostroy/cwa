ymaps.ready(init_yandex_maps);

function init_yandex_maps () {
    var ymaps_scale = yandexmap.getAttribute('ymaps_scale');
    if(!ymaps_scale) ymaps_scale = 10;
    var ymaps_lat = yandexmap.getAttribute('ymaps_lat');
    if(!ymaps_lat) ymaps_lat = 55.76;
    var ymaps_lng = yandexmap.getAttribute('ymaps_lng');
    if(!ymaps_lng) ymaps_lng = 37.64;

    //alert(window.location.toString().replace('/map/','/coords/'));
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
		
	if (yandexmap.className == 'inline-yandexmap')
	{
		myMap.behaviors.disable('scrollZoom');		
	}

	var myButton = new ymaps.control.Button({
         data: {
             // Зададим иконку для кнопки
             //image: 'images/button.jpg',
             // Текст на кнопке.
             content: 'Фильтр',
             // Текст всплывающей подсказки.
             title: 'Показать фильтр'
         }
    }, {
        // Зададим опции для кнопки.
        selectOnClick: false
    });	
	myMap.controls.add(myButton, {
		float: "right"
		//,		floatIndex: 500
	});
	myButton.events.add('press', function () { document.getElementById('mainform-large').classList.remove('hidden'); })
	
    objectManager.objects.options.set('preset', 'islands#greenDotIcon');
    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    //myMap.geoObjects.add(objectManager);
    ymaps.behavior.storage.add('mybehavior', MyBehavior);
    myMap.behaviors.enable('mybehavior');

    var datapath = "";
    var dataparams = "";
    var centerid = yandexmap.getAttribute('centerid');
	var arendaid = yandexmap.getAttribute('arendaid');
    if(centerid) {
        datapath = "/centers/coordinates/"
        dataparams = "&CenterSearch[id]=" + centerid;
    }
    else if(arendaid) {
        datapath = "/arenda/coordinates/"
        dataparams = "&ArendaSearch[id]=" + arendaid;
    }
    else {
        datapath = window.location.toString().replace('/map/','/coordinates/');
        dataparams = "";
    }
    $.ajax({
        url: datapath,
        type: "GET",
        data: dataparams
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
		//this._parent.getMap().events.add('contextmenu', this._onContextMenu, this);
    },
    disable: function () {
        this._parent.getMap().events.remove('click', this._onClick, this);
		//this._parent.getMap().events.remove('contextmenu', this._onContextMenu, this);
    },
    setParent: function (parent) { this._parent = parent; },
    getParent: function () { return this._parent; },
    _onClick: function (e) {
        var coords = e.get('coords');
 		if (document.getElementById("center-gmap_lat")) document.getElementById("center-gmap_lat").value = coords[0];
        if (document.getElementById("center-gmap_lng")) document.getElementById("center-gmap_lng").value = coords[1];
		if (document.getElementById("arenda-gmap_lat")) document.getElementById("arenda-gmap_lat").value = coords[0];
        if (document.getElementById("arenda-gmap_lng")) document.getElementById("arenda-gmap_lng").value = coords[1];
    }
	//,    _onContextMenu: function (e) {
    //    var coords = e.get('coords');
	//	alert('Центр карты: ' + coords[0] + ', ' + coords[1]);
	//}
};
