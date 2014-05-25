<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 20/03/14
 * Time: 12:13
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/base/Registry.php" );


/**
 * Class HelperFactory
 */
class HelperFactory {
    static function getFinder( $type ) {
        $type = preg_replace( "|^.*\\\|","", $type );
        $map = "{$type}Mapper";
        if( class_exists( $map ) ) {
            return new $map;
        }
        throw new Exception("Mapper does not exits");
    }

    static function getCollection( $type ) {
        $type = preg_replace("|^.*\\\|","", $type );
        $collection = "{$type}Collection";
        if( class_exists( $collection ) ) {
            return new $collection;
        }
        throw new Exception("Collection not found");
    }
}



/**
 * Class OW
 */
class OW {
    private $all = array();
    private $dirty = array();
    private $new = array();
    private $delete = array();
    private static $instance;

    private function __construct() {}

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * IdentityMap
     */

    function globalKey( $obj ) {
        $key = get_class( $obj ).".".$obj->getId();
        return $key;
    }

    static function add( $obj ) {
        $inst = self::instance();
        $inst->all[$inst->globalKey( $obj )] = $obj;
    }

    static function exists( $classname, $id ) {
        $inst = self::instance();
        $key = $classname.".".$id;
        if( isset( $inst->all[$key] ) ) {
            return $inst->all[$key];
        }
        return null;
    }



    /**
     * UnitWork
     */

    static function addDelete( $obj ) {
        $self = self::instance();
        $self->delete[$self->globalKey( $obj )] = $obj;
    }

    static function addDirty( $obj ) {
//        echo "<tt><pre> addDirty1 - ".print_r( $obj, true )."</pre></tt>";
        $inst = self::instance();
        if( ! in_array( $obj, $inst->new, true ) ) {
//            echo "<tt><pre> addDirty2 - ".print_r( $obj, true )."</pre></tt>";
            $inst->dirty[$inst->globalKey( $obj )] = $obj;
        }
    }

    static function addNew( $obj ) {
//        echo "<tt><pre> addNew - ".print_r( $obj, true )."</pre></tt>";
        $inst = self::instance();
        $inst->new[] = $obj;
    }

    static function addClean( $obj ) {
//        echo "<tt><pre> addClean - ".print_r( $obj, true )."</pre></tt>";
        $self = self::instance();
        unset( $self->delete[$self->globalKey( $obj )] );
        unset( $self->dirty[$self->globalKey( $obj )] );
//        echo "<tt><pre> addClean self->new - ".print_r( $self->new, true )."</pre></tt>";
        $self->new = array_filter($self->new, function( $a) use ( $obj ) { return !( $a === $obj ); } );
//        echo "<tt><pre> addClean self->new - ".print_r( $self->new, true )."</pre></tt>";
    }

    function preformOperations() {
        foreach ( $this->dirty as $key=>$obj ) {
//            echo "<tt><pre>".print_r( $obj, true )."</pre></tt>";
            $obj->finder()->update( $obj );
        }
        foreach ( $this->new as $key=>$obj ) {
//            echo "<tt><pre>".print_r( $obj, true )."</pre></tt>";
            $obj->finder()->insert( $obj );
        }
        $this->dirty = array();
        $this->new = array();
    }

}




/**
 * Class Map
 */
abstract class Mapper {
    protected static $PDO;

    function __construct() {
        self::$PDO = \woo\base\PDORegistry::getPDO();
    }

    private function getFM( $id ) {
        return OW::exists( $this->targetClass(), $id );
    }

    private function addTM( $obj ) {
        OW::add( $obj );
    }

    function insert( $obj ) {
        $this->doInsert( $obj );
        $this->addTM( $obj );
    }

    function find( $id ) {
        $old = $this->getFM( $id );
        if( $old ) {
//            echo "<tt><pre> Old - ".print_r( $old, true )."</pre></tt>";
            return $old;
        }
        $this->selectStmt()->execute( array( $id ) );
        $array = $this->selectStmt()->fetch();
        $this->selectStmt()->closeCursor();
        if( ! is_array( $array ) ) {
            return null;
        }
        if( ! isset( $array['id'] ) ) {
            return null;
        }
        $object = $this->createObject( $array );

        return $object;
    }

    function getFactory() {
        return PersistenceFactory::getFactory( $this->targetClass() );
    }

