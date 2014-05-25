<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 29/12/13
 * Time: 00:11
 * To change this template use File | Settings | File Templates.
 */

namespace woo\base;

class AppException extends \Exception {
    function __construct( $message  ){
        parent::__construct( $message );
    }

    function __toString() {
        return "MESSAGE ERROR: {$this->message} HAS CODE OF ERROR: {$this->code} <br/>FILE ERROR: {$this->file} IN LINE: {$this->line}";
    }
}

class DBExceptions extends \Exception {
    private $error;
    function __construct( $error ) {
        parent::__construct( $error->getMessage(), $error->getCode() );
        $this->error = $error;
    }

    function getErrorObject() {
        return $this->error;
    }
}

?>