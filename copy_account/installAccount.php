<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10/12/13
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);

require_once(dirname(__FILE__)."/database/DataBase.php");
require_once(dirname(__FILE__)."/base/Registry.php");




try {
    $createDBQ = "CREATE TABLE IF NOT EXISTS account (
                id INTEGER AUTO_INCREMENT PRIMARY KEY,
                fio TEXT,
                city TINYTEXT,
                email TEXT,
                login TINYTEXT,
                pass TEXT)CHARACTER SET utf8 COLLATE utf8_general_ci";
    if( ! $createDBQ ) {
        throw new Exception( "Error in query - CREATE TABLE" );
    }

    $DBH = DataBaseRegistry::getDB();
    $STH = $DBH->prepare($createDBQ);
    $STH->execute();
} catch( PDOException $e ) {
    echo $e->getMessage();
} catch( Exception $e ) {
    echo $e->getMessage();
}




?>