<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19.06.12
 * Time: 9:02
 * To change this template use File | Settings | File Templates.
 */
require_once("../../config/config.php");
require_once("../../config/class.config.dmn.php");

//echo date("Y-m-01","1338667200");

$name = new field_text("Поле",
                "тестовое поле",
                true,
                "ничего",
                "",
                "",
                "",
                "помоги мне",
                "");
$form = new form(array("name" =>$name),
                "Отправить",
                "field");
echo "<hr>";
$form->print_form();
?>