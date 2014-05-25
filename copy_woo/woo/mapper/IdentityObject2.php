<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17/03/14
 * Time: 19:42
 * To change this template use File | Settings | File Templates.
 */

namespace woo\mapper;

class IdentityObject {
    private $columns = array();
    private static $types;

    const TYPE_EQ   = 1;
    const TYPE_L    = 2;
    const TYPE_LE   = 3;
    const TYPE_G    = 4;
    const TYPE_GE   = 5;
    const TYPE_LIKE = 6;

    protected function setColumn( $type, $name, $value ) {
        if( self::typeExists( $type ) ) {
            $this->columns[$type][$name] = $value;
        } else {
            throw new \Exception("type: {$type} does not exists");
        }
    }

    function getColumn( $type, $key ) {
        if( ! isset( $this->columns[$type] ) ) {
            return null;
        }
        if( isset( $this->columns[$type][$key] ) ) {
            $st = $this->columns[$type][$key];
            return $this->columns[$type][$key];
        }
        return null;
    }

    function getColumns( $type ) {
        if( isset( $this->columns[$type] ) ) {
            return $this->columns[$type];
        }
        return  array();
    }

    static function types(){
        if( isset( self::$types ) ) {
            return self::$types;
        }
        self::$types = array(
            self::TYPE_EQ       => "=",
            self::TYPE_L        => "<",
            self::TYPE_LE       => "<=",
            self::TYPE_G        => ">",
            self::TYPE_GE       => ">=",
            self::TYPE_LIKE     => "LIKE");
        return self::$types;
    }

    static function typeExists( $type ) {
        $types = self::types();
        return ( isset( $types[$type] ) );
    }
}