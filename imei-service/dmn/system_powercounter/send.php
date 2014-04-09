<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17.06.12
 * Time: 21:13
 * To change this template use File | Settings | File Templates.
 */
 
// Выставляем уровень обработки ошибок
error_reporting(E_ALL & ~E_NOTICE);
// Устанавливаем соединение с базой данных
require_once("config.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем блок отображения текста в окне браузера
require_once("../utils/utils.print_page.php");

$title = 'Отправка почтового отчета';
$pageinfo = 'На этой странице можно отправить почтовый отчет
за сутки, неделю и месяц. E-mail отправки можно изменить в
конфигурационном файле config.php системы администрирования
(константа EMAIL_ADDRESS)';

try
{
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    echo "<table class=table
                 width=100%
                 border=0
                 cellspacing=0
                 cellpadding=0>
                 <tr>
                    <td><a href=send_manage.php?freq=1>Отослать</a> ежедневный отчет на ".EMAIL_ADDRESS."</td>
                 </tr>
                 <tr>
                    <td><a href=send_manage.php?freq=7>Отослать</a> ежеднедельный отчет на ".EMAIL_ADDRESS."</td>
                 </tr>
                 <tr>
                    <td><a href=send_manage.php?freq=30>Отослать</a> ежемесячный отчет на ".EMAIL_ADDRESS."</td>
                 </tr>
          </table> ";

    // Включаем завершение страницы
    require_once("../utils/bottomcounter.php");
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
?>
