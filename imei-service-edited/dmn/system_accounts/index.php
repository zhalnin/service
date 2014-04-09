<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 28.03.12
 * Time: 23:58
 * To change this template use File | Settings | File Templates.
 */


    // Устанавливаем соединение с базой данных
//    require_once("../../config/config.php");
    // Подключаем блок авторизации
    require_once("../utils/security_mod.php");
    // Подключаем FrameWork
    require_once("../../config/class.config.dmn.php");


    // Данные переменные определяют название страницы и подсказку.
    $title = 'Управление аккаунтами';
    $pageinfo = '<p class="help">Здесь можно добавить нового
                    пользователья, удалить или отредактировать
                    данные существующего. Вы не можете узнать
                    пароль существаующего поьзователя, так как
                    его шифрование необратимо, однако вы можете
                    назначить ему новый пароль</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    try
    {
        // Количество ссылок в постраничной навигации
        $page_link = 3;
        // Количество позиций на страниц
        $pnumber = 10;
        // Объявляем объект постраничной навигации
        $obj = new PagerMysql($tbl_accounts,
                                "",
                                "ORDER BY name",
                                $pnumber,
                                $page_link);

        // Добваить аккаунт
        echo "<a href=addaccount.php?page=$_GET[page]
                    title='Добавить новый аккаунт'>
                    Добавить аккаунт</a><br><br>";

        // Получаем содержимое текущей страницы
        $accounts = $obj->get_page();
        // Если имеется хотя бы одна запись - выводим ее
        if(!empty($accounts))
        {
            ?>
                <table width="100%"
                       class="table"
                       border="0"
                       cellspacing="0"
                       cellpadding="0">
                    <tr class="header" align="center">
                        <td>Пользователь</td>
                        <td>Действия</td>
                    </tr>
            <?php
                for($i = 0; $i < count($accounts); $i++)
                {
                    // Выводим строку таблицы
                    echo "<tr>
                            <td align=center>{$accounts[$i][name]}</td>
                            <td align=center>
                                <a href=#
                                    onClick=\"delete_position('".
                                    "delaccount.php?page=$_GET[page]&".
                                    "id_account={$accounts[$i][id_account]}',".
                                    "'Вы действительно хотите удалить аккаунт?');\"
                                    title='Удалить пользователя'>Удалить<a></td>
                          </tr>";
                }
                echo "</table><br>";
        }
        // Выводим ссылки на другие страницы
        echo $obj;


    }
    catch(ExceptionMySQL $exc)
    {
        require("../utils/exception_mysql.php");
    }

    // Включаем завершение страницы
    require_once("../utils/bottom.php");
?>