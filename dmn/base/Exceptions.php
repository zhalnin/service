<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 21:19
 */

namespace dmn\base;

class AppException extends \Exception {
    private $error;
    private $msg;
    function __construct( $app_error, $msg=null ) {
        if( $msg != null ) {
            $this->msg = ", ".$msg;
        }
        parent::__construct( $app_error );
        $this->error = $app_error;

    }

    function getErrorObject() {
        return "There is Error: $this->error $this->msg";
    }
}

class DBException extends \PDOException {
    private $error;

    function __construct( \PDOException $db_error ) {
        parent::__construct( $db_error->getMessage(), $db_error->getCode() );
        $this->error = $db_error;
    }

    function getErrorObject() {
        return "There is Error: $this->error";
    }
}
?>