<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/07/14
 * Time: 15:47
 */

namespace dmn\view;

error_reporting(E_ALL & ~E_NOTICE);

// Include parent class
//require_once( "dmn/classes/class.PagerMysqlTwoTables.php" );
//require_once( "dmn/classes/class.Field.php" );
//require_once( "dmn/classes/class.FieldText.php" );
//require_once( "dmn/classes/class.FieldTextarea.php" );
//require_once( "dmn/classes/class.FieldDateTime.php" );
//require_once( "dmn/classes/class.FieldCheckbox.php" );
//require_once( "dmn/classes/class.FieldFile.php" );
//require_once( "dmn/classes/class.FieldHidden.php" );
//require_once( "dmn/classes/class.FieldHiddenInt.php" );
//require_once( "dmn/classes/class.Form.php" );

// Include exception for error handling
require_once( "dmn/classes.php" );
require_once( "dmn/base/Exceptions.php" );


try {

    print date( "Y-m-d H:i:s", '1386755290' );




    function pName(){
        return "dfjdkjfkdf";
    }
//    print pName();

} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}

?>