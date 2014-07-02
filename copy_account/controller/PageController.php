<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/12/13
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */

namespace account\controller;

//use account\database\RegistrationManager;

error_reporting(E_ALL & ~E_NOTICE);
ob_start();

require_once("base/Registry.php");
require_once("controller/Request.php");
require_once("database/DataBase.php");
require_once("database/Registration.php");
require_once("database/Login.php");
require_once("database/ResendPass.php");
require_once("database/Activation.php");
require_once("utils/utils_server_name.php");




/**
 * Abstract class
 * Class PageController
 * @package account\controller
 */
abstract class PageController {
    private $request;
    private $session;
    protected $dbh;
    public $fio;
    public $city;
    public $email;
    public $login;
    // for registration manager
    public $reg_mgr;
    // for login manager
    public $login_mgr;
    // for resend forgotten password and login
    public $res_pass_mgr;
    // for activation of user data
    public $activation_mgr;


    /**
     * Constructor
     */
    function __construct() {
        $this->dbh = \account\base\DataBaseRegistry::getDB();
        $request = \account\base\RequestRegistry::getRequest();
        $this->reg_mgr = new \account\database\RegistrationManager();
        $this->login_mgr = new \account\database\LoginManager();
        $this->res_pass_mgr = new \account\database\ResendPassManager();
        $this->activation_mgr = new \account\database\ActivationManager();
//        $this->session = \account\base\SessionRegistry::getSession();
//        echo "<tt><pre>".print_r($this->dbh, true)."</pre></tt>";
        if( is_null( $request ) ) {
            $request = new Request();
        }
        $this->request = $request;

    }

    /*
     * Inherited function
     */
    abstract function process();

    function forward( $resource ) {
        include( $resource );
        exit(0);
    }

    function go( $resource ) {
        $server = serverName().$resource;
        header("Refresh:1; URL=http://".$server);
    }

    /**
     * Return $_REQUEST
     * @return Request| object of class RequestRegistry
     */
    function getRequest() {
        return $this->request;
    }


    /**
     * Check email by regular expression
     * It's being inherited
     * @param $email
     * @return bool
     */
    function checkEmail( $email ) {
        if( preg_match('|[-a-z0-9_\.]+@[-a-z0-9_\.]+\.[a-z]{2,6}|i', $email ) ) {
            return true;
        } else {
            return false;
        }
    }
}


/**
 * Class RegController
 * Check registration
 * @package account\controller
 */
class RegController extends PageController {

    function process(){
        try {
            $request = $this->getRequest();
            $fio = $_POST['fio'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $pass = $_POST['pass'];
            $code = $_POST['code'];

//echo $_POST['code'];
            $code_ses = \account\base\SessionRegistry::getSession('code');

            if( is_null( $request->getProperty('submitted') ) ) {
                $request->addFeedback('Fill the form');
                $this->forward('reg.php');
            } else if( empty( $fio ) ) {
                $request->addFeedback('Fio is required' );
                $this->forward('reg.php');
            } else if( empty( $city ) ) {
                $request->addFeedback('City is required' );
                $this->forward('reg.php');
            } else if(  empty( $email ) ) {
                $request->addFeedback('Email is required' );
                $this->forward('reg.php');
            } else if ( $this->checkEmail( $email ) === false ) {
                $request->addFeedback('Email has incorrect format');
                $this->forward('reg.php');
            } else if( ( $this->reg_mgr->checkEmailExists( array( $email ) ) ) === true ) {
                $request->addFeedback('Email is busy' );
                $this->forward('reg.php');
            } else if( empty( $login ) ) {
                $request->addFeedback('Login is required' );
                $this->forward('reg.php');
            } else if( $this->reg_mgr->checkLoginExists( array( $login ) ) === true ) {
                $request->addFeedback('Login is busy');
                $this->forward('reg.php');
            } else if( empty( $pass ) ) {
                $request->addFeedback('Pass is required' );
                $this->forward('reg.php');
            } else if( $code !== $code_ses ) {
                $request->addFeedback('Code in capcha is not correct');
                $this->forward('reg.php');
            }

            $this->forward('reg_handler.php');
        } catch ( \Exception $e ) {
            echo "Error:  {$e->getMessage()}";
        } catch ( \PDOException $e ) {
            echo "Error: {$e->getMessage()}";
        }
    }
}


/**
 * Class IndController
 * Check autorization
 * @package account\controller
 */
class IndController extends PageController {
    protected $activation;


