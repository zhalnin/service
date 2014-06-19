<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09/12/13
 * Time: 19:29
 * To change this template use File | Settings | File Templates.
 */

namespace account\database;

use account\base\DataBaseRegistry;

error_reporting(E_ALL & ~E_NOTICE);

//require_once( "account/database/Registration.php" );
//require_once("account/base/Registry.php");

class DataBase {
    protected static $PDO;
    static $stmt = array();
    static $checkEmailExists = "SELECT COUNT(*) FROM account WHERE email=?";

    function __construct() {
        if( ! isset( self::$PDO ) ) {
            $options = array( 'dsn'=>'mysql:host=localhost;dbname=talking;charset=utf8',
                            'user'=>'root',
                            'password'=>'zhalnin5334',
                            'options'=>array(

                                \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
                                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',
                                \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC
                            ) );
            self::$PDO = new \PDO( $options['dsn'],
                                $options['user'],
                                $options['password'],
                                $options['options']);
            self::$PDO->exec('SET CHARACTER SET utf8');
            \account\base\DataBaseRegistry::setDB(self::$PDO);
        }
//        \account\base\DataBaseRegistry::setDB(self::$PDO);
    }

    function prepareStmt( $stmt ) {
        if( isset( self::$stmt[$stmt] ) ) {
            return self::$stmt[$stmt];
        } else {
            $stmt_handle = self::$PDO->prepare($stmt);
            self::$stmt[$stmt] = $stmt_handle;
            return $stmt_handle;
        }
    }

    protected function doStatement( $stmt, $value ) {
//        echo "<tt><pre>$stmt  - ".print_r($value,true)."</pre></tt>";
        $sth = $this->prepareStmt( $stmt );
        $sth->closeCursor();
        $db_result = $sth->execute( $value );
        return $sth;
    }
//
//
//    function __get( $name ) {
//        return self::$PDO->$name;
//    }
//
//    function __set( $name, $value ) {
//        self::$PDO->$name = $value;
//    }
//
//    function __call( $method, $args ) {
//        call_user_func_array( array($this->PDO, $method ), $args );
//    }

