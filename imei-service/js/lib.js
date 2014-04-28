/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/20/13
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */

//var urlIMEI = "http://localhost:8888/imei-service/2.php",

//var urlIMEI = "http://localhost:8888/imei-service/2b.php",

//var urlIMEI = "http://imei-service.ru",


//var urlIMEI = "http://iunlocker.net/",
//    urlParser = "check_imei.php",

var urlIMEI = "http://iphoneimei.info",
    urlHandler = "parser.php",

    main = AM.DOM.$('main'),
    xmlHttp = false;


/**
 * Send Ajax from watchFrom() in imei_form.js  in some script by GET
 * to take IMEI info
 * @param param
 */
function start_ajax(param){
    AM.Ajax.ajax({
        mode: 'POST',
        url: urlHandler,
        dataType: 'text/html',
        onError: function() {
            console.log('error in ajax - file lib.js' );
        },
        onSuccess: parseResult,
        onStart: showOverlay,
        onEnd: hideOverlay,
        postParams: "url="+urlIMEI+"/?imei="+param
    });
}



/**
 * Send Ajax from watchFrom() in imei_form.js in some script by POST
 * to take IMEI info
 * @param param
 */
function start_post_ajax(param){
    AM.Ajax.ajax({
        mode: 'POST',
        url: "parserTest.php",
        dataType: 'text/html',
        onError: function() {
            console.log('error in ajax - file lib.js' );
        },
        onSuccess: parseResult,
        onStart: showOverlay,
        onEnd: hideOverlay,
        postParams: "url="+urlIMEI+"&urlParser="+urlParser+"&ime_i="+param
    });
}


/**
 * Show result of AJAX's response
 * @param responseText
 */
function parseResult(responseText){
//    console.log("parseResult()");
//    console.log(responseText);
//    hideOverlay();
    AM.DOM.$('progressbar').innerHTML = responseText;
    // фокусируем на "div", который будет содержать весь ответ по проверке IMEI

    AM.DOM.$("navigation").scrollIntoView(true);
}


/**
 * Позиционирование затемнения по середние экрана
 */
function adjustOverlay() {
    // Обнаружение галереи
    var obj = AM.DOM.$("overlay");
    // Определение существования галереи
    if(!obj) return;
    // Определение ее текущей высоты и ширины
    var w = AM.Position.getWidth(obj),
        h = AM.Position.getHeight(obj),
    // Вертикальное позиционирование контейнера по середине окна
        t = AM.Position.scrollY() + ( AM.Position.windowHeight() / 2 ) - ( h / 2 );
    // Но не выше верхней части страницы
    if(t < 0) t = 0;
    // Горизонтальное позиционирование контейнера по середине окна
    var l = AM.Position.scrollX() + ( AM.Position.windowWidth() / 2 ) - ( w / 2 );
    // Но не левее, чем левый край страницы
    if(l < 0) l = 0;
    // Установка выверенной позиции элемента
    AM.Position.setY(obj, t);
    AM.Position.setX(obj, l);
}

/**
 * Перепозиционирование затемнения на весь экран
 */
function adjustAllOverlay() {
    if( id("overlay") != undefined ) {
        // Обнаружение затемнения
        var overl = id("overlay");
        // Установка его размеров по высоте и ширине текущей страницы
        // (что будет полезным при использовании прокрутки)
        overl.style.height = AM.Position.scrollY() + AM.Position.windowHeight()+ "px";
        overl.style.width = AM.Position.scrollX() + AM.Position.windowWidth()+"px";
    }
}


function adjust() {
//    console.log('adjust');
    // Обнаружение галереи
    var obj = AM.DOM.$("gallery");
    // Определение существования галереи
    if(!obj) return;
    // Определение ее текущей высоты и ширины
    var w = AM.Position.getWidth(obj),
        h = AM.Position.getHeight(obj),
    // Вертикальное позиционирование контейнера по середине окна
        t = AM.Position.scrollY() + ( AM.Position.windowHeight() / 2 ) - ( h / 2 );
    // Но не выше верхней части страницы
    if(t < 0) t = 0;
    // Горизонтальное позиционирование контейнера по середине окна
    var l = AM.Position.scrollX() + ( AM.Position.windowWidth() / 2 ) - ( w / 2 );
    // Но не левее, чем левый край страницы
    if(l < 0) l = 0;
    // Установка выверенной позиции элемента
    AM.Position.setY(obj, t);
    AM.Position.setX(obj, l);
}

function adjustElem( elem ) {
//    console.log('adjust');
    // Обнаружение галереи
    var obj = AM.DOM.$( elem );
    // Определение существования галереи
    if(!obj) return;
    // Определение ее текущей высоты и ширины
    var w = AM.Position.getWidth(obj),
        h = AM.Position.getHeight(obj),
    // Вертикальное позиционирование контейнера по середине окна
        t = AM.Position.scrollY() + ( AM.Position.windowHeight() / 2 ) - ( h / 2 );
    // Но не выше верхней части страницы
    if(t < 0) t = 0;
    // Горизонтальное позиционирование контейнера по середине окна
    var l = AM.Position.scrollX() + ( AM.Position.windowWidth() / 2 ) - ( w / 2 );
    // Но не левее, чем левый край страницы
    if(l < 0) l = 0;
//    console.log(t);
    // Установка выверенной позиции элемента
    AM.Position.setY(obj, t);
    AM.Position.setX(obj, l);
}



/**
 * Скрытие затемнения и текущей галереи
 */
function hideOverlay() {
//    console.log('hideOverlay()');
    // Обеспечение перезапуска значения и галереи
    curImage = null;
    // и скрытия затемнения и галереи
    AM.DOM.hide( AM.DOM.$( "overlay" ) );

}

/**
 * Проявление затемнения
 */
function showOverlay() {
//    console.log("showOverlay()");
    // Обнаружение затемнения
    var over = AM.DOM.$("overlay"),
        div_img = AM.DOM.$('div_img');


    // Установка его размеров по высоте и ширине текущей страницы
    // (что будет полезным при использовании прокрутки)
    div_img.style.marginTop = '150px';
    over.style.height = AM.Position.windowHeight()+ "px";
    over.style.width = AM.Position.windowWidth()+"px";

    // и проявление
    AM.DOM.fadeIn(over, 50, 3);
}