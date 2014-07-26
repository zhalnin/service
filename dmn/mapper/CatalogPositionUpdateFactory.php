<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 20:50
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class CatalogUpdateFactory
 * Для обновления полей каталога
 * @package dmn\mapper
 */
class CatalogPositionUpdateFactory extends UpdateFactory{

    /**
     * Получаем объект и инициализируем переменные
     * строим запрос
     * @param \dmn\domain\DomainObject $obj
     * @return array
     */
    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['operator']     = $obj->getOperator();
        $values['cost']         = $obj->getCost();
        $values['timeconsume']  = $obj->getTimeconsume();
        $values['compatible']   = $obj->getCompatible();
        $values['status']       = $obj->getStatus();
        $values['currency']     = $obj->getCurrency();
        $values['hide']         = $obj->getHide();
        $values['pos']          = $obj->getPos();
        $values['putdate']      = $obj->getPutdate();
        $values['id_catalog']   = $obj->getIdCatalog();

        if( $id > -1 ) {
            $cond['id_position'] = $id;
        }
        return $this->buildStatement( "system_position", $values, $cond );
    }
}

?>