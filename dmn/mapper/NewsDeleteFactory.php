<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/07/14
 * Time: 22:25
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/DeleteFactory.php" );

/**
 * Class NewsDeleteFactory
 * Для получения условия и удаления записи из
 * таблицы system_news
 * @package dmn\mapper
 */
class NewsDeleteFactory extends DeleteFactory{

    /**
     * Получаем объект,
     * из него id удаляемой записи
     * и строим запрос
     * @param \dmn\domain\DomainObject $obj
     * @return array
     */
    function newDelete( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId(); // получаем id записи
        $cond = null;

        if( $id > -1 ) { // если есть условие, а оно есть
            $cond['id_news'] = $id;
        }
    return $this->buildStatement( "system_news", $cond ); // строим запрос
    }
}

?>