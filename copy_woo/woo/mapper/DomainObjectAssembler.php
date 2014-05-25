<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 25/03/14
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

class DomainObjectAssembler {
    protected static $PDO;

    function __construct( PersistenceFactory $factory ) {
        $this->factory = $factory;
        if( ! isset( self::$PDO ) ) {
            self::$PDO = \woo\base\PDORegistry::getPDO();
        }
//        echo "<tt><pre>".print_r( self::$PDO, true)."</pre></tt>";
    }

    function getStatement( $str ) {
        if( ! isset( $this->statements[$str] ) ) {
            $this->statements[$str] = self::$PDO->prepare( $str );
        }
        return $this->statements[$str];
    }

    function findOne( IdentityObject $idobj ) {
        $collection = $this->find( $idobj );
        return $collection->next();
    }

    function find( IdentityObject $idobj ) {
        $selfact = $this->factory->getSelectionFactory();
        list( $selection, $values ) = $selfact->newSelection( $idobj );
        $stmt = $this->getStatement( $selection );
        $stmt->execute( $values );
        $raw = $stmt->fetchAll();
        return $this->factory->getCollection( $raw );
    }

    function insert( \woo\domain\DomainObject $obj ) {
        $upfact  = $this->factory->getUpdateFactory();
        list( $update, $values ) = $upfact->newUpdate( $obj );
//        print "$update (".implode( $values ). ")<br />";
//        echo "<tt><pre>".print_r( $values, true)."</pre></tt>";
        $stmt = $this->getStatement( $update );
//        echo "<tt><pre>".print_r( $stmt->execute($values), true)."</pre></tt>";
        $stmt->execute( $values );
        if( $obj->getId() < 0 ) {
            $obj->setId( self::$PDO->lastInsertId() );
        }
        $obj->markClean();
    }
}
?>