    function createObject( $array ){
//        $old = $this->getFM( $array['id'] );
//        if( $old ) {
//            echo "<tt><pre> Old2 - ".print_r( $old, true )."</pre></tt>";
//            return $old;
//        }
//        $obj = $this->doCreateObject( $array );
//        $this->addTM( $obj );
//        $obj->markClean();
//        return $obj;
        $objfactory = $this->getFactory()->getDomainObjectFactory();
        return $objfactory->createObject( $array );
    }

    protected abstract function update( $obj );
}
class VenueMapper extends Mapper {
    function __construct() {
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare( "SELECT * FROM venue WHERE id=?" );
        $this->insertStmt = self::$PDO->prepare( "INSERT INTO venue ( name ) VALUES ( ? )" );
        $this->updateStmt = self::$PDO->prepare( "UPDATE venue SET name=?, id=? WHERE id=?" );
    }

    function getCollection( $raw ) {
        return new SpaceCollection( $raw, $this );
    }

    protected function targetClass() {
        return "Venue";
    }

    function selectStmt() {
        return $this->selectStmt;
    }

    protected function doInsert( $obj ) {
        print "INSERT<br />";
        $values = array( $obj->getName() );
        $this->insertStmt->execute( $values );
        $id = self::$PDO->lastInsertId();
        $obj->setId( $id );
    }

    function update( $obj ) {
        print "UPDATE<br />";
        $values = array( $obj->getName(), $obj->getId(), $obj->getId() );
        $this->updateStmt->execute( $values );
    }

//    function doCreateObject( $array ) {
//        $obj = new Venue( $array['id'] );
//        $obj->setName( $array['name'] );
//        return $obj;
//    }
}




/**
 * Class DomainObj
 */
abstract class DomainObject {
    private $id=-1;

    function __construct( $id=null ) {
        if( is_null( $id ) ) {
            $this->markNew();
        } else {
            $this->id = $id;
        }
    }

    function getId() {
        return $this->id;
    }

    function setId( $id ) {
        $this->id = $id;
    }

    function finder() {
        return self::getFinder( get_class( $this ) );
    }

    static function getFinder( $type ) {
        return HelperFactory::getFinder( $type );
    }

    function markNew() {
//        print "<br />new<br />";
        OW::addNew( $this );
    }

    function markDirty() {
//        print "<br />dirty<br />";
        OW::addDirty( $this );
    }

    function markDelete() {
        OW::addDelete( $this );
    }

    function markClean() {
//        print "<br />clean<br />";
        OW::addClean( $this );
    }
}
class Venue extends DomainObject {
    private $name;

    function __construct( $id=null, $name=null ) {
        $this->name = $name;
        parent::__construct( $id );
    }

    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }

    function getName() {
        return $this->name;
    }
}


//interface VenueCollection extends Iterator {
//    function add( $venue );
//}

/**
 * Class Collection
 */
abstract class Collection {
    protected $mapper;
    protected $dofact;
    protected $total = 0;
    protected $raw = array();

    private $result;
    private $pointer = 0;
    private $objects = array();

    function __construct( $raw, $dofact ) {
//        echo "<tt><pre> RAW - ".print_r( $raw, true )."</pre></tt>";
        if( ! is_null( $raw ) && ! is_null( $dofact ) ) {
            $this->raw = $raw;
            $this->total = count( $raw );
        }
        $this->dofact = $dofact;
    }

