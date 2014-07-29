<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 18:14
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class ArtUrlUpdateFactory
 * Для обновления полей каталога
 * @package dmn\mapper
 */
class ArtUrlUpdateFactory extends UpdateFactory{

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
        $values['url']          = $obj->getUrl();
        $values['keywords']     = $obj->getKeywords();
        $values['modrewrite']   = $obj->getModrewrite();
        $values['pos']          = $obj->getPos();
        $values['hide']         = $obj->getHide();
        $values['id_catalog']    = $obj->getIdCatalog();

        if( $id > -1 ) {
            $cond['id_position'] = $id;
        }
        return $this->buildStatement( "system_menu_position", $values, $cond );
    }
}

?>