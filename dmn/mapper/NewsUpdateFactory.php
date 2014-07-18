<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/07/14
 * Time: 23:45
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpdateFactory.php" );

class NewsUpdateFactory extends UpdateFactory{

    function newUpdate( \dmn\domain\DomainObject $obj ) {

        $id = $obj->getId();
        $cond = null;
        $values['name']     = $obj->getName();
        $values['preview']  = $obj->getPreview();
        $values['body']     = $obj->getBody();
        $values['putdate']  = $obj->getPutdate();
        $values['hidedate'] = $obj->getHidedate();
        $values['url']      = $obj->getUrl();
        $values['urltext']  = $obj->getUrltext();
        $values['alt']      = $obj->getAlt();
        $values['urlpict']  = $obj->getUrlpict();
        $values['urlpict_s']= $obj->getUrlpict_s();
        $values['pos']      = $obj->getPos();
        $values['hide']     = $obj->getHide();
        $values['hidepict'] = $obj->getHidepict();

        if( $id > -1 ) {
            $cond['id_news'] = $id;
        }
        return $this->buildStatement( "system_news", $values, $cond );
    }
}

?>