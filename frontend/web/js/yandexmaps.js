var myMap;
var objectManager;
var currentObject;
var myGeocoder;

ymaps.ready(init_yandex_maps);

function locate_yandex_maps (region_id)
{
    $.ajax({
        url: "/site/map-params/",
        type: "GET",
        data: "&id=" + region_id
    }).done(function(data) {
        data = JSON.parse(data);
        myMap.setCenter([data.lat, data.lng], data.zoom);
    });

    init_objects();
}

function init_objects ()
{
    //objectManager.removeAll();

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

}

function get_metro (coords)
{
    myGeocoder = ymaps.geocode(coords, {kind: 'metro', results: '1'});
    myGeocoder.then(
    function (res) {
            metr, mcoords, str = '';
            metr = res.geoObjects.get(0);
            str += metr.properties.get('name');
            //mcoords = metr.geometry.getCoordinates();
            // str += '<li>' + metr.properties.get('name') + ' - '
            //     +
            //     ymaps.formatter.distance(ymaps.coordSystem.geo.getDistance(mcoords, coords))
            //     + '</li>';
            return str;
        },
        function (err) {
    }
    );
}

function init_closest_metro (coords)
{
    myGeocoder = ymaps.geocode(coords, {kind: 'metro', results: '3'});
    myGeocoder.then(
    function (res) {
            var closest_metro_div = document.getElementById('closest_metro');
            if (closest_metro_div)
            {
                var i, metr, mcoords, str = '<ul>';
                for (i=0; i<3; i++)
                {
                    metr = res.geoObjects.get(i);
                    mcoords = metr.geometry.getCoordinates();
                    str += '<li><span class="metro-icon">' + metr.properties.get('name').replace("метро ", "") + ' - '
                        +
                        ymaps.formatter.distance(ymaps.coordSystem.geo.getDistance(mcoords, coords))
                        + '</span></li>';
                    //console.log(metr.properties.get('metaDataProperty.GeocoderMetaData.formerName'));
                    //console.log(metr.properties.get('description'));
                    //console.log(metr.geometry.getCoordinates());
                }
                str += '</ul>'
                closest_metro_div.innerHTML = str;
                //console.log(str);
            }
        },
        function (err) {
            // обработка ошибки
    }
    );
}

function init_yandex_maps ()
{
    var ymaps_scale = yandexmap.getAttribute('ymaps_scale');
    if(!ymaps_scale) ymaps_scale = 8;
    var ymaps_lat = yandexmap.getAttribute('ymaps_lat');
    if(!ymaps_lat) ymaps_lat = 55.75396;
    var ymaps_lng = yandexmap.getAttribute('ymaps_lng');
    if(!ymaps_lng) ymaps_lng = 37.620393;
    var ymaps_hide_filter_button = yandexmap.getAttribute('ymaps_hide_filter_button');
    if(!ymaps_hide_filter_button) ymaps_hide_filter_button = 0;

    init_closest_metro ([ymaps_lat, ymaps_lng]);

    //alert(window.location.toString().replace('/map/','/coords/'));
    myMap = new ymaps.Map('yandexmap', {
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

    if (!ymaps_hide_filter_button)
    {
        var ButtonLayout = ymaps.templateLayoutFactory.createClass([
                '<div alt="{{ data.title }}" class="bg-primary my-button ',
                '{% if state.size == "small" %}my-button_small{% endif %}',
                '{% if state.size == "medium" %}my-button_medium{% endif %}',
                '{% if state.size == "large" %}my-button_large{% endif %}',
                '{% if state.selected %} my-button-selected{% endif %}">',
                // '<img class="my-button__img" src="{{ data.image }}" alt="{{ data.title }}">',
                '<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>',
                '<span class="my-button__text">{{ data.content }}</span>',
                '</div>'
            ].join(''));

      	var myButton = new ymaps.control.Button({
               data:
               {
                   content: 'Фильтр',
                   title: 'Показать фильтр'
               },
               options:
               {
                    maxWidth: [170, 190, 220],
                    layout: ButtonLayout,
                    selectOnClick: false
               }
          });
      	myMap.controls.add(myButton, {
      		float: "right"
      		//,		floatIndex: 500
      	});
      	myButton.events.add('press', function () { document.getElementById('mainform-large').classList.remove('hidden'); })
    }

    objectManager.objects.options.set('preset', 'islands#greenDotIcon');
    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    //myMap.geoObjects.add(objectManager);
    ymaps.behavior.storage.add('mybehavior', MyBehavior);
    myMap.behaviors.enable('mybehavior');
    myMap.geoObjects.add(objectManager);

    init_objects();
}

function MyBehavior() {
    this.options = new ymaps.option.Manager(); // Менеджер опций
    this.events = new ymaps.event.Manager(); // Менеджер событий
}

MyBehavior.prototype =
{
    constructor: MyBehavior,

    enable: function ()
    {
        this._parent.getMap().events.add('click', this._onClick, this);
    },

    disable: function ()
    {
        this._parent.getMap().events.remove('click', this._onClick, this);
    },

    setParent: function (parent)
    {
        this._parent = parent;
    },

    getParent: function ()
    {
        return this._parent;
    },

    _onClick: function (e)
    {
        var coords = e.get('coords');

        if (!currentObject)
        {
            currentObject = new ymaps.Placemark(coords);
            myMap.geoObjects.add(currentObject);
        }
        else
        {
            currentObject.geometry.setCoordinates(coords);
        }

     	if (document.getElementById("center-gmap_lat"))
            document.getElementById("center-gmap_lat").value = coords[0];
        if (document.getElementById("center-gmap_lng"))
            document.getElementById("center-gmap_lng").value = coords[1];
    		if (document.getElementById("arenda-gmap_lat"))
            document.getElementById("arenda-gmap_lat").value = coords[0];
        if (document.getElementById("arenda-gmap_lng"))
            document.getElementById("arenda-gmap_lng").value = coords[1];

        if (document.getElementById("center-metro"))
        {
            myGeocoder = ymaps.geocode(coords, {kind: 'metro', results: '1'});
            myGeocoder.then(
            function (res) {
                var metr = res.geoObjects.get(0);
                var str = metr.properties.get('name');
                document.getElementById("center-metro").value = str.replace("метро ", "");
                },
                function (err) {
                    // обработка ошибки
            }
            );
        }




    }
    // ,
    // _onContextMenu:
    // function (e)
    // {
    //     var coords = e.get('coords');
	  //     alert('Центр карты: ' + coords[0] + ', ' + coords[1]);
	  // }
};

$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});

myGeocoder = ymaps.geocode(coords, {kind: 'metro', results: '5'});
myGeocoder.then(
    function (res) {
        for (var i=0; i<5; i++)
        {
            var metr = res.geoObjects.get(i);
            console.log(metr.properties.get('name'));
            console.log(metr.properties.get('metaDataProperty.GeocoderMetaData.formerName'));
            console.log(metr.properties.get('description'));
            console.log(metr.geometry.getCoordinates());
        }
    },
    function (err) {
        // обработка ошибки
    }
);
