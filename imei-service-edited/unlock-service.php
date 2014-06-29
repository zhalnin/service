<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/21/13
 * Time: 5:32 PM
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);

// Устанавливаем соединение с базой данных
//require_once("config/config.php");
require_once("class/class.Database.php");
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");

// Подключаем FrameWork
require_once("config/class.config.php");
// Подключаем функцию вывода текста с bbCode
require_once("dmn/utils/utils.print_page.php");
require_once("utils/utils.print_price.php");
// Подключаем заголовок
//require_once("utils.title.php");
Database::getInstance();

try
{
    // Объявляем объект постраничной навигации
    $query = "SELECT * FROM $tbl_cat_catalog
                WHERE id_parent = 0
                AND hide = 'show'";

    $res = mysql_query($query);
    if(!$res){
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка выбора каталога");
    }
    $parent_catalog = mysql_fetch_array($res);
//echo "<tt><pre>".print_r($parent_catalog, true)."</pre></tt>";

    // Объявляем объект постраничной навигации
    $query = "SELECT * FROM $tbl_cat_catalog
                WHERE id_parent = $_GET[id_parent]
                  AND abbreviatura = '$_GET[ctr]'
                AND hide = 'show'";

    $res = mysql_query($query);

    if(!$res){
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка выбора каталога");
    }
    $catalog = mysql_fetch_array($res);
//    echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
    // Название каталога
    $ctr = $_GET['ctr'];
    $id_parent = $_GET['id_parent'];
    $title = $catalog['name'];
    $id_catalog = $catalog['id_catalog'];
    $keywords = "unlock iPhone,официальный анлок,AT&T,Orange,UK,USA,Bouygues,Telia,SFR,Vodafone,T-mobile,Verizon";
    $description = "Официальный анлок iPhone. Стоимость разлочки iPhone зависит от оператора, к которому он привязан.";


    $query = "SELECT * FROM $tbl_cat_position
                WHERE id_catalog = $id_catalog
                AND hide = 'show'
                ORDER BY operator";
//    print $query;
    $res = mysql_query($query);
    if(!$res){
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка выбора подкаталогов");
    }

    require_once("templates/top.php");
    ?>


    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="index.php"><span>Главная</span></a></li>
            <li id="nav-unlock"><a  class="selected" href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a href="udid.php"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="fast_check.php"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="faq.php"><span>Вопросы</span></a></li>
        </ul>
    </div>





    <div id="main"  class="">

        <div id="main-slogan" class="main-content">
            <div id="slogan">Быстро - Качественно - Надежно</div>
        </div>

    <div id="news-main" class="main-content">
    <div id="slogan"><span class='currency' id='uah'></span><span class='currency' id='usd'></span><span class='currency' id='eur'></span><span class='currency' id='rub'></span></div>
    <div id="showcase" class="content">







    <?php
if(mysql_num_rows($res)){
//    echo "<tt><pre>".print_r($res, true)."</pre></tt>";
    ?>
    <div id="design">
    <div class="row block grid2col row block border">
    <img class="hero-image flushleft" alt="<?php echo $catalog[alt];?>" src="<?php echo $catalog[urlpict];?>">
    <div class="column last">
    <h1><a href="<?php echo $_SERVER[PHP_SELF] .'?ctr='.$ctr.'&id_parent='.$id_parent ?>"><?php echo $catalog[name];?></a></h1>
    <div class='column last dividerdownmidi'>
        <div>
            <div style='width: 130px; float: left; margin: 10px 0 20px 50px;'><b>Оператор</b></div>
            <div style='width: 156px; float: left; margin: 10px 0 20px 50px;'><b>Совместимость</b></div>
            <div style='width: 140px; float: left; margin: 10px 0 20px 65px;'><b>Стоимость</b></div>
            <div style='width: 120px; float: left; margin: 10px 0 20px 60px;'><b>Сроки</b></div>
        </div>
    <?php
    while($subcatalog = mysql_fetch_array($res)){

        echo "<div>
                <div style='width: 150px; float: left; margin: 10px 0 0 30px; font-size: 11pt' id='operator'><ins><b>$subcatalog[operator]</b></ins></div>
                <div style='width: 183px; float: left; margin: 10px 0 0 22px;' id='cost'>iPhone ".$subcatalog[compatible]."</div>
                <div style='width: 150px; float: left; margin: 10px 0 0 60px;' id='timeconsume'>".print_price($subcatalog[cost])."</div>
                <div style='width: 170px; float: left; margin: 10px 0 0 10px;' id='timeconsume'>$subcatalog[timeconsume] $subcatalog[status]</div>
            </div>";
    }
    echo "</div>
                <div class=\"dividerdownbottom\"; style='width: 700px; height: 40px; clear : both;'></div>
                    </div>
                </div>
            </div>";  // End of design
}
    ?>
<!--    <div style='width: 203px; float: left; margin: 10px 0 0 2px;' id='cost'>".$subcatalog[compatible]."</div>-->
<!--    <div style='width: 130px; float: left; margin: 10px 0 0 40px;' id='timeconsume'>$subcatalog[timeconsume] $subcatalog[status]</div>-->




    <div class="hero selfclear">
        <div id="shipping-box" class="box box-nomargin shipping-box ptn">
            <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
            <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;" ><a href="<?php echo $_SERVER[PHP_SELF] .'?ctr='.$ctr.'&id_parent='.$id_parent ?>"><?php echo $catalog[name];?></a></h2>
            <div id="shipping" class="step edit" style="opacity: 1;">
                <div class="step-content top-divided" style="">
                    <div id="shipping-contact-form" class="step-mode edit clearfix" style="">
                        <div class="gs grid-1of2" style="">
                            <div id="shipping-user" class="user-form-block substep" style="">
                                <form method="post">
                                    <fieldset style="">
                                        <legend style="">
                                            <strong class="label"><b><?php echo $parent_catalog[order_title];?></b></strong>
                                        </legend>
                                        <div id="shipping-user-address_section" class="user-address fieldset-content" style="">

                                            <div class="mbs" style="">
                                                <span class="companyName-field field-with-placeholder" style="">
                                                    <label class="placeholder" for="shipping-user-companyName" style="">
                                                        <span>Адрес Email (обязательно)</span>
                                                    </label>
                                                    <input id="email" class="email" type="email" maxlength="" size="35" name="email">
                                                </span>
                                            </div>
                                            <div class="mbs" style="">

                                                    <span class="daytimePhone-field field-with-placeholder" style="">
                                                    <label class="placeholder" for="shipping-user-daytimePhone" style="">
                                                        <span>IMEI (15-ть цифр)</span>
                                                    </label>
                                                    <input id="imei" class="imei" type="text" maxlength="" size="8" name="imei">
                                                </span>
                                            </div>
                                        </div>
                                        <input id="item" type="hidden" name="item" value="официальный анлок iPhone">
                                        <input id="type" type="hidden" name="type" value="unlock">
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="gs grid-1of2 gs-last" style="">
                            <div class="substep" style="">
                                <div id="payment-form-astro" class="form-astro with-seperator">
                                    <p class="legend" style="">
                                        <strong id="coherent_id_103">Условия</strong>
                                        <a href="carrier_check.php" class="separated-link metrics-link">Проверить iPhone на привязку к оператору</a>
                                    </p>
                                    <p>
                                        <?php
                                        echo nl2br(print_page($parent_catalog['description']));
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>  <!--  End of shipping-contact-form -->
                </div>  <!--  End of step-content top-divided -->
                <div class="step-continue part-edit clear" style="">
                    <div class="continue-content clearfix">
                        <div class="gs grid-3of4" style="">
                            <div class="chat chat-now cchat">
                                <div id="shipping-step-defaults" style="">
                                    <div id="shipping-continue-button" class="button rect transactional" title="Отправить" value="click" type="submit" style="visibility: visible">
                                    <span style="">
                                        <span class="effect"></span>
                                        <span class="label"> Отправить </span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gs grid-1of4 gs-last r-align" style="">
                            <div id="shipping-button" class="button rect transactional blues" title="Сбросить" type="button" style="">
                                <span style="">
                                    <span class="effect"></span>
                                    <span class="label"> Сбросить </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!-- End of shipping -->
        </div>  <!--  End of shipping-box -->
    </div>  <!-- End of hero selfclear -->
    </div>  <!-- End of showcase -->
    </div>  <!-- End of news-main -->
    </div>  <!-- End of main -->




    <?php
    require_once("templates/bottom.php");
}
catch(ExceptionMySQL $exc)
{
    require_once("exception_mysql_debug.php");
}
catch(ExceptionObject $exc)
{
    require_once("exception_object_debug.php");
}
catch(ExceptionMember $exc)
{
    require_once("exception_member_debug.php");
}
?>