<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 02.12.11
 * Time: 22:44
 * To change this template use File | Settings | File Templates.
 */

require_once("../../class/class.Database.php");
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
Database::getInstance();
// Если пользователь не авторизовался - авторизуем
if(!isset($_SERVER['PHP_AUTH_USER']))
{
    Header("WWW-Authenticate: Basic realm=\"Admin1 Page\"");
    Header("HTTP/1.0 401 Unauthorized");
    file_put_contents('security.txt', 'false'."\n", FILE_APPEND );
    exit();
}
else
{
    file_put_contents('security.txt', 'true'."\n", FILE_APPEND );
    // Проверяем переменные $_SERVER['PHP_AUTH_USER']
    // и $_SERVER['PHP_AUTH_PW'], чтобы предотвратить
    // SQL-инъекцию
    if(!get_magic_quotes_gpc())
    {
        $_SERVER['PHP_AUTH_USER'] =
                    mysql_real_escape_string($_SERVER['PHP_AUTH_USER']);
        $_SERVER['PHP_AUTH_PW'] =
                    mysql_real_escape_string($_SERVER['PHP_AUTH_PW']);
    }


    $query = "SELECT pass from $tbl_accounts
                WHERE name='{$_SERVER[PHP_AUTH_USER]}'";
    $lst = @mysql_query($query);

    // Если найдена ошибка в SQL-запросе -
    // открываем диалоговое окно ввода пароля
    if(!$lst)
    {
        Header("WWW-Authenticate: Basic realm=\"Admin2 Page\"");
        Header("HTTP/1.0 401 Unauthorized");
        exit();
    }

    // Если такого пользователя нет -
    // открываем диалоговое окно ввода пароля
    if(mysql_num_rows($lst) == 0)
    {
        Header("WWW-Authenticate: Basic realm=\"Admin3 Page\"");
        Header("HTTP/1.0 401 Unauthorized");
        exit();
    }

    // Если все проверки пройдены, сравниваем хэши паролей
    $pass = @mysql_fetch_array($lst);
    if(md5($_SERVER['PHP_AUTH_PW'])!= $pass['pass'])
    {
        Header("WWW-Authenticate: Basic realm=\"Admin4 Page\"");
        Header("HTTP/1.0 401 Unauthorized");
        exit();
    }
}
?>
