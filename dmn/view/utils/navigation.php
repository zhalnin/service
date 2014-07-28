<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 25/07/14
 * Time: 18:58
 */
namespace dmn\view\utils;
error_reporting(E_ALL & ~E_NOTICE);
require_once( "dmn/base/Registry.php" );

/**
 * Функция для управления меню навигации - Where am I now?
 * @param $id_catalog - id каталога
 * @param $link - формируемая ссылка уже в функции рекурсивно
 * @param $catalog - имя таблицы в БД
 * @param $cmd - команда для адресации
 * @return string - отображение нахождения в дереве каталога
 * @throws \dmn\base\DBException
 */
function navigation( $id_catalog, $link, $catalog, $cmd ) {

    $pdo = \dmn\base\DBRegistry::getDB(); // дескриптор БД
    $id_catalog = intval($id_catalog); // id каталога
    $stmt = "SELECT * FROM $catalog
                WHERE id_catalog = ?";
    $sth = $pdo->prepare( $stmt ); // подготавливаем запрос
    $result = $sth->execute( array( $id_catalog ) ); // выполняем запрос
    if(! $result ) { // если не выполнен
        throw new \dmn\base\DBException(mysql_error(),
            $result,
            "Ошибка обращения к
            таблице каталога
            navigation()");
    }
    $raw = $sth->fetch(); // получаем массив с результатом
//    echo "<tt><pre>".print_r($raw, true)."</pre></tt>";
    if( ! empty( $raw ) ) { // если не пустой массив
        $link = "<a class=menu
                    href=dmn.php?cmd=$cmd&idp=".$raw['id_catalog'].">
                    ".$raw['name']."</a>-&gt;".$link;
        $link = navigation($raw['id_parent'],
                        $link,
                        $catalog,
                        $cmd );
    }
    return $link;
}
?>