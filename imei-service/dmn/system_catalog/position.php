<?php

ob_start();
error_reporting(E_ALL & ~E_NOTICE);

// Подключаем базу данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
// Навигационное меню
require_once("../utils/utils.navigation.php");
// Подключаем блок отображения текста в окне браузера
require_once("../utils/utils.print_page.php");

$title = $titlepage = "Администрирование каталога продукции";
$pageinfo = "<p class=help>Здесь осуществляется добавление позиции
                        удаление и редактирование уже существующих позиций</p>";

// Включаем заголовок страницы
require_once("../utils/top.php");

$_GET['id_catalog'] = intval($_GET['id_catalog']);

try
{
    // Извлекаем параметры текущего каталога
    $query = "SELECT * FROM $tbl_cat_catalog
            WHERE id_catalog = $_GET[id_catalog]
            LIMIT 1";
    $cat = mysql_query($query);
    if(!$cat)
    {
        throw new ExceptionMySQL(mysql_error(),
                                 $query,
                                "Ошибка извлечения
                                параметров каталога");
    }
    $catalog = mysql_fetch_array($cat);
    // Если это не корневой каталог, выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>'.
    "<a class=menu href=index.php?id_parent=0&".
                "page=$_GET[page] title=\"Корневое меню\">Корневое меню</a>-&gt;".
                menu_navigation($_GET["id_catalog"], "",$tbl_cat_catalog).
                "<a class=menu href=posadd.php?id_catalog=$_GET[id_catalog]&".
                    "page=$_GET[page] title=\"Добавить позицию\">Добавить позицию</a>".

    '</td></tr></table>';

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество элементов на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new PagerMysql($tbl_cat_position,
                        "WHERE id_catalog=$_GET[id_catalog]",
                        "ORDER BY pos",
                        $pnumber,
                        $page_link,
                        "&id_catalog=$_GET[id_catalog]");
    // Получаем содержимое текущей страницы
    $position = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим ее
    if(!empty($position))
    {
        // Выводим заголовок таблицы
        echo '<table width="100%"
                    class="table"
                    border="0"
                    cellpadding="0"
                    cellspacing="0">
                    <tr class="header" align="center">
                        <td width=100>Оператор</td>
                        <td width=150>Стоимость</td>
                        <td width=150>Совместимость</td>
                        <td width=150>Сроки</td>
                        <td width=100>Действия</td>
                    </tr>';
        for($i = 0; $i < count($position); $i++)
        {
            $url = "id_position={$position[$i][id_position]}&".
                "id_catalog={$position[$i][id_catalog]}&page=$_GET[page]";
            // Выясняем, скрыть элемент или нет
            if($position[$i]['hide'] == 'hide')
            {
                $strhide = "<a href=posshow.php?$url>Отобразить</a>";
                $style = " class=hiddenrow";
            }
            else
            {
                $strhide = "<a href=poshide.php?$url>Скрыть</a>";
                $style = "";
            }
            // Определяем оператора
            $operator = "AT&T";
            switch ($position[$i]['operator'])
            {
                case 'AT&T':
                    $operator = "AT&T";
                    break;
                case 'Avea':
                    $operator = "Aveaa";
                    break;
                case 'Bouygues':
                    $operator = "Bouygues";
                    break;
                case 'Bell':
                    $operator = "Bell";
                    break;
                case 'Cellcom':
                    $operator = "Cellcom";
                    break;
                case 'Claro':
                    $operator = "Claro";
                    break;
                case 'Etisalat':
                    $operator = "Etisalat";
                    break;
                case 'EMEA':
                    $operator = "EMEA";
                    break;
                case 'Entel':
                    $operator = "Entel";
                    break;
                case 'Fido/Rogers':
                    $operator = "Fido/Rogers";
                    break;
                case 'KPN':
                    $operator = "KPN";
                    break;
                case 'KT-Freetel':
                    $operator = "KT-Freetel";
                    break;
                case 'Mobilkom':
                    $operator = "Mobilkom";
                    break;
                case 'Mobinil':
                    $operator = "Mobinil";
                    break;
                case 'Movistar':
                    $operator = "Movistar";
                    break;
                case 'Netcom':
                    $operator = "Netcom";
                    break;
                case 'Omnitel':
                    $operator = "Omnitel";
                    break;
                case 'Optus':
                    $operator = "Optus";
                    break;
                case 'Orange':
                    $operator = "Orange";
                    break;
                case 'O2':
                    $operator = "O2";
                    break;
                case 'Pelephone':
                    $operator = "Pelephone";
                    break;
                case 'Play':
                    $operator = "Play";
                    break;
                case 'SFR':
                    $operator = "SFR";
                    break;
                case 'STC':
                    $operator = "STC";
                    break;
                case 'Softbank':
                    $operator = "Softbank";
                    break;
                case 'Sunrise':
                    $operator = "Sunrise";
                    break;
                case 'Swisscom':
                    $operator = "Swisscom";
                    break;
                case '3Three/Hutchison':
                    $operator = "3Three/Hutchison";
                    break;
                case 'Tukcell':
                    $operator = "Tukcell";
                    break;
                case 'Telenor':
                    $operator = "Telenor";
                    break;
                case 'Tele2':
                    $operator = "Tele2";
                    break;
                case 'Telia':
                    $operator = "Telia";
                    break;
                case 'Telus':
                    $operator = "Telus";
                    break;
                case 'Telstra':
                    $operator = "Telstra";
                    break;
                case 'Tim':
                    $operator = "Tim";
                    break;
                case 'T-Mobile':
                    $operator = "T-Mobile";
                    break;
                case 'Vivo':
                    $operator = "Vivo";
                    break;
                case 'Verizon':
                    $operator = "Verizon";
                    break;
                case 'Vodafone':
                    $operator = "Vodafone";
                    break;
            }

            // Выводим позицию
            echo "<tr $style>
                    <td>
                        <a href=# onclick=\"show_detail('posdetail.php".
                        "?id_position={$position[$i][id_position]}',400,350);".
                        " return false \"
                        title = \"Подробнее\">
                            $operator<br/>
                        </a>
                    </td>
                    <td>{$position[$i][cost]}</td>
                    <td>{$position[$i][compatible]}</td>
                    <td>{$position[$i][timeconsume]}</td>";
              echo "<td>
                        <a href=posup.php?$url>Вверх</a><br/>
                        $strhide<br/>
                        <a href=posedit.php?$url>Редактировать</a><br/>
                        <a href=# onClick=\"delete_position('posdel.php?$url',".
                    "'Вы действительно хотите удалить позицию');\">Удалить</a><br/>
                        <a href=posdown.php?$url>Вниз</a></p>
                    </td>
                 </tr>";
        }
        echo "</table><br/>";
        // Выводим ссылки на другие страницы
        echo $obj;
    }
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}

// Включаем завершение страницы
require_once("../utils/bottom.php");
ob_get_flush();
?>

<script type="text/javascript" language="JavaScript">
<!--
    function show_detail(url,width,height)
    {
        var a;
        var b;
        var url;
        vidWindowWidth = width;
        vidWindowHeight = height;
        a = (screen.height-vidWindowHeight)/5;
        b = (screen.width-vidWindowWidth)/2;
        features = "top=" + a + ",left=" + b +
                    ",width=" + vidWindowWidth +
                    ",height=" + vidWindowHeight +
                    ",toolbar=no,menubar=no,location=no" +
                    ",directories=no,scrollbars=no,resizable=no";
        window.open(url,'',features,true);
    }
//-->
</script>