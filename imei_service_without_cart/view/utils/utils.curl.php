<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 19:00
 */
namespace imei_service\view\utils;
error_reporting( E_ALL & ~E_NOTICE );


//для удобства работы с КУРЛом, напишем простенькую функцию
//параметры: $host - адрес, $referer - откуда пришли (можно подделать, в статистике парсимого сайта будет отображаться, что мы пришли, например, с Яндекса :))
//$file	- идентификатор файла (если мы хотим скачать файл, то передаём его идентификатор)
//function curl_get($hostGet,$user_agent){
function curlGet($hostGet,$user_agent){
    //инициализация curl и задание основных параметров
    $curl = curl_init($hostGet);
    curl_setopt($curl, CURLOPT_USERAGENT, $user_agent );
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // вывести на экран, если нет - то в переменную
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, 'imei_service/view/utils/cookieGet.txt');  // Записываем cookie
    curl_setopt($curl, CURLOPT_COOKIEFILE, 'imei_service/view/utils/cookieGet.txt'); // Читаем cookies
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: iphoneimei.info'));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);     // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
    //если дали ссылку на файл


//  если CURLOPT_RETURNTRANSFER - false
    //если же ссылку на файл не дали, то возвращаем страничку
    ob_start();
    curl_exec($curl);
    print(curl_error($curl));
    curl_close($curl);
    return ob_get_clean();

//  если CURLOPT_RETURNTRANSFER - true
//    $out = curl_exec($curl);
//        echo $out;
//    curl_close($curl);
//    return $out;
}

function curlPost($host, $user_agent, $ime_i ) {
    if( $curl = curl_init() ) {
        curl_setopt($curl, CURLOPT_URL, $host); // Инициализируем соединение, можно и сразу в curl_init передать
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent ); // Содержимое заголовка user_agent в HTTP-запросе
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true); // Возвращаем результат в виде строки, вместо вывода в окно браузера - TRUE
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'imei_service/view/utils/cookiePost.txt');  // Записываем cookie
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'imei_service/view/utils/cookiePost.txt'); // Читаем cookies
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FALSE - остановка от проверки сертификата узла
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Проверка существования общего имени в сертификате
        curl_setopt($curl, CURLOPT_POST, true); // TRUE - для использования POST (applications/x-www-form-urlencoded)
        curl_setopt($curl, CURLOPT_POSTFIELDS, "ime_i="+$ime_i); // Данные в POST (при передачи файлов, в начале - @ - значит массив)
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);     // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
        $out = curl_exec($curl);
//        echo $out;
        curl_close($curl);
        return $out;


//        ob_start();
//        curl_exec($curl);
//        print(curl_error($curl));
//        curl_close($curl);
//        return ob_get_clean();
    }
}