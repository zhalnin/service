<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 25/07/14
 * Time: 22:34
 */
namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class CatalogUpdateFactory
 * Для обновления полей каталога
 * @package dmn\mapper
 */
class CatalogUpdateFactory extends UpdateFactory{

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
        $values['order_title']  = $obj->getOrderTitle();
        $values['description']  = $obj->getDescription();
        $values['keywords']     = $obj->getKeywords();
        $values['abbreviatura'] = $obj->getAbbreviatura();
        $values['modrewrite']   = $obj->getModrewrite();
        $values['pos']          = $obj->getPos();
        $values['hide']         = $obj->getHide();
        $values['urlpict']      = $obj->getUrlpict();
        $values['alt']          = $obj->getAlt();
        $values['rounded_flag'] = $obj->getRoundedFlag();
        $values['title_flag']   = $obj->getTitleFlag();
        $values['alt_flag']     = $obj->getAltFlag();
        $values['id_parent']    = $obj->getIdParent();

        if( $id > -1 ) {
            $cond['id_catalog'] = $id;
        }
        return $this->buildStatement( "system_catalog", $values, $cond );
    }
}

?>