window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");
// window.axios.defaults.baseURL = "https://qr-game.ezavalishin.ru";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const BootstrapToProto = {
  install(Vue) {
    Vue.prototype.$axios = window.axios;
  }
};

window.ymaps.ready(init);

function init(){
  // Создание карты.
  const myMap = new ymaps.Map("map", {
    // Координаты центра карты.
    // Порядок по умолчанию: «широта, долгота».
    // Чтобы не определять координаты центра карты вручную,
    // воспользуйтесь инструментом Определение координат.
    center: [55.76, 37.64],
    // Уровень масштабирования. Допустимые значения:
    // от 0 (весь мир) до 19.
    zoom: 7
  });

  const yellowCollection = new window.ymaps.GeoObjectCollection(null, {
    preset: 'islands#yellowIcon'
  });

  const urlParams = new URLSearchParams(window.location.search);
  const myParam = urlParams.get('id');

  console.log(myParam);

  window.axios.get('/coordinates' + (myParam ? ('?id=' + myParam) : ''))
    .then(({data}) => {
      const items = data.data;

      for (let i = 0, l = items.length; i < l; i++) {
        console.log(items[i].coords.coordinates);
        yellowCollection.add(new window.ymaps.Placemark([items[i].coords.coordinates[1], items[i].coords.coordinates[0]], {iconContent: items[i].value}));
      }

      myMap.geoObjects.add(yellowCollection).add(yellowCollection);

      console.log(items);
    });
}