    function add( $object ) {
        $class = $this->targetClass();
        if( ! ( $object instanceof $class ) ) {
            throw new Exception( "This collection - {$class}" );
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    protected function notifyAccess() {

    }

    function getRow( $num ) {
        $this->notifyAccess();
        if( $num >= $this->total || $num < 0 ) {
            return null;
        }
        if( isset( $this->objects[$num] ) ) {
            return $this->objects[$num];
        }
        if( isset( $this->raw[$num] ) ) {
//            echo "getRow - createObject";
//            echo "<tt><pre> RAW - co - ".print_r( $this, true )."</pre></tt>";
            $this->objects[$num] = $this->dofact->createObject( $this->raw[$num] );
            return $this->objects[$num];
        }
    }

    function rewind() {
        $this->pointer = 0;
    }

    function current() {
        return $this->getRow($this->pointer );
    }

    function key() {
        return $this->pointer;
    }

    function next() {
        $row = $this->getRow( $this->pointer );
        if( $row ) {
            $this->pointer++;
        }
        return $row;
    }

    function valid() {
        return ( ! is_null( $this->current() ) );
    }

    abstract function targetClass();
}
class VenueCollection extends Collection implements Iterator {
    function targetClass() {
        return "Venue";
    }
}
class DefferedVenueCollection extends VenueCollection {
    private $stmt;
    private $valueArray;
    private $run=false;

    function __construct( $mapper, $stmt_handle, $valueArray ) {
        parent::__construct(null, $mapper);
        $this->stmt = $stmt_handle;
        $this->valueArray = $valueArray;
    }

    function notifyAccess() {
        if( ! $this->run ) {
            $this->stmt->execute( $this->valueArray );
            $this->raw = $this->stmt->fetchAll();
            $this->total = count( $this->raw );
        }
        $this->run = true;
    }
}



/**
 * Class PersistenceFactory
 */
abstract class PersistenceFactory {
    abstract function getMapper();
    abstract function getDomainObjectFactory();
    abstract function getCollection( $array );
    abstract function getSelectionFactory();
    abstract function getUpdateFactory();

    static function getFactory( $target_class ) {
        switch( $target_class ) {
            case "Venue":
                return new VenuePersistenceFactory();
                break;
        }
    }

    static function getFinder( $target_class ) {
        switch( $target_class ) {
            case "Venue":
                $class = new VenuePersistenceFactory();
                return new DomainObjectAssembler( $class );
            break;
        }
    }
}
class VenuePersistenceFactory extends PersistenceFactory {
    function getMapper() {
        return new VenueMapper();
    }

    function getDomainObjectFactory() {
        return new VenueObjectFactory();
    }

    function getCollection( $array ) {
        return new VenueCollection( $array, $this->getDomainObjectFactory() );
    }

    function getSelectionFactory() {
        return new VenueSelectionFactory();
    }

    function getUpdateFactory() {
        return new VenueUpdateFactory();
    }

    function getIdentityObject() {
        return new VenueIdentityObject();
    }
}








/**
 * Class DomainObjectFactory
 */
abstract class DomainObjectFactory {
    abstract  function createObject( $array );
}
class VenueObjectFactory extends DomainObjectFactory {
    function createObject( $array ) {
        $obj = new Venue( $array['id'] );
        $obj->setName( $array['name'] );
        return $obj;
    }
}


/**
 * Class IdentityObject
 */
class IdentityObject2 {
    private $name = null;

    function setName( $name ) {
        $this->name = $name;
    }

    function getName() {
        return $this->name;
    }
}
class VenueIdentityObject2 extends IdentityObject {
    private $id = null;

    function setId( $id ) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }
}



/**
 * Class Field
 */
class Field {
    protected $name = null;
    protected $operator = null;
    protected $comps = array();
    protected $incomplete = false;

    function __construct( $name ) {
        $this->name = $name;
    }

    function addTest( $operator, $value ) {
        $this->comps[] = array('name'=>$this->name,
                                'operator'=>$operator,
                                'value'=>$value );
    }

    function getComps() {
        return $this->comps;
    }

    function isIncomplete() {
        return empty( $this->comps );
    }
}



/**
 * Class IdentityObject
 */
class IdentityObject {
    protected $currentfield = null;
    protected $fields = array();
    private $and = null;
    private $enforce = array();

    function __construct( $field=null, array $enforce=null ) {
//                echo "<tt><pre>".print_r( $enforce, true )."</pre></tt>";
        if( ! is_null( $enforce ) ) {
            $this->enforce = $enforce;
        }
        if( ! is_null( $field ) ) {
            $this->field( $field );
        }

    }

    function getObjectFields() {
        return $this->enforce;
    }

    function field( $filename ) {
        if( ! $this->isVoid() && $this->currentfield->isIncomplete() ) {
            throw new Exception("Field is not completed");
        }
        $this->enforceField( $filename );
        if( isset( $this->fields[$filename] ) ) {
            $this->currentfield = $this->fields[$filename];
        } else {
            $this->currentfield = new Field( $filename );
            $this->fields[$filename] = $this->currentfield;
        }
        return $this;
    }

    function isVoid() {
//        echo "<tt><pre>".print_r( $this->fields, true )."</pre></tt>";
        return empty( $this->fields );
    }

