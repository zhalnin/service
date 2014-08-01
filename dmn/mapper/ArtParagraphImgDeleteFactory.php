<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/08/14
 * Time: 19:45
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/DeleteFactory.php" );

/**
 * Class ArtParagraphImgDeleteFactory
 * Для получения условия и удаления записи из
 * таблицы system_menu_paragraph_image
 * @package dmn\mapper
 */
class ArtParagraphImgDeleteFactory extends DeleteFactory{

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
            $cond['id_image'] = $id;
        }
        return $this->buildStatement( "system_menu_paragraph_image", $cond ); // строим запрос
    }
}

?>