<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 19:14
 */
//header( "Location: system_news/index.php" );

try {
    require_once( "dmn/controller/Controller.php" );
    require_once( "dmn/base/Exceptions.php" );


    define( 'AZ', true ); // определяем основную константу

    dmn\Controller\controller::run();


} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>