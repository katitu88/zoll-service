/**
*
*
*          Переменные
*
*
*/

var topbar = $("#top-bar");
var topbar_sub = $("#top-bar .submenu");
var arrow = $("#top-bar .menu .center .center-overlay .menu-items ul li .arrow");
var isMobile = mobile();
var isIE = msie();    
var head = document.getElementsByTagName('head')[0]; // Пресечь загрузку шрифта google maps (roboto)
var insertBefore = head.insertBefore;


/**
*
*
*          Функции
*
*
*/


function msie() { // Проверка на ишачесть
  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");
  return (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) ? true : false);
}

function mobile(){ // Проверка на мобильность
  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|  palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)  |bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|  esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(  20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|  lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0  |1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12  |21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|  b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi  (rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)  ))
    return true;

  else
    return false;
}


/**
*
*
*          Google maps
*
*
*/


head.insertBefore = function (newElement, referenceElement) {
  if (newElement.href && newElement.href.indexOf('https://fonts.googleapis.com/css?family=Roboto') === 0) {
    return;
  }
  insertBefore.call(head, newElement, referenceElement);
}

function initMap() {
    var office = {lat: 55.839653, lng: 37.516301};
    var map_center = {lat: 55.839653, lng: 37.516301};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: map_center,
      styles: [
        {"elementType": "geometry", "stylers": [{"color": "#212121"}]},
        {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
        {"elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},
        {"elementType": "labels.text.stroke", "stylers": [{"color": "#212121"}]},
        {"featureType": "administrative", "elementType": "geometry", "stylers": [{"color": "#757575"}]},
        {"featureType": "administrative.country", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]},{"featureType": "administrative.locality",
          "elementType": "labels.text.fill", "stylers": [{"color": "#bdbdbd"}]},{"featureType": "poi",
          "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},{"featureType": "poi.park",
          "elementType": "geometry",
          "stylers": [{"color": "#181818"}]},{"featureType": "poi.park",
          "elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},{"featureType": "poi.park",
          "elementType": "labels.text.stroke",
          "stylers": [{"color": "#1b1b1b"}]},{"featureType": "road",
          "elementType": "geometry.fill", "stylers": [{"color": "#2c2c2c"}]},{"featureType": "road",
          "elementType": "labels.text.fill", "stylers": [{"color": "#8a8a8a"}]},{"featureType": "road.arterial",
          "elementType": "geometry",
          "stylers": [{"color": "#373737"}]},{"featureType": "road.highway",
          "elementType": "geometry",
          "stylers": [{"color": "#3c3c3c"}]},{"featureType": "road.highway.controlled_access",
          "elementType": "geometry",
          "stylers": [{"color": "#4e4e4e"}]},{"featureType": "road.local",
          "elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},{"featureType": "transit",
          "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},{"featureType": "water",
          "elementType": "geometry",
          "stylers": [{"color": "#000000"}]},{"featureType": "water",
          "elementType": "labels.text.fill", "stylers": [{"color": "#3d3d3d"}]}
      ]   
    });

  var marker = new google.maps.Marker({
    position: office,
    map: map
  });
}


/**
*
*
*          Мобильное устройство
*
*
*/


if(isMobile) {

}  

/**
*
*
*          Ишак и проверка его версии с заглушкой
*
*
*/

function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            if (oldonload) {
                oldonload();
            }
            func();
        }
    }
}

addLoadEvent(function(){
    outdatedBrowser({
        bgColor: '#0378D7',
        color: '#ffffff',
        lowerThan: 'borderImage',
        languagePath: ''
    })
})


var lazyjs = new LazyLoad({
  elements_selector: ".display_img"
});

var body = document.body,
    timer,
    hover_disabled = false;

window.addEventListener('scroll', function() {
  clearTimeout(timer);
  if( ! hover_disabled && ! body.classList.contains('disable-hover')) {
    body.classList.add('disable-hover');
    hover_disabled = true;
  }
  
  timer = setTimeout(function(){
    body.classList.remove('disable-hover');
    hover_disabled = false;
  }, 300);
}, false);

var bg_options = {
    speed: -5,
    center: false,
    wrapper: null,
    round: true,
    vertical: true,
    horizontal: false
}

$(document).ready(function(){
  lazyjs.update();
  $('.rellax').fadeIn( 200, 'linear' );
  if (!isMobile){
    setTimeout(function(){
      var rellax_bg = new Rellax('.layer-bg', bg_options);
    }, 300);
  }
});  