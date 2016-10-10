ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map", {
            center: [55.73, 37.75],
            zoom: 12
        }, {
            searchControlProvider: 'yandex#search'
        }),
        cafe, metro;

    function findClosestObjects () {
        // Найдем в выборке кафе, ближайшее к найденной станции метро,
        // и откроем его балун.
        cafe.getClosestTo(metro.get(0)).balloon.open();

        // Будем открывать балун кафе, который ближе всего к месту клика
        myMap.events.add('click', function (event) {
            cafe.getClosestTo(event.get('coords')).balloon.open();
        });
    }

    // Описания кафе можно хранить в формате JSON, а потом генерировать
    // из описания геообъекты с помощью ymaps.geoQuery.
    cafe = ymaps.geoQuery({
        type: 'FeatureCollection',
        features: [{
                type: 'Feature',
                properties: {
                    balloonContent: 'Кофейня "Дарт Вейдер" - у нас есть печеньки!'
                },
                geometry: {
                    type: 'Point',
                    coordinates: [55.724166, 37.545849]
                }
            }, {
                type: 'Feature',
                properties: {
                    balloonContent: 'Кафе "Горлум" - пирожные прелесть.'
                },
                geometry: {
                    type: 'Point',
                    coordinates: [55.717495, 37.567886]
                }
            }, {
                type: 'Feature',
                properties: {
                    balloonContent: 'Кафе "Кирпич" - крепкий кофе для крепких парней.'
                },
                geometry: {
                    type: 'Point',
                    coordinates: [55.7210180,37.631057]
                }
            }
        ]
    // Сразу добавим точки на карту.
    }).addToMap(myMap);


    var myGeocoder = ymaps.geocode([55.744828, 37.603423], {kind: 'metro'});
    myGeocoder.then(
        function (res) {
            myMap.geoObjects.add(res.geoObjects);
            // Выведем в консоль данные, полученные в результате геокодирования объекта.

            console.log('aaa');
            console.log(res.geoObjects.get(0).properties.get('metaDataProperty'));
            //alert(res.geoObjects.get(0).properties.get('metaDataProperty'));
        },
        function (err) {
            // обработка ошибки
        }
    );

    // С помощью обратного геокодирования найдем метро "Кропоткинская".
    metro = ymaps.geoQuery(ymaps.geocode([55.744828, 37.603423], {kind: 'metro'}))
    // Нужно дождаться ответа от сервера и только потом обрабатывать полученные результаты.
        .then(findClosestObjects);


    var stations = [
        "Черкизовская",
        "Преображенская площадь",
        "Сокольники",
        "Красносельская",
        "Комсомольская",
        ];
    var total;
    //alert(stations.length);
}
