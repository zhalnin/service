<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 21:19
 */

namespace imei_service\base;

class AppException extends \Exception {
    private $error;
    function __construct( $app_error ) {
        parent::__construct( $app_error );
        $this->error = $app_error;
    }

    function getErrorObject() {
        return "There is Error: $this->error";
    }
}

class DBException extends \PDOException {
    private $error;

    function __construct( DBException $db_error ) {
        parent::__construct( $db_error->getMessage(), $db_error->getCode() );
        $this->error = $db_error;
    }

    function getErrorObject() {
        return "There is Error: $this->error";
    }
}
?>