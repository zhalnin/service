<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 14:45
 */
namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request    = \dmn\view\VH::getRequest();
    $idc        = $request->getProperty( 'idc' );
    $idp        = $request->getProperty( 'idp' );
    $accounts = $request->getObject( 'accounts' );

    if( is_object( $accounts ) ) {
        // Получаем содержимое текущей страницы
        $account = $accounts->getPage();
    }

// Данные переменные определяют название страницы и подсказку.
    $title = 'Управление аккаунтами';
    $pageinfo = '<p class="help">Здесь можно добавить нового
                    пользователья, удалить или отредактировать
                    данные существующего. Вы не можете узнать
                    пароль существаующего поьзователя, так как
                    его шифрование необратимо, однако вы можете
                    назначить ему новый пароль</p>';
    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Добваить аккаунт
    echo "<a href=?cmd=Accounts&pact=add&page=$_GET[page]
                    title='Добавить новый аккаунт'>
                    Добавить аккаунт</a><br><br>";
    // Выводим ссылки на другие страницы
    echo $accounts;
    echo "<br /><br />";
    ?>
    <table width="100%"
           class="table"
           border="0"
           cellspacing="0"
           cellpadding="0">
        <tr class="header" align="center">
            <td>Пользователь</td>
            <td>Последний визит</td>
            <td>Действия</td>
        </tr>
    <?php

    if( ! empty( $account ) ) {

//        echo "<tt><pre>".print_r($account, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        for($i = 0; $i < count($account); $i++) {
            // Выводим строку таблицы
            echo "<tr>
                    <td align=center>{$account[$i][name]}</td>
                    <td align=center width='150px' >{$account[$i][lastvisit]}</td>
                    <td align=center width='10%' >
                        <a href=#
                            onClick=\"delete_position('".
                            "?cmd=Accounts&pact=del&page=$_GET[page]&".
                            "ida={$account[$i][id_account]}',".
                            "'Вы действительно хотите удалить аккаунт?');\"
                            title='Удалить пользователя'>Удалить<a></td>
                        </tr>";
        }

    }
    echo "</table><br><br>";

    // Выводим ссылки на другие страницы
    echo $accounts;

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