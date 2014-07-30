<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 14:00
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class ArtParagraphUpdateFactory
 * Для обновления полей параграфа каталога
 * @package dmn\mapper
 */
class ArtParagraphUpdateFactory extends UpdateFactory{

    /**
     * Получаем объект и инициализируем переменные
     * строим запрос
     * @param \dmn\domain\DomainObject $obj
     * @return array
     */
    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['name']         = $obj->getName();
        $values['type']         = $obj->getType();
        $values['align']        = $obj->getAlign();
        $values['hide']         = $obj->getHide();
        $values['pos']          = $obj->getPos();
        $values['id_position']  = $obj->getIdPosition();
        $values['id_catalog']   = $obj->getIdCatalog();

        if( $id > -1 ) {
            $cond['id_paragraph'] = $id;
        }
        return $this->buildStatement( "system_menu_paragraph", $values, $cond );
    }
}

?>