    function enforceField( $filename ) {
        if( ! in_array( $filename, $this->enforce ) &&
            ! empty( $this->enforce ) ) {
            $forcelist = implode(', ', $this->enforce );
            throw new Exception("{$filename} seems to be not eligiable
                                    for this field {$forcelist}");
        }
    }

    function eq( $value ) {
        return $this->operator( "=", $value );
    }
    function gt( $value ) {
        return $this->operator( ">", $value );
    }
    function lt( $value ) {
        return $this->operator( "<", $value );
    }

    function operator( $operator, $value ) {
        if( $this->isVoid() ){
            throw new Exception("Field does not defined");
        }
        $this->currentfield->addTest( $operator, $value );
        return $this;
    }

    function getComps() {
        $ret = array();
//        echo "<tt><pre>".print_r( $this->fields, true )."</pre></tt>";
        foreach ($this->fields as $key=>$val ) {
            $ret = array_merge( $ret, $val->getComps() );
//            echo "<tt><pre>".print_r( $val, true )."</pre></tt>";
        }
//        echo "<tt><pre>".print_r( $ret, true )."</pre></tt>";
        return $ret;
    }
}
class VenueIdentityObject extends IdentityObject {
    function __construct( $field= null ) {
        parent::__construct( $field, array("name","id") );
    }

    function __toString() {
        $result = array();
        foreach ($this->getComps() as $key=>$val ) {
            $result[] = implode(' ', $val);
        }
        return implode(' AND ', $result );
    }
}



/**
 * Class UpdateFactory
 */
abstract class UpdateFactory {
    abstract function newUpdate( $obj );

    protected function buildStatement( $table, array $fields, array $conditions=null ) {
        $terms = array();
        if( ! is_null( $conditions ) ) {
            $query = "UPDATE {$table} SET ";
            $query .= implode(" = ?,", array_keys( $fields ) ) ." = ?";
            $terms = array_values( $fields );
            $cond = array();
            $query .= " WHERE ";
            foreach ( $conditions as $key=>$val ) {
                $cond[] = "$key = ?";
                $terms[] = $val;
            }
            $query .= implode( " AND ", $cond );
        } else {
            $query = "INSERT INTO {$table} (";
            $query .= implode( ",", array_keys( $fields ) );
//            echo "<tt><pre>".print_r( $fields, true )."</pre></tt>";
            $query .= ") VALUES (";
            foreach ( $fields as $name=>$value ) {
                $terms[] = $value;
                $qs[] = '?';
            }
            $query .= implode( ",", $qs );
            $query .= ")";
        }
//        echo "<tt><pre>".print_r( $query, true )."</pre></tt>";
        return array( $query, $terms );
    }
}
class VenueUpdateFactory extends UpdateFactory {
    function newUpdate( $obj ) {
        $id = $obj->getId();
        $cond = null;
        $values['name'] = $obj->getName();
        if( $id > -1 ) {
            $cond['id'] = $id;
        }
        return $this->buildStatement( "venue", $values, $cond );
    }
}


/**
 * Class SelectionFactory
 */
abstract class SelectionFactory {
    abstract function newSelection( $obj );

    function buildSelection( $obj ) {
//        echo "<tt><pre> idobj - ".print_r( $obj, true )."</pre></tt>";
        if( $obj->isVoid() ) {
//            echo "<tt><pre> idobj - ".print_r( $obj, true )."</pre></tt>";
            return array("", array() );
        }
        $compstrings = array();
        $values = array();
        foreach ($obj->getComps() as $comp ) {
//            echo "<tt><pre> idobj - ".print_r( $comp, true )."</pre></tt>";
            $compstrings[] = "{$comp['name']} {$comp['operator']} ?";
            $values[] = $comp['value'];
        }
        $where = "WHERE ".implode(" AND ", $compstrings );
//        echo "<tt><pre> idobj - ".print_r( $where, true )."</pre></tt>";
        return array( $where, $values );
    }
}
class VenueSelectionFactory extends SelectionFactory {

    function newSelection( $obj ) {
//        echo "<tt><pre> idobj - ".print_r( $obj, true )."</pre></tt>";
        $fields = implode( ',', $obj->getObjectFields() );
//        echo "<tt><pre> idobj - ".print_r( $fields, true )."</pre></tt>";
        $core = "SELECT $fields FROM venue";
        list( $where, $values ) = $this->buildSelection( $obj );
//        echo "<tt><pre> idobj - ".print_r( $this->buildSelection($obj), true )."</pre></tt>";
        return array( $core." ".$where, $values );
    }
}


class DomainObjectAssembler {
    protected static $PDO;

