/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/9/13
 * Time: 10:51 PM
 * To change this template use File | Settings | File Templates.
 */

//addEvent(window,"load", function(e){
//    var currency_span = document.getElementsByTagName('span');
//    for(var k = 0, lenK = currency_span.length; k < lenK; k++){
//        switch (currency_span[k].id){
//            case('usd'):
//                addEvent(currency_span[k],'click',function(e){
//                    COOKIE.setCookie("currency_type","usd");
//                    location.reload();
//                });
//                break;
//            case('eur'):
//                addEvent(currency_span[k],'click',function(e){
//                    COOKIE.setCookie("currency_type","eur");
//                    location.reload();
//                });
//                break;
//            case('uah'):
//                addEvent(currency_span[k],'click', function(e) {
//                    COOKIE.setCookie("currency_type","uah");
//                    location.reload();
//                });
//                break;
//            case('rub'):
//                addEvent(currency_span[k],'click', function(e) {
//                    COOKIE.setCookie("currency_type","rub");
//                    location.reload();
//                });
//                break;
//        }
//    }
//
//});



AM.Event.addEvent(window,"load", function(e){
    var currency_span = document.getElementsByTagName('span');
    for(var k = 0, lenK = currency_span.length; k < lenK; k++){
        switch (currency_span[k].id){
            case('usd'):
                addEvent(currency_span[k],'click',function(e){
                    COOKIE.setCookie("currency_type","usd");
                    location.reload();
                });
                break;
            case('eur'):
                addEvent(currency_span[k],'click',function(e){
                    COOKIE.setCookie("currency_type","eur");
                    location.reload();
                });
                break;
            case('uah'):
                addEvent(currency_span[k],'click', function(e) {
                    COOKIE.setCookie("currency_type","uah");
                    location.reload();
                });
                break;
            case('rub'):
                addEvent(currency_span[k],'click', function(e) {
                    COOKIE.setCookie("currency_type","rub");
                    location.reload();
                });
                break;
        }
    }

});