<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/05/14
 * Time: 20:19
 */

namespace imei_service\controller;

require_once( "imei_service/base/Registry.php" );
require_once( "imei_service/base/Exceptions.php" );
require_once( "imei_service/controller/AppController.php" );
require_once( "imei_service/command/Command.php" );

class ApplicationHelper {
    private static $instance;
    private $config = "imei_service/data/imei_service_options.xml";

    private function __construct(){}

    static function instance() {
        if( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function init() {
        $dsn = \imei_service\base\ApplicationRegistry::getDSN();
        if( ! is_null( $dsn ) ) {
            return;
        }
        $this->getOptions();
    }

    private function getOptions() {
        $this->ensure( file_exists( $this->config ), "Could not find options file 'xml' in ApplicationHelper.php" );
        $options = @SimpleXml_load_file( $this->config );
        $this->ensure( $options instanceof \SimpleXMLElement, "Could not resolve options file in ApplicationHelper.php" );
        $dsn = (string)$options->dsn;
        $this->ensure( $dsn, "No DSN found in ApplicationHelper.php" );
        \imei_service\base\ApplicationRegistry::setDSN( $dsn );
        $map = new ControllerMap();

        foreach( $options->control->view as $default_view ) {
            $stat_str = trim( $default_view['status'] );
            $status = \imei_service\command\Command::statuses( $stat_str );
            $map->addView( 'default', $status, (string)$default_view );
        }

        \imei_service\base\ApplicationRegistry::setControllerMap( $map );
    }

    private function ensure( $stmt, $msg ) {
        if( ! $stmt ) {
            throw new \imei_service\base\AppException( $msg );
        }
    }
}
?>