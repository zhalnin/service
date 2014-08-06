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
require_once( 'imei_service/base/Registry.php' );
require_once( 'imei_service/view/ViewHelper.php' );


function getPDO() {
    if( ! isset( $pdo ) ) {
        $pdo = \imei_service\base\DBRegistry::getDB();
    }
    return $pdo;
}

function getStmt( $stmt ) {
    return getPDO()->prepare( $stmt );
}

function getId() {
    return getPDO()->lastinsertId();
}


try {
    // Название таблиц
    $tbl_ip             = 'powercounter_ip';
    $tbl_pages          = 'powercounter_pages';
    $tbl_refferer       = 'powercounter_refferer';
    $tbl_searchquerys   = 'powercounter_searchquerys';


    // Параметры соединения
    $dblocation         = 'localhost';
    $dbname             = 'imei-service';
    $dbuser             = 'root';
    $dbpasswd           = 'zhalnin5334';

    $request            = VH::getRequest();
    $cmd                = $request->getProperty( 'cmd' );

    $ip                 = getIP(); // получаем ip, с которого зашел пользователь
    if(empty($ip)) $ip  = '0.0.0.0';



//    echo "<tt><pre>".print_r( getStmt( 'Select' ), true )."</pre></tt>";

//    // Соединяемся с сервером базы данных
//    $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
////    if(!$dbcnx) return;
//    if(!$dbcnx) {
//        throw new \Exception('Error in connect DB');
//    }
//    // Выбираем базу данных
////    if(!@mysql_select_db($dbname, $dbcnx)) exit();
//    if(!@mysql_select_db($dbname, $dbcnx)) {
//        throw new \Exception('Error in select DB');
//    }

    // Если название не указано - формируем URL
    if( empty( $titlepage ) ) {
        $titlepage = \imei_service\view\utils\getNS()."?cmd=$cmd";
    }
    // Проверяем нет ли такой страницы в базе данных
    $selectStmt = getStmt( "SELECT id_page FROM $tbl_pages
                WHERE title = ?" );
    $sth = $selectStmt->execute( array( $titlepage ) );
    $result = $selectStmt->fetch();

    if( $sth ) { // если вызов успешен
        // Выясним, первичный ключ(id_page)
        // текущей страницы (по названию страницы)
        if( $result['id_page'] > 0 ) { // если id_page больше 0, т.е. имеется в БД
            $id_page = $result['id_page'];
        } else { // если нет в БД
            // Если название данной страницы отсутсвует в таблице pages
            // то проверяем страницу по ее адресу
            $selectStmt2 = getStmt( "SELECT id_page FROM $tbl_pages
                        WHERE name=?" );
            $sth2 = $selectStmt2->execute( array( $_SERVER[PHP_SELF]."?cmd=$cmd" ) );
            $result2 = $selectStmt2->fetch();
            if( $sth2 ) {
                if( $result2['id_page'] > 0 ) {  // Страница существует - обновляем её название
                    $id_page = $result2['id_page'];
                    $updateStmt = getStmt( "UPDATE $tbl_pages
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
                    $insertStmt = getStmt( "INSERT INTO $tbl_pages VALUES( ?, ?, ?, ? )" );
                    $sth3 = $insertStmt->execute( array( null, $_SERVER['PHP_SELF']."?cmd=$cmd", $titlepage, 0 ) );
                    if( ! $sth3 ) {
                        throw new \imei_service\base\AppException('Error in UPDATE 2');
                    }
                    // Выясняем первичный ключ только что добавленной
                    // страницы
                    $id_page = getId();
//                        echo "<tt><pre>".print_r( $id_page, true )."</pre></tt>";
                }
            } else {
                throw new \imei_service\base\AppException('Error in SELECT 2');
            }
        }
    }


//    // Проверяем нет ли такой страницы в базе данных
//    $query = "SELECT id_page FROM $tbl_pages
//                WHERE title = '$titlepage'";
//    $pgs = mysql_query($query);
//    if($pgs)
//    {
//        // Выясним, первичный ключ(id_page)
//        // текущей страницы (по названию страницы)
//        if(mysql_num_rows($pgs) > 0) $id_page = mysql_result($pgs,0);
//        // Если название данной страницы отсутсвует в таблице pages
//        // то проверяем страницу по ее адресу
//        else
//        {
//            $query = "SELECT id_page FROM $tbl_pages
//                        WHERE name='$_SERVER[PHP_SELF]'";
//            $pgs = mysql_query($query);
//            if($pgs)
//            {
//                // Страница существует - обновляем её название
//                if(mysql_num_rows($pgs)>0)
//                {
//                    $id_page = mysql_result($pgs, 0);
//                    $query = "UPDATE $tbl_pages
//                                SET title = '$titlepage'
//                                WHERE id_page = $id_page";
//                    mysql_query($query);
//                }
//                // Если данная страница отсутствует в таблице pages
//                // и не разу не учитывалась - добавляем данную
//                // страницу в таблицу
//                else
//                {
//                    $query = "INSERT INTO $tbl_pages
//                                VALUES(NULL,
//                                        '$_SERVER[PHP_SELF]',
//                                        '$titlepage',
//                                        0)";
//                    @mysql_query($query);
//                    // Выясняем первичный ключ только что добавленной
//                    // страницы
//                    $id_page = mysql_insert_id();
//                }
//            }
//        }
//    } else {
//        throw new \imei_service\base\AppException('Error in first SELECT');
//    }
    // Пользовательский агент
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $browser = 'none';
    // Выясняем браузер
    if(strpos($useragent, "Mozilla")    !== false) $browser = 'mozilla';
    if(strpos($useragent, "MSIE")       !== false) $browser = 'msie';
    if(strpos($useragent, "MyIE")       !== false) $browser = 'myie';
    if(strpos($useragent, "Opera")      !== false) $browser = 'opera';
    if(strpos($useragent, "Netscape")   !== false) $browser = 'netscape';
    if(strpos($useragent, "Firefox")    !== false) $browser = 'firefox';
    // Выясняем операционную систему
    $os = 'none';
    if(strpos($useragent, "Win")        !== false) $os = 'windows';
    if(strpos($useragent, "Linux")      !== false
    || strpos($useragent, "Lynx")       !== false
    || strpos($useragent, "Unix")       !== false) $os = 'unix';
    if(strpos($useragent, "Macintosh")  !== false) $os = 'macintosh';
    if(strpos($useragent, "PowerPC")    !== false) $os = 'macintosh';
    // Выясняем принадлежность к поисковым роботам
    if(strpos($useragent, "StackRambler")   !== false) $os = 'robot_rambler';
    if(strpos($useragent, "Googlebot")      !== false) $os = 'robot_google';
    if(strpos($useragent, "Yandex")         !== false) $os = 'robot_yandex';
    if(strpos($useragent, "Aport")          !== false) $os = 'robot_aport';
    if(strpos($useragent, "msnbot")         !== false) $os = 'robot_msnbot';
    $search = 'none';

    // Это строчка с реферером - URL страницы, с которой
    // посетитель пришел на сайт
    if(!isset($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = "";
    $reff = urldecode($_SERVER['HTTP_REFERER']);
    // Выясняем принадлежность к поисковым системам
    if(strpos($reff, "yandex")) $search = 'yandex';
    if(strpos($reff, "rambler")) $search = 'rambler';
    if(strpos($reff, "google")) $search = 'google';
    if(strpos($reff, "aport")) $search = 'aport';
    if(strpos($reff, "mail") && strpos($reff, "search")) $search = 'mail';
    if(strpos($reff, "msn") && strpos($reff, "results")) $search = 'msn';
    $server_name = $_SERVER["SERVER_NAME"];
    if(substr($_SERVER["SERVER_NAME"],0,4) == "www.")
    {
        $server_name = substr($_SERVER["SERVER_NAME"], 4);
    }

    if(strpos($reff,$server_name)) $search = 'own_site';

    // Заносим всю собранную информацию в базу данных
    $query_main = "INSERT INTO $tbl_ip VALUES (
                    NULL,
                    INET_ATON('$ip'),
                    NOW(),
                    '$id_page',
                    '$browser',
                    '$os')";
    @mysql_query($query_main);

    if(!empty($reff) && $search =='none')
    {
        $reff = mysql_real_escape_string($reff);
        $query_reff = "INSERT INTO $tbl_refferer VALUES (
                        NULL,
                        '$reff',
                        NOW(),
                        INET_ATON('$ip'),
                        '$id_page')";
        @mysql_query($query_reff);
    }
    // Вносим поисковый запрос в соответствующую таблицу
    if(!empty($reff) && $search != 'none' && $search != 'own_site')
    {
        switch($search)
        {
            case 'yandex':
                {
                    preg_match("|text=([^&]+)|is", $reff."&", $out);
                    if(strpos($reff,"yandpage") != null)
                        $quer = convert_cyr_string(urldecode($out[1]),"k","w");
                    else
                        $quer = \imei_service\view\utf8_win($out[1]);
                    break;
                }
            case 'rambler':
                {
                    preg_match("|words=([^&]+)|is", $reff."&", $out);
                    $quer = $out[1];
                    break;
                }
            case 'mail':
                {
                    preg_match("|q=([^&]+)is", $reff."&", $out);
                    $quer = $out[1];
                    break;
                }
            case 'google':
                {
                    preg_match("|[^a]q=([^&]+)|is", $reff."&", $out);
                    $quer = \imei_service\view\utf8_win($out[1]);
                    break;
                }
            case 'msn':
                {
                    preg_match("|q=([^&]+)|is", $reff."&", $out);
                    $quer = \imei_service\view\utf8_win($out[1]);
                    break;
                }
            case 'aport':
                {
                    preg_match("|r=([^&]+)|is", $reff."&", $out);
                    $quer = $out[1];
                    break;
                }
        }
        $symbols = array("\"","'","(",")","+",",","-");
        $quer = str_replace($symbols, " ", $quer);
        $quer = trim($quer);
        $quer = str_replace('|[\s]+|',' ', $quer);
        $query = "INSERT INTO $tbl_searchquerys
                VALUES (NULL,
                        '$quer',
                        NOW(),
                        INET_ATON('$ip'),
                        '$id_page',
                        '$search')";
        @mysql_query($query);
    }


} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>