    function process() {
        try {

            $request = $this->getRequest();

            $ip = getenv('http_x_forwarded_for');
            if( empty( $ip ) || $ip == 'unknown' ) {
                $ip = getenv('remote_addr');
            }
//        echo "<tt><pre>".print_r( $request,true)."</pre></tt>";
            $login = $_POST['login'];
            $pass_u = $_POST['pass'];
            $pass = md5( $pass_u );
            $auto = $_POST['auto'];
            if( is_null( $request->getProperty('submitted') ) ) {
                $request->addFeedback(" Fill the form to enter to site");
                $this->forward('ind.php');
            } else if( $this->login_mgr->checkOshibka( array( $ip ) ) === true ) {
                $request->addFeedback("You enter incorrect login and password over 3 times. <br />
                    You should wait 15 minutes and try again");
                $this->forward('ind.php');
            } else if( empty( $login ) ) {
                $request->addFeedback( "Login is required" );
                $this->forward( 'ind.php' );
            } else if( empty( $pass_u ) ) {
                $request->addFeedback( "Password is required" );
                $this->forward( "ind.php" );
            } else if( $this->login_mgr->checkLogPassExists( array( $login, $pass ) ) === false ) {
                $request->addFeedback( "Login or Pass doesn't match");
                $this->login_mgr->insertOshibka( array( $ip )  );
                $this->forward( "ind.php" );
            } else if( $this->login_mgr->checkLogActivation( array( $login, $pass ) ) === false ) {
                $request->addFeedback( "Your account didn't activate else<br />
            <a href=\"sendMail.php?login={$login}&pass={$pass_u}\" >Send activation email</a>" );
                $this->forward( "ind.php" );
            } else {

                \account\base\SessionRegistry::setSession('login',$login);
                \account\base\SessionRegistry::setSession('pass',$pass);
                \account\base\SessionRegistry::setSession('auto',1);
                if( isset( $auto ) ) {
                    setcookie( "auto", "yes", time()+9999999 );
                    setcookie( "login", $login, time()+9999999 );
                    setcookie( "password", $pass_u, time()+9999999 );
//                    \account\base\SessionRegistry::setSession('auto', $auto);
                }
//                $this->forward( "ind_site.php" );
                $this->go("ind_site.php");
            }
        } catch ( \Exception $e ) {
            echo "Error:  {$e->getMessage()}";
        } catch ( \PDOException $e ) {
            echo "Error: {$e->getMessage()}";
        }
    }
}





/**
 * Class ResendPassController
 * Check email for resend password if user has forget his password
 * @package account\controller
 */
class ResendPassController extends PageController {
    public $user_data = array();

    function process() {
        try {
            $request = $this->getRequest();
            $submitted = $_POST['submitted'];
            $email = $_POST['email'];
//        echo "<tt><pre>".print_r($request,true)."</pre></tt>";
            if( ! isset( $submitted ) ) {
                $request->addFeedback('Enter email, you type while register');
                $this->forward('resend_pass.php');
            } else if( empty( $email ) ) {
                $request->addFeedback('Enter your email');
                $this->forward( "resend_pass.php" );
            } else if(  $this->checkEmail( $email ) === false ) {
                $request->addFeedback('Enter correct email format' );
                $this->forward( 'resend_pass.php' );
            } else if( $this->res_pass_mgr->checkEmailExists( array( $email ) ) === false ) {
                $request->addFeedback('This email not found');
                $this->forward( 'resend_pass.php' );
            } else if( $this->res_pass_mgr->checkEmailActivate( array( $email ) ) === false ) {
                $request->addFeedback( "Your account didn't activate else<br />
            <a href=\"sendMail.php?email={$email}\" >Send activation email</a>" );
                $this->forward( 'resend_pass.php' );
            } else {
                $this->forward( 'sucess_reset_pass.php' );
            }
        } catch ( \Exception $e ) {
            echo "Error:  {$e->getMessage()}";
        } catch ( \PDOException $e ) {
            echo "Error: {$e->getMessage()}";
        }
    }
}



class ActivationController extends PageController {
    function process() {
        try {
            $request = $this->getRequest();
            $login = $_POST['login'];
            $code = $_POST['code'];
            $ud = array($login, $code);
            if( ( ! isset( $login ) ) && ( ! isset( $code ) ) ) {
                $request->addFeedback('You enter not valid email');
                $this->forward( 'resend_pass.php' );
            } else if( $this->activation_mgr->checkUserExists( array( $ud ) ) === false ){
                $request->addFeedback( 'The user does not exist' );
                $this->forward( 'resend_pass.php' );
            } else if( $this->activation_mgr->checkUserActivate( array( $ud ) ) === true ) {
                $request->addFeedback( 'Your account is activated already' );
                $this->forward( 'resend_pass.php' );
            } else  {
                $this->forward( 'activation.php' );
            }
        } catch ( \Exception $e ) {
            echo "Error:  {$e->getMessage()}";
        } catch ( \PDOException $e ) {
            echo "Error: {$e->getMessage()}";
        }
    }

//    function checkUserExists( array $ud ) {
//        $STH = $this->dbh->prepare("SELECT COUNT(*) FROM account WHERE login=? AND activation=?");
//        $STH->execute($ud);
//        if( $STH->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    function checkUserActivate( array $ud ) {
//        $STH = $this->dbh->prepare("SELECT * FROM account WHERE login=? AND activation=? AND status=1");
//        $STH->execute( $ud );
//        if( $STH->fetchColumn() > 0 ) {
//            return true;
//        } else {
//            return false;
//        }
//    }
}

class LogoutController extends PageController {
    function process() {
//        $login = \account\base\SessionRegistry::getSession('login');
//        $pass = \account\base\SessionRegistry::getSession('password');
        setcookie('login',"",time() - 3600 );
        setcookie('password',"",time() - 3600 );
        setcookie('auto',"",time() - 3600 );
        \account\base\SessionRegistry::setSession('auto',"0");
        $this->go('ind_controller.php');
    }
}
ob_get_flush();
?>