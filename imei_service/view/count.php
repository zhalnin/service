<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28.05.12
 * Time: 12:31
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view;
error_reporting(E_ALL & ~E_NOTICE);
require_once( 'imei_service/view/utils/getIP.php' );
require_once( 'imei_service/view/utils/utf8_win.php' );
require_once( 'imei_service/view/utils/getNameServer.php' );
require_once( 'imei_service/view/utils/getVerBrowser.php' );
require_once( 'imei_service/base/Registry.php' );
require_once( 'imei_service/view/ViewHelper.php' );


/**
 * Возвращаем дескриптор БД
 * @return mixed
 */
function getPDO() {
    if( ! isset( $pdo ) ) {
        $pdo = \imei_service\base\DBRegistry::getDB();
    }
    return $pdo;
}

/**
 * Получаем подготовленное выражение
 * для запроса к БД
 * @param $stmt
 * @return mixed
 */
function getStmt( $stmt ) {
//    echo "<tt><pre>".print_r( $stmt, true )."</pre></tt>";
    return getPDO()->prepare( $stmt );
}

/**
 * Возвращаем id вставленной записи
 * @return mixed
 */
function getId() {
    return getPDO()->lastinsertId();
}

/**
 * Получаем id_page под которой существует,
 * вставлена или обновлена запись с именем
 * страницы на которой сработал счетчик
 * @param $titlepage - имя страницы
 * @param null $cmd - доп. параметр в строке запроса
 * @return mixed - id_pages
 * @throws \imei_service\base\AppException
 */
