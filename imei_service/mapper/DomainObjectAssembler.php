<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/05/14
 * Time: 22:08
 */

namespace imei_service\mapper;


class DomainObjectAssembler {
    protected static $PDO;

    function __construct( PersistenceFactory $factory ) {
        $this->factory = $factory;
        if( ! isset( self::$PDO ) ) {
            $dsn = \imei_service\base\DBRegistry::getDB();
            if( is_null( $dsn ) ) {
                throw new \imei_service\base\AppException( "No DSN" );
            }
            self::$PDO = $dsn;
        }
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

    function insert( \imei_service\domain\DomainObject $obj ) {
        $upfact = $this->factory->getUpdateFactory();
        list( $update, $values ) = $upfact->newUpdate( $obj );
        $stmt = $this->getStatement( $update );
        $stmt->execute( $values );
        if( $obj->getId() < 0 ) {
            $obj->setId( self::$PDO->lastInsertId() );
        }
        $obj->markClean();
    }
}
?>