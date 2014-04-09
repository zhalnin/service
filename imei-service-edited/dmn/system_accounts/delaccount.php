<?php
ob_start();
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 04.04.12
 * Time: 17:38
 * To change this template use File | Settings | File Templates.
 */
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем Framework
require_once("../../config/class.config.dmn.php");
// Проверяем GET-параметр, предотвращая SQL-инъекцию
$_GET['id_account'] = intval($_GET['id_account']);

try
{
    // Проверяем, не удаляется ли последний аккаунт:
    // если его удалить, в систему невозможно будет войти
    $query = "SELECT COUNT(*) FROM $tbl_accounts";
    $acc = mysql_query($query);
    if(!$acc)
    {
        throw new ExceptionMySQL(mysql_error(),
                                $query,
                                "Ошибка удаления пользователя");
    }
    if(mysql_result($acc,0) > 1)
    {
        $query = "DELETE FROM $tbl_accounts
                    WHERE id_account=".$_GET['id_account'];
        if(mysql_query($query))
        {
            header("Location: index.php?page=".$_GET[page]);
            exit;
        }
        else
        {
            throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                    "Ошибка удаления пользователя");
        }
    }
    else
    {
        throw new Exception("Нельзя удалить единственный аккаунт");
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(Exception $exc)
{
    require("../utils/exception.php");
}
ob_end_flush();
?>