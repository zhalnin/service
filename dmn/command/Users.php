<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 18:06
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'AZ' ) ) die();
define( 'Users', true );

require_once( "dmn/command/Command.php" );
require_once( "dmn/classes/class.PagerMysql.php" );
require_once( 'dmn/domain/Users.php' );


class Users extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $page_link  = 3; // Количество ссылок в постраничной навигации
        $pnumber    = 10; // Количество позиций на страниц
        $position   = $request->getProperty( 'ppos' ); // перемещение, сокрытие/отображение позиции
        $action     = $request->getProperty( 'pact' ); // действие над позицией аккаунта
        $page       = intval( $request->getProperty( 'page' ) );
        $idp        = intval( $request->getProperty( 'idp' ) );



        // Устанавливаем фильтр
        $begin = "";
        if( ! empty( $_POST['chk_begin'] ) ) {
            $begin = mktime( $_POST['b_date_hour'],
                $_POST['b_date_minute'],
                0,
                $_POST['b_date_month'],
                $_POST['b_date_day'],
                $_POST['b_date_year'] );
        }
        $end = "";
        if( ! empty( $_POST['chk_end'] ) ) {
            $end = mktime( $_POST['e_date_hour'],
                $_POST['e_date_minute'],
                0,
                $_POST['e_date_month'],
                $_POST['e_date_day'],
                $_POST['e_date_year'] );
        }


        // в зависимости от действия вызываем метод с
        // определенными параметрами для выполнения действия над
        // позицией в блоке
        if( ! empty( $position ) ) {
            \dmn\domain\Users::position( $idp, $position );
            $this->reloadPage( 0, "dmn.php?cmd=Users&page={$page}" );
        }

        if( ! empty( $action ) ) {
            switch( $action ) {
                case 'add':
                    return self::statuses( 'CMD_ADD');
                    break;
                case 'edit':
                    return self::statuses( 'CMD_EDIT');
                    break;
                case 'del':
                    return self::statuses( 'CMD_DELETE');
                    break;
                case 'detail':
                    return self::statuses( 'CMD_DETAIL');
                    break;
            }
        }



        $url = "&begin_date=$_GET[begin_date]".
            "&end_date=$_GET[end_date]";

        $where = "WHERE 1=1";
        if( ! empty( $begin ) ) {
            $where .= " AND putdate >= '".
                date( "Y-n-d H:i:s", $begin )."'";
        }
        if( ! empty( $end ) ) {
            $where .= " AND putdate <= '".
                date( "Y-n-d H:i:s", $end ). "'";
        }

        // Объявляем объект постраничной навигации
        $users = new \dmn\classes\PagerMysql('system_account',
            $where,
            " ORDER BY putdate DESC",
            $pnumber,
            $page_link,
            $url);

//        echo "<tt><pre>".print_r($where, true)."</pre></tt>";
        if( is_object( $users ) ) {
            $request->setObject( 'users', $users );
        }

        return self::statuses('CMD_OK'); // передаем статус выполнения и далее смотрим переадресацию

    }
}

?>