<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 16:40
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

/**
 * Class ArtParagraphImgUpdateFactory
 * Для обновления полей изображений параграфа каталога
 * @package dmn\mapper
 */
class ArtParagraphImgUpdateFactory extends UpdateFactory{

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
        $values['alt']          = $obj->getAlt();
        $values['small']        = $obj->getSmall();
        $values['big']          = $obj->getBig();
        $values['hide']         = $obj->getHide();
        $values['pos']          = $obj->getPos();
        $values['id_position']  = $obj->getIdPosition();
        $values['id_catalog']   = $obj->getIdCatalog();
        $values['id_paragraph'] = $obj->getIdParagraph();

        if( $id > -1 ) {
            $cond['id_image'] = $id;
        }
        return $this->buildStatement( "system_menu_paragraph_image", $values, $cond );
    }
}

?>