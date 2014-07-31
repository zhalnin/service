<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 14:02
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpDownFactory.php" );

/**
 * Class ArtParagraphUpDownFactory
 * Для перемещения позиции в блоке администрирования
 * @package dmn\mapper
 */
class ArtParagraphUpDownFactory extends UpDownFactory {

    /**
     * Метод для SELECT из БД для получения
     * - current
     * - previous/next
     * @param IdentityObject $obj
     * @param $orderBy
     * @return array
     */
    function newUpDownSelect( IdentityObject $obj, $orderBy ) {
        $fields = implode( ',', $obj->getObjectFields() );
        $core = "SELECT pos FROM system_menu_paragraph";
        list( $where, $values ) =  $this->buildSelect( $obj );
//        echo "<tt><pre>".print_r($core, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";
        return array( $core." ".$where." ".$orderBy ." LIMIT 1", $values );
    }

    /**
     * Метод для UPDATE
     * перемещение позиции вверх или вниз
     * @param $terms
     * @param $direct
     * @return array
     */
    function newUpDownUpdate( $terms, $direct ) {
        return $this->buildUpdate( 'system_menu_paragraph', $terms, $direct );
    }
}
?>