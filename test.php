<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf8" >
    <style type='text/css'>
        * {
            /*margin: 0;*/
            /*padding: 0;*/
        }
        ul li {
            
        }
        li {
            /*list-style-image: url(../dataimg/optdot_prd.gif);*/
            list-style-type: none;
            margin: 0 10px;
        }
        .main_txt {
            font-family: Tahoma;
            /*font-size: 11px;*/
            text-align: justify;
            /*text-indent: 50px;*/
            /*color: #000000;*/
            color: #666666;
            font-size: 1em;
            line-height: 1.5em;
            padding-top: 5px;
            padding-bottom: 5px;

            margin-left: 30px;
            margin-right: 30px;
        }

        .main_txt_lnk {
            color: #B20000;
            text-decoration: none;
        }

        .main_txt_lnk:hover {
            color: #00023E;
        }
        #tp {
            /*background-color: #ededed;
            width: 648px;
            height: 122px;
            background-image: url(\"http://imei-service.ru/images/letter/top_apple.png\");*/
        }
        #mid {
            background-color: #f1f1f1;
            width: 630px;
            margin-left: 9px;
            text-align: center;
            padding-bottom: 100px;
            color: #000000;
        }

        #btm {
            /* background-color: #ededed;
             width: 630px;
             height: 21px;
             background-image: url(\"http://imei-service.ru/images/letter/btm.gif\");
             margin-left: 9px;*/
        }
        h1 {
            margin-bottom: 30px;
        }

        p {
            margin-bottom: 18px;
        }
        .footer_txt {
            font-family: Tahoma;
            /*font-size: 11px;*/
            text-align: justify;
            text-indent: 20px;
            /*color: #000000;*/
            color: #666666;
            font-size: 0.8em;
            line-height: 1.5em;
            padding-top: 5px;
            padding-bottom: 5px;
            margin-left: 50px;
        }
        .nested {
            margin-left: 20px;
        }
        .nested li {
            list-style-type: disc;
        }
        a {
            color: #0088CC;
            cursor: pointer;
            text-decoration: none;
        }
        #container {
            width: 650px;
            margin: 30 auto;
        }

    </style>
</head>
</html>
<?php
    $t = "";
    $t .= "<ul>";
    for( $i=1; $i<2; $i++ ) {

        $item_name      = 'Проверка iPhone по IMEI';
        $amount         = 30;
        $quantity       = 2;
        $total          = 60;
        $subtotal       = 60;
        $data           = 'IMEI: 012345678909876';

        $t .= "<li>Наименование: ".$item_name."</li>"
            ."<li>Стоимость: ".$amount." RUB</li>"
            ."<li>Количество: ".$quantity." ед.</li>"
            ."<li>Итог: ".$total." RUB</li>"
            ."<li>Общая стоимость: ".$subtotal." RUB</li>";

    }
    $t .= "</ul>";

echo $t;
?>