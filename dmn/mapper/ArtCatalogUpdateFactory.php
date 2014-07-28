<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 22:57
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class ArtCatalogUpdateFactory
 * Для обновления полей каталога
 * @package dmn\mapper
 */
class ArtCatalogUpdateFactory extends UpdateFactory{

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
        $values['description']  = $obj->getDescription();
        $values['keywords']     = $obj->getKeywords();
        $values['modrewrite']   = $obj->getModrewrite();
        $values['pos']          = $obj->getPos();
        $values['hide']         = $obj->getHide();
        $values['id_parent']    = $obj->getIdParent();

        if( $id > -1 ) {
            $cond['id_catalog'] = $id;
        }
        return $this->buildStatement( "system_menu_catalog", $values, $cond );
    }
}

?>