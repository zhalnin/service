<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 30.04.12
 * Time: 22:09
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL & ~E_NOTICE);
$title = 'Подробная информация';
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы
require_once("../../config/class.config.dmn.php");
?>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="../utils/cms.css">
</head>
<body leftmargin="0"
      marginheight="0"
      marginwidth="0"
      rightmargin="0"
      bottommargin="0"
      topmargin="0">
<table width="100%"
       border="0"
       cellpadding="0"
       cellspacing="0"
       height="100%"
       class="text">
    <tr valign="top">
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td width="0">&nbsp;</td>
        <td class=main height=100%>
            <?php
            // Проверяем GET-параметры
            $_GET['id_position'] = intval($_GET['id_position']);
            try
            {
                $query = "SELECT * FROM $tbl_cat_position
                        WHERE id_position = $_GET[id_position]
                        LIMIT 1";
                $pos = mysql_query($query);
                if(!$pos)
                {
                    throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка при обращении к
                                            таблице позиций");
                }
                if(mysql_num_rows($pos))
                {
                    $position = mysql_fetch_array($pos);
                    ?>
            <table width="100%"
                   class="table"
                   border="0"
                   cellpadding="0"
                   cellspacing="0">
                <tr class="header" align="center">
                    <td>Параметр</td>
                    <td>Значение</td>
                </tr>
            <?php
                // Определяем оператора
                $operator = "AT&T";
                switch ($position['operator'])
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
                    case 'Cellcoom':
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
                    case 'Fido/Roges':
                        $operator = "Fido/Rogers";
                        break;
                    case 'Pelephone':
                        $operator = "Pelephone";
                        break;
                    case 'KPN':
                        $operator = "KPN";
                        break;
                    case 'KT-Freetel':
                        $operator = "KT-Freetel";
                        break;
                    case 'Mobinil':
                        $operator = "Mobinil";
                        break;
                    case 'Movistar':
                        $operator = "Movistar";
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
                    case 'Play':
                        $operator = "Play";
                        break;
                    case 'SFR':
                        $operator = "SFR";
                        break;
                    case 'STC':
                        $operator = "STC";
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
                // Определем материал дома
                $cost = $position['cost'];
                // Определяем тип санузла
                $timeconsume = $position['timeconsume'];

                if(!empty($position['status'])){
                    $status = $position['status'];
                } else {
                    $status = "все IMEI";
                }

                if(!empty($position['compatible'])){
                    $compatible = $position['compatible'];
                } else {
                    $compatible = "Все модели";
                }

                echo "<tr>
                        <td align=right>Оператор</td>
                        <td>$operator</td>
                      </tr>";
                echo "<tr>
                        <td align=right>Стоимость</td>
                        <td>$position[cost]</td>
                      </tr>";
                echo "<tr>
                        <td align=right>Сроки</td>
                        <td>$position[timeconsume]</td>
                      </tr>";
                echo "<tr>
                        <td align=right>Совместимость</td>
                        <td>$compatible</td>
                      </tr>";
                echo "<tr>
                        <td align=right>Статус</td>
                        <td>$status</td>
                      </tr>";
                echo "<tr>
                        <td align=right>Валюта</td>
                        <td>$position[currency]</td>
                      </tr>";
                }
                echo "</table><br><br>";
            }
            catch(ExceptionMySQL $exc)
            {
                require("../utils/exception_mysql.php");
            }
            ?>
        </td>
        <td width=10>&nbsp;</td>
    </tr>
    <tr class=authors>
        <td colspan="3"></td>
    </tr>
</table>
</body>
</html>