function getIdByTitlepage( $titlepage, $cmd=null ) {
    // Проверяем нет ли такой страницы в базе данных
    $selectStmt = getStmt( "SELECT id_page FROM powercounter_pages
                WHERE title = ?" );
    $sth = $selectStmt->execute( array( $titlepage ) );
    $result = $selectStmt->fetch();
    if( $sth ) { // если вызов успешен
        // Выясним, первичный ключ(id_page)
        // текущей страницы (по названию страницы)
        if( $result['id_page'] > 0 ) { // если id_page больше 0, т.е. имеется в БД
            return $result['id_page']; // возвращаем id_page в таблице БД
        } else { // если нет в БД
            // Если название данной страницы отсутсвует в таблице pages
            // то проверяем страницу по ее адресу
            $selectStmt2 = getStmt( "SELECT id_page FROM powercounter_pages
                        WHERE name=?" );
            $sth2 = $selectStmt2->execute( array( $_SERVER[PHP_SELF]."?cmd=$cmd" ) );
            $result2 = $selectStmt2->fetch();
            if( $sth2 ) {
                if( $result2['id_page'] > 0 ) {  // Страница существует - обновляем её название
                    $id_page = $result2['id_page'];
                    $updateStmt = getStmt( "UPDATE powercounter_pages
                                SET title = ?
                                WHERE id_page = ?" );
                    $sth3 = $updateStmt->execute( array( $titlepage, $id_page ) );
                    if( ! $sth3 ) {
                        throw new \imei_service\base\AppException('Error in UPDATE 1');
                    }
                    // Если данная страница отсутствует в таблице pages
                    // и не разу не учитывалась - добавляем данную
                    // страницу в таблицу
                } else {
                    $insertStmt = getStmt( "INSERT INTO powercounter_pages VALUES( ?, ?, ?, ? )" );
                    $sth3 = $insertStmt->execute( array( null, $_SERVER['PHP_SELF']."?cmd=$cmd", $titlepage, 0 ) );
                    if( ! $sth3 ) {
                        throw new \imei_service\base\AppException('Error in UPDATE 2');
                    }
                    // Выясняем первичный ключ только что добавленной
                    // страницы
                    return $id_page = getId(); // возвращаем id_page только что вставленный в таблицу БД
//                        echo "<tt><pre>".print_r( $id_page, true )."</pre></tt>";
                }
            } else {
                throw new \imei_service\base\AppException('Error in SELECT 2');
            }
        }
    }
}

/**
 * Вставляем данные в таблицу powercounter_id
 * @param $ip_s
 * @param $id_page_s
 * @param $browser_s
 * @param $os_s
 * @throws \imei_service\base\DBException
 */
function setIp( $ip_s, $id_page_s, $browser_s, $os_s ) {
//    echo "<tt><pre> --- ".print_r( $ip_s, true )."</pre></tt>";
    $insertStmt = getStmt( 'INSERT INTO powercounter_ip VALUES ( ?, INET_ATON(?), ?, ?, ?, ? )' );
    if( ! $insertStmt ) {
        throw new \imei_service\base\DBException('Error in setIp() - 1');
    }
    $date = date( 'Y-m-d H:i:s', time() );
    $sth = $insertStmt->execute( array( NULL, $ip_s, $date, $id_page_s, $browser_s, $os_s ) );
    if( ! $sth ) {
        throw new \imei_service\base\DBException('Error in setIp() - 2');
    }
}

/**
 * Вставляем данные в таблицу powercounter_refferer данные с сайта переадресации
 * @param $reff_s - откуда пришли на страницу
 * @param $ip_s - адрес откуда пришли
 * @param $id_page_s - id_page страницы, которую посетили
 * @throws \imei_service\base\DBException
 */
function setIpRef( $reff_s, $ip_s, $id_page_s ) {
    $insertStmt = 'INSERT INTO powercounter_refferer VALUES( ?, ?, ?, INET_ATON(?), ? )';
    if( ! $insertStmt ) {
        throw new \imei_service\base\DBException('Error in setIpRef() - 1');
    }
    $date = date( 'Y-m-d H:i:s', time() );
    $sth = $insertStmt->execute( array( NULL, $reff_s, $date, $ip_s, $id_page_s ) );
    if( ! $sth ) {
        throw new \imei_service\base\DBException('Error in setIpRef() - 2');
    }
}

/**
 * Вставляем данные в таблицу powercounter_querys поисковый запрос
 * @param $query_s
 * @param $ip_s
 * @param $id_page_s
 * @param $search_s
 * @throws \imei_service\base\DBException
 */
function setSearchquerys($query_s, $ip_s, $id_page_s, $search_s ) {
    $insertStmt = 'INSERT INTO powercounter_searchquerys VALUES( ?, ?, ?, INET_ATON(?), ?, ? )';
    if( ! $insertStmt ) {
        throw new \imei_service\base\DBException('Error in setSearchquerys() - 1');
    }
    $date = date( 'Y-m-d H:i:s', time() );
    $sth = $insertStmt->execute( NULL, $query_s, $date, $ip_s, $id_page_s, $search_s );
    if( ! $sth ) {
        throw new \imei_service\base\DBException('Error in setSearchquerys() - 2');
    }
}


try {

    $request            = VH::getRequest();
    $cmd                = $request->getProperty( 'cmd' );

    $ip                 = getIP(); // получаем ip, с которого зашел пользователь
    if(empty($ip)) $ip  = '0.0.0.0';

    // Если название не указано - формируем URL
    if( empty( $titlepage ) ) {
        $titlepage = \imei_service\view\utils\getNS()."?cmd=$cmd";
    }

    // получаем id_pages из таблицы БД
    $id_page = \imei_service\view\getIdByTitlepage( $titlepage, $cmd );

    // Пользовательский агент
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $browser = 'none';
    // Выясняем браузер
    if( strpos( $useragent, "Mozilla" )    !== false ) $browser = 'mozilla';
    if( strpos( $useragent, "MSIE" )       !== false ) $browser = 'msie';
    if( strpos( $useragent, "MyIE" )       !== false ) $browser = 'myie';
    if( strpos( $useragent, "Opera" )      !== false ) $browser = 'opera';
    if( strpos( $useragent, "Netscape" )   !== false ) $browser = 'netscape';
    if( strpos( $useragent, "Firefox" )    !== false ) $browser = 'firefox';
    // Выясняем операционную систему
    $os = 'none';
    if( strpos( $useragent, "Win" )        !== false ) $os = 'windows';
    if( strpos( $useragent, "Linux" )      !== false
    || strpos( $useragent, "Lynx" )       !== false
    || strpos( $useragent, "Unix" )       !== false ) $os = 'unix';
    if( strpos( $useragent, "Macintosh" )  !== false ) $os = 'macintosh';
    if( strpos( $useragent, "PowerPC" )    !== false ) $os = 'macintosh';
    // Выясняем принадлежность к поисковым роботам
    if( strpos( $useragent, "StackRambler" )   !== false ) $os = 'robot_rambler';
    if( strpos( $useragent, "Googlebot" )      !== false ) $os = 'robot_google';
    if( strpos( $useragent, "Yandex" )         !== false ) $os = 'robot_yandex';
    if( strpos( $useragent, "Aport" )          !== false ) $os = 'robot_aport';
    if( strpos( $useragent, "msnbot" )         !== false ) $os = 'robot_msnbot';
    $search = 'none';

    // Это строчка с реферером - URL страницы, с которой
    // посетитель пришел на сайт
    if( ! isset( $_SERVER['HTTP_REFERER'] ) ) $_SERVER['HTTP_REFERER'] = "";
    $reff = urldecode( $_SERVER['HTTP_REFERER'] );
    // Выясняем принадлежность к поисковым системам
    if( strpos( $reff, "yandex" ) ) $search = 'yandex';
    if( strpos( $reff, "rambler" ) ) $search = 'rambler';
    if( strpos( $reff, "google" ) ) $search = 'google';
    if( strpos( $reff, "aport" ) ) $search = 'aport';
    if( strpos( $reff, "mail" ) && strpos( $reff, "search" ) ) $search = 'mail';
    if( strpos( $reff, "msn" ) && strpos( $reff, "results" ) ) $search = 'msn';

    $server_name = $_SERVER["SERVER_NAME"];
    if( substr( $_SERVER["SERVER_NAME"],0, 4 ) == "www." ) {
        $server_name = substr( $_SERVER["SERVER_NAME"], 4 );
    }

    if( strpos( $reff, $server_name ) ) $search = 'own_site'; // значит это одно и тоже приложение, т.е. переадресация в рамках своего сайта

    // вставляем в таблицу powercounter_ip данные
    setIp( $ip, $id_page, $browser, $os );

    if( ! empty( $reff ) && $search =='none') { // если переадресация была с другого сайта и не из поисковика
        setIpRef( $reff, $ip, $id_page );
    }

    // Вносим поисковый запрос в соответствующую таблицу
    if( ! empty( $reff ) && $search != 'none' && $search != 'own_site' ) {
        switch( $search ) {
            case 'yandex': {
                    preg_match("|text=([^&]+)|is", $reff."&", $out);
                    if( strpos( $reff,"yandpage" ) != null)
                        $quer = convert_cyr_string( urldecode( $out[1] ),"k","w" );
                    else
                        $quer = \imei_service\view\utf8_win( $out[1] );
                    break;
                }
            case 'rambler': {
                    preg_match( "|words=([^&]+)|is", $reff."&", $out );
                    $quer = $out[1];
                    break;
                }
            case 'mail': {
                    preg_match( "|q=([^&]+)is", $reff."&", $out );
                    $quer = $out[1];
                    break;
                }
            case 'google': {
                    preg_match( "|[^a]q=([^&]+)|is", $reff."&", $out );
                    $quer = \imei_service\view\utf8_win( $out[1] );
                    break;
                }
            case 'msn': {
                    preg_match( "|q=([^&]+)|is", $reff."&", $out );
                    $quer = \imei_service\view\utf8_win( $out[1] );
                    break;
                }
            case 'aport': {
                    preg_match( "|r=([^&]+)|is", $reff."&", $out );
                    $quer = $out[1];
                    break;
                }
        }
        $symbols = array( "\"","'","(",")","+",",","-" ); // набор запрещенных символов
        $quer = str_replace( $symbols, " ", $quer ); // заменяем из на пробелы
        $quer = trim($quer); // обрезаем пробелы вначале и конце
        $quer = str_replace('|[\s]+|',' ', $quer); // несколько пробелов заменяем одним

        // вставляем данные в таблицу поисковых запросов
        setSearchquerys( $quer, $ip, $id_page, $search );
    }


} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>