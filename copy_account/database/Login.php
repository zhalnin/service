<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/01/14
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */

namespace account\database;

class LoginManager extends DataBase {

    static $deleteStmt = "DELETE FROM oshibka WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 900";
    static $countStmt = "SELECT COUNT(*) FROM oshibka WHERE ip=?";
    static $selectColStmt = "SELECT col FROM oshibka WHERE ip=?";
    static $selectIPStmt = "SELECT ip FROM oshibka WHERE ip=?";
    static $updateStmt = "UPDATE oshibka SET col=?";
    static $insertStmt = "INSERT INTO oshibka (ip, date, col) VALUES (?,?,?)";
    static $countLogPassStmt = "SELECT COUNT(*) FROM account WHERE login=? AND pass=?";
    static $countLogActivationStmt = "SELECT COUNT(*) FROM account WHERE login=? AND pass=? AND status=1";
    static $selectLogActivationStmt = "SELECT * FROM account WHERE login=? AND pass=? AND status=0";


    function helpCountOshibka( array $ip ) {
        $sthc = $this->doStatement( self::$countStmt,  $ip );
        return $sthc;
    }

    function helpSelectIPOshibka( array $ip ) {
        $sths = $this->doStatement( self::$selectIPStmt, $ip );
        return $sths;
    }

    function helpSelectColOshibka( array $ip ) {
        $sths = $this->doStatement( self::$selectColStmt, $ip );
        return $sths;
    }


    function helpUpdateOshibka( $col ) {
        $this->doStatement( self::$updateStmt, $col );
    }

    function helpInsertOshibka( array $args ) {
        $this->doStatement( self::$insertStmt, $args );
    }

    function helpCountLogActivation( array $args ) {
        $sthc = $this->doStatement( self::$countLogActivationStmt, $args );
        return $sthc;
    }

    function helpSelectLogActivation( array $args ) {
        $sths = $this->doStatement( self::$selectLogActivationStmt, $args );
        return $sths;
    }


    /**
     * Check if user enter incorrect data over 3 times and wait 15 minutes
     * @param $ip
     * @return bool
     */
    function checkOshibka( array $ip ) {
        $this->doStatement( self::$deleteStmt, $ip );
        $sthc = $this->helpCountOshibka( $ip );
        if( $sthc->fetchColumn() > 0 ) {
            $sths = $this->helpSelectColOshibka( $ip );
            while( $row = $sths->fetch() ) {
                if( $row['col'] > 2 ) {
                    return true;
                }
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * If user enter incorrect data, insert or update mistake into database
     * @param $ip
     */
    function insertOshibka( array $ip ) {
        $sthc = $this->helpCountOshibka(  $ip );
        if( $sthc->fetchColumn() > 0 ) {
            $sths = $this->helpSelectIPOshibka( $ip );
            while( $row = $sths->fetch() ) {

//                echo "<tt><pre>".print_r($ip[0],true)."</pre></tt>";
                if( $ip[0] == $row['ip'] ) {
                    $sthr = $this->helpSelectColOshibka( $ip );
                    while( $rows = $sthr->fetch() ) {
                        $col = $rows['col'] + 1;
                        $this->helpUpdateOshibka( array( $col) );
                    }
                }
            }
        } else {
            $this->helpInsertOshibka( array($ip[0], date('Y-m-d H:i:s'), "1") );
        }
    }

    /**
     * Check if count oflogin and password exists
     * @param $args
     * @return bool
     */
    function checkLogPassExists( array $args ) {
        $sthc = $this->doStatement( self::$countLogPassStmt, $args );
        if( $sthc->fetchColumn() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if User have activated his account
     * @param $args
     * @return bool
     */
    function checkLogActivation( array $args ) {
        $sthc = $this->helpCountLogActivation( $args );
        if( $sthc->fetchColumn() < 1 ) {
            $sths = $this->helpSelectLogActivation( $args );
            while( $row = $sths->fetch() ) {
                $this->activation = $row['activation'];
            }
            return false;
        } else {
            return true;
        }
    }
}
?>