    function checkEMailExists( array $email ) {
        $result = $this->doStatement( self::$checkEmailExists, $email );
        if( $row = $result->fetchColumn() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

}



//class RegistrationManager extends DataBase {
//
//    static $checkLoginExists = "SELECT COUNT(*) FROM account WHERE login=?";
//
//
//    function checkLoginExists( array $login ) {
//        $result = $this->doStatement(self::$checkLoginExists, $login );
//        if( $result->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//}




//class LoginManager extends DataBase {
//
//    static $deleteStmt = "DELETE FROM oshibka WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 900";
//    static $countStmt = "SELECT COUNT(*) FROM oshibka WHERE ip=?";
//    static $selectColStmt = "SELECT col FROM oshibka WHERE ip=?";
//    static $selectIPStmt = "SELECT ip FROM oshibka WHERE ip=?";
//    static $updateStmt = "UPDATE oshibka SET col=?";
//    static $insertStmt = "INSERT INTO oshibka (ip, date, col) VALUES (?,?,?)";
//    static $countLogPassStmt = "SELECT COUNT(*) FROM account WHERE login=? AND pass=?";
//    static $countLogActivationStmt = "SELECT COUNT(*) FROM account WHERE login=? AND pass=? AND status=1";
//    static $selectLogActivationStmt = "SELECT * FROM account WHERE login=? AND pass=? AND status=0";
//
//
//    function helpCountOshibka( array $ip ) {
//        $sthc = $this->doStatement( self::$countStmt,  $ip );
//        return $sthc;
//    }
//
//    function helpSelectIPOshibka( array $ip ) {
//        $sths = $this->doStatement( self::$selectIPStmt, $ip );
//        return $sths;
//    }
//
//    function helpSelectColOshibka( array $ip ) {
//        $sths = $this->doStatement( self::$selectColStmt, $ip );
//        return $sths;
//    }
//
//
//    function helpUpdateOshibka( $col ) {
//        $this->doStatement( self::$updateStmt, $col );
//    }
//
//    function helpInsertOshibka( array $args ) {
//        $this->doStatement( self::$insertStmt, $args );
//    }
//
//    function helpCountLogActivation( array $args ) {
//        $sthc = $this->doStatement( self::$countLogActivationStmt, $args );
//        return $sthc;
//    }
//
//    function helpSelectLogActivation( array $args ) {
//        $sths = $this->doStatement( self::$selectLogActivationStmt, $args );
//        return $sths;
//    }
//
//
//    /**
//     * Check if user enter incorrect data over 3 times and wait 15 minutes
//     * @param $ip
//     * @return bool
//     */
//    function checkOshibka( array $ip ) {
//        $this->doStatement( self::$deleteStmt, $ip );
//        $sthc = $this->helpCountOshibka( $ip );
//        if( $sthc->fetchColumn() > 0 ) {
//            $sths = $this->helpSelectColOshibka( $ip );
//            while( $row = $sths->fetch() ) {
//                if( $row['col'] > 2 ) {
//                    return true;
//                }
//                return false;
//            }
//        } else {
//            return false;
//        }
//    }
//
//
//    /**
//     * If user enter incorrect data, insert or update mistake into database
//     * @param $ip
//     */
//    function insertOshibka( array $ip ) {
//        $sthc = $this->helpCountOshibka(  $ip );
//        if( $sthc->fetchColumn() > 0 ) {
//            $sths = $this->helpSelectIPOshibka( $ip );
//            while( $row = $sths->fetch() ) {
//
//                echo "<tt><pre>".print_r($ip[0],true)."</pre></tt>";
//                if( $ip[0] == $row['ip'] ) {
//                    $sthr = $this->helpSelectColOshibka( $ip );
//                    while( $rows = $sthr->fetch() ) {
//                        $col = $rows['col'] + 1;
//                        $this->helpUpdateOshibka( array( $col) );
//                    }
//                }
//            }
//        } else {
//            $this->helpInsertOshibka( array($ip[0], date('Y-m-d H:i:s'), "1") );
//        }
//    }
//
//    /**
//     * Check if count oflogin and password exists
//     * @param $login
//     * @param $pass
//     * @return bool
//     */
//    function checkLogPassExists( array $args ) {
//        $sthc = $this->doStatement( self::$countLogPassStmt, $args );
//        if( $sthc->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    /**
//     * Check if User have activated his account
//     * @param $login
//     * @param $pass
//     * @return bool
//     */
//    function checkLogActivation( array $args ) {
//        $sthc = $this->helpCountLogActivation( $args );
//        if( $sthc->fetchColumn() < 1 ) {
//            $sths = $this->helpSelectLogActivation( $args );
//            while( $row = $sths->fetch() ) {
//                $this->activation = $row['activation'];
//            }
//            return false;
//        } else {
//            return true;
//        }
//    }
//}



//class ResendPassManager extends DataBase {
//
//    static $countEmailActivate = "SELECT COUNT(*) FROM account WHERE email=? AND status=1";
//    static $selectExistsAccount = "SELECT * FROM account WHERE email=?";
//    static $updateAccount = "UPDATE account SET fio=?,city=?,email=?,login=?,pass=?,activation=? WHERE id=? AND email=?";
//
//    function helperCountEmailActivate( $email ) {
//        $sthc = $this->doStatement( self::$countEmailActivate, $email );
//        return $sthc;
//    }
//
//    function helperSelectExistsAccount( $email ) {
//        $sths = $this->doStatement( self::$selectExistsAccount, $email );
//        return $sths;
//    }
//
//    function helperUpdateAccount( $args ) {
//        $sthu = $this->doStatement( self::$updateAccount, $args );
//    }
//
//    function checkEmailActivate( array $email ) {
//        if( $this->helperCountEmailActivate( $email )->fetchColumn() > 0 ) {
//            $sths = $this->helperSelectExistsAccount( $email );
//            while( $row = $sths->fetch() ) {
//                $id = $row['id'];
//                $fio = $row['fio'];
//                $login = $row['login'];
//                $city = $row['city'];
//                $email = $row['email'];
//                $pass_u = "password".time();
//                $pass = md5( "password".time() );
//                $activation = md5( $email.time() );
//                $this->helperUpdateAccount( array( $fio, $city, $email,$login, $pass, $activation, $id, $email ) );
//            }
//            $server = serverName();
//            $subject    = "Reset Password";
//            $message    = "You reset your password successfuly!<br />
//                Your login: $login<br />
//                Your password: $pass_u<br />
//                You can visit site with your account <a href=".$server."ind.php>Enter site</a>";
//            $header = "Content-type:text/html; charset=utf-8";
//            mail($email, $subject, $message, $header );
//            return true;
//        } else {
//            return false;
//        }
//    }
//}
//


//class ActivationManager extends DataBase {
//    static $countUserExists = "SELECT COUNT(*) FROM account WHERE login=? AND activation=?";
//    static $countUserActivate = "SELECT * FROM account WHERE login=? AND activation=? AND status=1";
//
//    function checkUserExists( array $ud ) {
//        if( $this->doStatement( self::$countUserExists, $ud )->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    function checkUserActivate( array $ud ) {
//        if( $this->doStatement( self::$countUserActivate, $ud )->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//}

new DataBase();
?>