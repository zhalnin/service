<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 18:37
 */
namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request    = \dmn\view\VH::getRequest();
    $idc        = intval( $request->getProperty( 'idc' ) );
    $idp        = intval( $request->getProperty( 'id' ) );
    $users      = $request->getObject( 'users' );
    $page       = intval( $request->getProperty( 'page' ) );

    if( is_object( $users ) ) {
        // Получаем содержимое текущей страницы
        $user = $users->getPage();
    }

// Данные переменные определяют название страницы и подсказку
    $title = 'Управление пользователями';
    $pageinfo = '<p class=help>Данная страница позволяет
                управлять регистрационной информацией
                зарегистрированных пользователей</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Добваить аккаунт
    echo "<a href=?cmd=Users&pact=add&page=$_GET[page]
                    title='Добавить пользователя'>
                    Добавить пользователя</a><br><br>";

    // подключаем скрипт с фильтром
    include( 'dmn/view/utils/userFilter.php' );

    // Выводим ссылки на другие страницы
    echo $users;
    echo "<br /><br />";
    ?>
    <table width="100%"
           class="table"
           border="0"
           cellpadding="0"
           cellspacing="0">
        <tr class="header"
            align="center">
            <td align="center"
                width="120">Дата регистрации</td>
            <td align="center">Ник</td>
            <td align="center">E-mail</td>
            <td align="center"
                width="50">Действия</td>
        </tr>
    <?php

    if( ! empty( $user ) ) {
//        echo "<tt><pre>".print_r($user, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        for($i = 0; $i < count($user); $i++) {
            $url = "&idp={$user[$i]['id']}&page=$page";
            // Выясняем, заблокирован пользователь или нет
            $colorrow = "";
            $colorstatus = "";
            if( $user[$i]['block'] == 'block' ) {
                $blk = "<a href=?cmd=Users&ppos=unblock$url
                        title='Разблокировать пользователя'>
                        Разблокировать</a>";
                $colorrow = "class='hiddenrow'";
            } else {
                $blk = "<a href=?cmd=Users&ppos=block$url
                        title='Заблокировать пользователя'>
                        Блокировать</a>";
            }
            if( $user[$i]['status'] == 0 ) {
                $activate = "<a href=?cmd=Users&ppos=activate$url
                        title='Активировать пользователя'>
                        Активировать</a>";
                $colorstatus = "class='hiddenstatus'";
            } else {
                $activate = "<a href=?cmd=Users&ppos=deactivate$url
                        title='Деактивировать пользователя'>
                        Деактивировать</a>";
            }
            // Преобразуем дату регистрации
            list($date,$time)   = explode(" ", $user[$i]['putdate']);
            list($year, $month, $day) = explode("-", $date);
            $time = substr($time, 0, 5);
            // Выводим строку таблицы
            // Выводим позицию
            echo "<tr $colorrow>
                    <td align=center>$day.$month.$year $time</td>
                    <td align=center $colorstatus>
                            <a href=#
                                onclick=\"show_detail('?cmd=Users&pact=detail&".
                        "idp={$user[$i][id]}', 400,350);".
                        "return false\"   >".
                        htmlspecialchars($user[$i]['fio'])."</a></p></td>
                    <td align=center>
                            <a href=mailto:".htmlspecialchars($user[$i]['email']).">".
                        htmlspecialchars($user[$i]['email'])."</a></td>
                    <td align=center>
                        $blk<br>
                        $activate<br>
                        <a href=?cmd=Users&pact=edit&$url>Редактировать</a><br>
                        <a href=# onclick=\"delete_position('?cmd=Users&pact=del$url',".
                    "'Вы действительно хотите удалить пользователя?');\">Удалить</a></td>
                </tr>";
        }

    }
    echo "</table><br><br>";
    // Выводим ссылки на другие страницы
    echo $users;

    // Включаем завершение страницы
    require_once("dmn/view/templates/bottom.php");
} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>