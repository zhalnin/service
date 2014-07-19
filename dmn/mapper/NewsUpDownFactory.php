<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 19/07/14
 * Time: 17:17
 */

namespace dmn\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/mapper/UpDownFactory.php" );

/**
 * Class NewsUpDownFactory
 * Для перемещения позиции в блоке администрирования
 * @package dmn\mapper
 */
class NewsUpDownFactory extends UpDownFactory {

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
        $core = "SELECT pos FROM system_news";
        list( $where, $values ) =  $this->buildSelect( $obj );
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
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
        return $this->buildUpdate( 'system_news', $terms, $direct );
    }
}
?>