    function __construct( PersistenceFactory $factory ) {
        $this->factory = $factory;
        if( ! isset( self::$PDO ) ) {
            self::$PDO = \woo\base\PDORegistry::getPDO();
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
        list ( $selection, $values ) = $selfact->newSelection( $idobj );
        $stmt = $this->getStatement( $selection );
        $stmt->execute( $values );
        $raw = $stmt->fetchAll();
        return $this->factory->getCollection( $raw );
//        echo "<tt><pre> getStatement - ".print_r( $values, true )."</pre></tt>";
    }

    function insert( DomainObject $obj ) {
        $upfact = $this->factory->getUpdateFactory();
        list( $update, $values ) = $upfact->newUpdate( $obj );
        $stmt = $this->getStatement( $update );
        $stmt->execute( $values );
        if( $obj->getId() < 0) {
            $obj->setId( self::$PDO->lastInsertId() );
        }
        $obj->markClean();
    }
}





try {

    $factory = PersistenceFactory::getFactory( 'Venue' );
    $finder = new DomainObjectAssembler( $factory );
    $idobj = $factory->getIdentityObject()->field('name')->eq('The Likely Lounge');
    $collection = $finder->find( $idobj );
//    $collection = $finder->findOne( $idobj );
//    print $collection->getName();

    foreach ( $collection as $venue ) {
//        echo "<tt><pre> foreach - ".print_r( $venue, true )."</pre></tt>";
        (print $venue->getName() ."<br />");
    }


    echo "<tt><pre> findOne - ".print_r($finder->findOne( $idobj ), true)."</pre></tt>";
    echo "<tt><pre> Factory - ".print_r($factory, true)."</pre></tt>";
    echo "<tt><pre> Finder - ".print_r($finder, true)."</pre></tt>";
    echo "<tt><pre> IdentityObject - ".print_r($idobj, true)."</pre></tt>";
    echo "<tt><pre> Collection - ".print_r($collection, true)."</pre></tt>";



//    $finder2 = PersistenceFactory::getFinder('Venue');
//    $idobj2 = $finder2->getIdentityObject();
//    echo "<tt><pre>".print_r($idobj2, true)."</pre></tt>";



//    $idobj = new VenueIdentityObject2('name' );
//    $idobj->eq('Mosin');
//
//
//    $vsf = new VenueSelectionFactory();
//    $result = $vsf->newSelection( $idobj );

//    echo "<tt><pre> idobj - ".print_r( $idobj, true )."</pre></tt>";
//
//    echo "<tt><pre> vsf - ".print_r( $vsf, true )."</pre></tt>";

//      echo "<tt><pre> result - ".print_r( $result, true )."</pre></tt>";

//
//    $mapper = new VenueMapper();
//    $result = $mapper->find(146);
//    $result->setName("Load max");
////    $venue->setId($result['id']);
//
//    $vuf = new VenueUpdateFactory();
//    $result = $vuf->newUpdate( $result );
//    OW::instance()->preformOperations();
//
//
//
//    echo "<tt><pre> result - ".print_r( $result, true )."</pre></tt>";

//    $idobj2 = new VenueIdentityObject2("name", array("name","id","set","get"));
//    $idobj2->eq('Mosin')->field('id')->lt(133);
//    $idobj2->field('name')->eq('Alexei')->field('id')->lt(144);
//    foreach ($idobj2->getComps() as $key=>$val ) {
//        $result[] = implode(' ', $val);
//    }
//    print " WHERE ". implode(' AND ', $result );
//    print(" WHERE ".$idobj2);


//    $ret = array();
//    foreach ( $idobj2->getComps() as $compdata ) {
////        echo "<tt><pre>".print_r( $compdata, true )."</pre></tt>";
//        $ret[] = "{$compdata['name']} {$compdata['operator']} {$compdata['value']}";
//    }
//    print implode( " AND ", $ret );

//
//    echo "<tt><pre>".print_r( $idobj2->getObjectFields(), true )."</pre></tt>";

//    echo "<tt><pre>".print_r( $idobj2, true )."</pre></tt>";




//    $field = new Field('name');
//    $field->addTest("<",'Alex');
//    $field->addTest(">", 10);
//    if( ! $field->isIncomplete() ) {
////        echo "<tt><pre>".print_r($field->getComps(), true)."</pre></tt>";
//        foreach ($field->getComps() as $key=>$val) {
//                $res[] = implode(' ', $val);
////            echo "<tt><pre>".print_r($val, true)."</pre></tt>";
//        }
//        print " WHERE " . implode(' AND ', $res );
//
//    } else {
//        throw new Exception("Field 'name' is empty");
//    }
////    echo "<tt><pre>".print_r($field, true)."</pre></tt>";





//    $idobj = new VenueIdentityObject();
//    $idobj->setName("alex");
//    $idobj->setId(144);
//
//    $comps = array();
//    $name = $idobj->getName();
//    if( ! is_null( $name ) ) {
//        $comps[] = "name = '{$name}'";
//    }
//    $id = $idobj->getId();
//    if( ! is_null( $id ) ) {
//        $comps[] = "id = {$id}";
//    }
//    $clause = " WHERE ".implode( ' AND ', $comps );
//
//    echo "<tt><pre>".print_r($clause, true)."</pre></tt>";


//
//    $map = new VenueMapper();
//    $DVC = new DefferedVenueCollection(
//        $map,
//        $map->selectStmt() ,
//        array(138)
//    );
//    $DVC->notifyAccess();
//
//    echo "<tt><pre> DVC - ".print_r( $DVC, true )."</pre></tt>";


//    foreach ($DVC as $ven ) {
//        echo "<tt><pre> DVC as ven - ".print_r( $ven, true )."</pre></tt>";
////        OW::instance()->preformOperations();
//        print "Collection: ".$ven->getName()."<br />";
//    }


    /**
 * Invoke Collection inserting
 */

//$collection = HelperFactory::getCollection("Venue");
//$collection->add( new Venue( null, "Loud and Thumping" ) );
//$collection->add( new Venue( null, "Eeezy" ) );
//$collection->add( new Venue( null, "Duck and Badger" ) );
//
//
//foreach ($collection as $venue) {
//    OW::instance()->preformOperations();
//    print "Name: ".$venue->getName()."<br />";
//}
//
//
// echo "<tt><pre> Collection - ".print_r( $collection, true )."</pre></tt>";



/**
 * Вызов identityMap и unit work
 */

//$venMap = new VenueMapper();
//$venDom = new Venue();
//$venDom->setName('Alexxxxx the best');
//$venDom->setName('Alex the best');
//$venMap->insert( $venDom );

//echo "<tt><pre> VenMap1 - ".print_r( $venMap, true )."</pre></tt>";
//echo "<tt><pre> VenDom1 - ".print_r( $venDom, true )."</pre></tt>";

////$start = microtime(true);
//$venDom = $venMap->find( $venDom->getId() );
//$venDom = $venMap->find( 67 );
////$end = microtime(true);
////$result = $end - $start;
////echo "<br />$result<br />";
//
//$venDom->setName('Bobbbbbbbb the best');
//$venMap->update( $venDom );
//
//
//OW::instance()->preformOperations();
////echo "<tt><pre> VenMap2 - ".print_r( $venMap, true )."</pre></tt>";
//echo "<tt><pre> VenDom2 - ".print_r( $venDom, true )."</pre></tt>";




//    $mapper = new \woo\mapper\VenueMapper();
//    $venue = new \woo\domain\Venue();
//    $venue->setName( "The Likely Lounge" );
//
////    OW::instance()->add( $venue );
//    $mapper->insert( $venue );
//    OW::instance()->add( $venue );
//
//    echo $venue->getId();
//    $old = OW::instance()->exists('\woo\domain\Venue', $venue->getId() );
//    if( $old ) {
//        echo "<tt><pre> old1 - ".print_r( $old, true )."</pre></tt>";
//    }
//
//    echo "<tt><pre> OW1 - ".print_r( OW::instance(), true )."</pre></tt>";
//
//
//    $venue = $mapper->find( $venue->getId() );
//    echo "<tt><pre> Find1 - ".print_r( $venue, true )."</pre></tt>";
//
//    $venue->setName( "The Bibble Beer Likely Loung" );
////    $mapper->update( $venue );
//
//
//    $old = OW::instance()->exists('\woo\domain\Venue', $venue->getId() );
//    if( $old ) {
//        echo "<tt><pre> old2 - ".print_r( $old, true )."</pre></tt>";
//    }
//    $mapper->insert( $venue );
//
//    $venue = $mapper->find( $venue->getId() );
//    echo "<tt><pre> Find2 - ".print_r( $venue, true )."</pre></tt>";
//
//    echo "<tt><pre> OW2 - ".print_r( OW::instance(), true )."</pre></tt>";

} catch (Exception $ex ) {
    print $ex->getMessage();
}