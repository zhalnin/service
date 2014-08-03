<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 17:51
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
if( ! defined( 'Users' ) ) die();
require_once( "dmn/view/ViewHelper.php" );

$title          = "Подробная информация";
$request        = VH::getRequest();
$usersDetail    = $request->getObject( 'usersDetail' );
$idp            = intval( $request->getProperty( 'idp' ) );
$fio            = $usersDetail->getFio();
$city           = $usersDetail->getCity();
$email          = $usersDetail->getEmail();
$url            = $usersDetail->getUrl();
$login          = $usersDetail->getLogin();
$status         = $usersDetail->getStatus();
$pass           = $usersDetail->getPass();
$putdate        = $usersDetail->getPutdate();
$lastvisit      = $usersDetail->getLastvisit();
$block          = $usersDetail->getBlock();


?>

<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="dmn/view/css/cms.css"></head>
<body leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0"
      bottommargin="0" topmargin="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0"
       height="100%" class="text">
    <tr valign="top">
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td width="0">&nbsp;</td>
        <td class=main height="100%">
            <table width="100%" border="0" cellpadding="0" cellspacing="0"
                   class="table">
                <tr class="header" align="center">
                    <td>Параметр</td>
                    <td>Значение</td>
                </tr>
                <?php
                try {

                    if( ! empty( $fio ) ) {
                        echo "<tr><td align=right>Имя</td><td>".htmlspecialchars( $fio )."</td></tr>";
                    }
                    if( ! empty( $city ) ) {
                        echo "<tr><td align=right>Город</td><td>".htmlspecialchars( $city )."</td></tr>";
                    }
                    if( ! empty( $login ) ) {
                        echo "<tr><td align=right>Логин</td><td>".htmlspecialchars( $login )."</td></tr>";
                    }
                    if( ! empty( $pass ) ) {
                        echo "<tr><td align=right>Пароль</td><td>".htmlspecialchars( $pass )."</td></tr>";
                    }
                    if( ! empty( $email ) ) {
                        echo "<tr><td align=right>E-mail</td><td><a href=mailto:".htmlspecialchars( $email ).">".
                            htmlspecialchars( $email )."</a></td></tr>";
                    }
                    if( ! empty( $url ) ) {
                        echo "<tr><td align=right>Сайт</td><td>".htmlspecialchars( $url )."</td></tr>";
                    }
                    if( isset( $status ) ) {
                        if( $status == 1 )
                            $statusActiv = 'Да';
                        else
                            $statusActiv = 'Нет';
                        echo "<tr><td align=right>Активирован</td><td>$statusActiv</td></tr>";
                    }
                    if( ! empty( $block ) ){
                        if( $block == 'block' )
                            $statususer = 'Да';
                        else
                            $statususer = 'Нет';
                        echo "<tr>
                    <td align=right>Заблокирован</td>
                    <td>$statususer</td>
                 </tr>";
                    }
                    if( ! empty( $putdate ) ) {
                        // Преобразуем дату регистрации
                        list( $date, $time ) = explode( " ",$putdate );
                        list( $year,$month,$day ) = explode( "-", $date );
                        $time = substr( $time, 0, 5 );
                        echo "<tr>
                    <td align=right>Дата регистрации</td>
                    <td>$day.$month.$year $time</td>
                 </tr>";
                    }
                    if( ! empty( $lastvisit ) ) {
                        // Преобразуем дату последнего визита
                        list( $date, $time ) = explode( " ", $lastvisit );
                        list( $year, $month, $day ) = explode("-",$date);
                        $time = substr( $time, 0, 5 );
                        echo "<tr>
                    <td align=right>Дата последнего визита</td>
                    <td>$day.$month.$year $time</td>
                 </tr>";
                    }

            echo "</table><br /><br />";

            } catch( \dmn\base\AppException $exc) {
                    echo $exc->getErrorObject();
            }
                ?>

        </td>
        <td width="10">&nbsp;</td>
    </tr>
    <tr class=authors>
        <td colspan="3"></td>
    </tr>
</table>
</body>
</html>

