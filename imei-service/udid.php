<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/11/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
header("Expires: Mon, 23 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
Header("Pragma: no-cache");
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
// Устанавливаем соединение с базой данных
//require_once("config/config.php");
require_once("class/class.Database.php");
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");

// Подключаем FrameWork
require_once("config/class.config.php");
// Подключаем функцию вывода текста с bbCode
require_once("dmn/utils/utils.print_page.php");
// Подключаем заголовок
//require_once("utils.title.php");

$_GET['id_parent'] = intval($_GET['id_parent']);

Database::getInstance();
try
{
// Если GET-параметр id_news не передан - выводим
// список новостных сообщений
    if(empty($_GET['id_parent']))
    {
        $_GET['id_parent'] = 0;

        // Объявляем объект постраничной навигации
        $query = "SELECT * FROM $tbl_cat_catalog
                WHERE id_parent = $_GET[id_parent]
                AND modrewrite = 'udid'
                AND hide = 'show'";
    }
    $res = mysql_query($query);
    if(!$res){
        throw new ExceptionMySQL(mysql_error(),
            $query,
            "Ошибка выбора каталога");
    }
    $parent_catalog = mysql_fetch_array($res);
// Название каталога
    $title = $parent_catalog['name'];
    $parent = $parent_catalog[id_catalog];
    $keywords = "udid registration,регистрация udid,аккаунт разработчика,iOS7.1 beta,iOS7.1 бета,провижен профиль,provision";
    $description = "Регистрация UDID в аккаунте разработчика позволит вам устанавливать прошивки бета-версии без опасения, что аппарат не активируется. Также появляется возможность установки платных приложений бесплатно.";

    require_once("templates/top.php");
    ?>
    <div id="header">
        <ul id="navigation" role="navigation">
            <li id="nav-home"><a href="index.php"><span>Главная</span></a></li>
            <li id="nav-unlock"><a href="unlock.php"><span>Официальный Анлок iPhone</span></a></li>
            <li id="nav-udid"><a  class="selected" href="udid.php"><span>Регистрация UDID</span></a></li>
            <li id="nav-carrier"><a href="carrier_check.php"><span>Проверка оператора по IMEI</span></a></li>
            <li id="nav-fast_check"><a href="fast_check.php"><span>Быстрая проверка</span></a></li>
            <li id="nav-blacklist"><a href="blacklist_check.php"><span>Blacklist</span></a></li>
            <li id="nav-faq"><a href="faq.php"><span>Вопросы</span></a></li>
        </ul>
    </div>

    <div id="main"  class="main-content">
        <div id="showcase" class="content">
            <div class="hero selfclear">
                <div id="shipping-box" class="box box-nomargin shipping-box ptn">
                    <!--                <span class="step-header is-stepped stepnumber2" style="opacity: 1;"></span>-->
                    <h2 id="shipping-box-title" class="heading primary" style="opacity: 1;"><a href="<?php echo $_SERVER[PHP_SELF] ?>"><?php echo $parent_catalog[name];?></a></h2>
                    <div id="shipping" class="step edit" style="opacity: 1;">
                        <div class="step-content top-divided" style="">
                            <div id="shipping-contact-form" class="step-mode edit clearfix" autocomplete="off" style="">
                                <div class="gs grid-1of2" style="">
                                    <div id="shipping-user" class="user-form-block substep" style="">
                                        <form method="post">
                                            <fieldset class="US first user-form-fieldset" style="">
                                                <legend style="">
                                                    <strong class="label"><?php echo $parent_catalog[order_title];?></strong>
                                                </legend>
                                                <div id="shipping-user-address_section" class="user-address fieldset-content" style="">

                                                    <div class="mbs" style="">
                                                        <span class="companyName-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-companyName" style="">
                                                                <span>Адрес Email (обязательно)</span>
                                                            </label>
                                                            <input id="email" class="email" type="email" maxlength="35" size="35" name="email">
                                                        </span>
                                                    </div>
                                                    <div class="mbs" style="">

                                                        <span class="udidPhone-field field-with-placeholder" style="">
                                                            <label class="placeholder" for="shipping-user-udidPhone" style="">
                                                                <span>UDID ( 40 символов )</span>
                                                            </label>
                                                            <input id="udid" class="udid" type="text" size="8" name="udid">
                                                        </span>
                                                    </div>
                                                </div>
                                                <input id="item" type="hidden" name="item" value="Регистрация UDID">
                                                <input id="type" type="hidden" name="type" value="udid">
                                            </fieldset>
                                        </form>
                                    </div><!-- shipping user -->
                                </div><!-- shipping-box-title -->
                                <div class="gs grid-1of2 gs-last" style="">
                                    <div class="substep" style="">
                                        <div id="payment-form-astro" class="form-astro with-seperator">
                                            <p class="legend" style="">
                                                <strong id="coherent_id_103">Условия</strong>
                                                <a href="faq.php?id_catalog=0&id_position=41" class="separated-link metrics-link">Как узнать UDID?</a>
                                            </p>
                                            <p>
                                                <?php
                                                echo nl2br($parent_catalog['description']);
                                                ?>
                                            </p>
                                        </div><!-- payment-form-astro -->
                                    </div><!-- substep -->
                                </div><!-- gs grid-lof2 gs-last -->
                            </div><!-- content -->
                        </div><!-- step-content top-divided -->
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
                                            </div><!-- shipping-continue-button -->
                                        </div><!-- shipping-step-defaults -->
                                    </div><!-- chat chat-now cchat -->
                                </div><!-- gs grid-3of4 -->
                                <div class="gs grid-1of4 gs-last r-align" style="">
                                    <div id="shipping-button" class="button rect transactional blues" title="Сбросить" type="button" style="">
                                        <span style="">
                                            <span class="effect"></span>
                                            <span class="label"> Сбросить </span>
                                        </span>
                                    </div><!-- shipping-button -->
                                </div><!-- gs grid-1of4 gs-last r-align -->
                            </div><!-- continue-content clearfix -->
                        </div><!-- step-continue part-edit clear -->
                    </div><!-- shipping -->
                </div><!-- shipping-box -->
            </div><!-- hero selfclear -->
        </div><!-- showcase -->
    </div><!-- main -->

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