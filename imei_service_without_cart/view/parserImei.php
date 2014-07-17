<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 19:11
 */

namespace imei_service\view;
error_reporting( E_ALL & ~E_NOTICE );

try {
    // подключаем помощник для вьюшки
    require_once( "imei_service/view/ViewHelper.php" );

    // получаем объект request
    $request = \imei_service\view\VH::getRequest();
    // получаем объект-коллекцию pageCurl
    $page = $request->getObject( 'pageCurl' );

    //// Модель аппарата
    preg_match('|<div class="error">(.+?)</div>|is',$page,$matchErrors);
    preg_match('|<h2 class="model">(.+?)</h2>|is',$page,$matchModel);
    //// Объем памяти и цвет аппарата
    preg_match('|<h3 class="capacity color">(.+?)</h3>|is', $page,$matchCap);
    preg_match_all('|<span class="name">(.+?)</span>|is', $page,$matchName);
    preg_match_all('|<span class="value">(.+?)</span>|is', $page,$matchValue);
    preg_match('|<a id="special_info">(.+?)</a>|is', $page, $matchRef);

    //получаем массив всех-всех ссылок с этой старницы
    $error      =   $matchErrors[1];
    $model		=	$matchModel[1];
    $cap        =   $matchCap[1];
    $name       =   $matchName[1];
    $value      =   $matchValue[1];
    $ref        =   strval($matchRef[1]);
    ?>

    <div id="pb-ipad" class="productbrowser content pb-dynamic" style="height: 400px;">
        <div class="pb-slider" style="height: 400px;">
            <div id="pb-slider" class="pb-slide" style="width: 970px;">
                <?php
                if(!$error){
                ?>
                <ul id="ul-slider" class="ul-slider box" page="1" style="width: 450px; margin: 0 250px; margin-top: 10px">
                    <li style="width: 425px; margin: 0; height: 30px" class="check_imei">
                        <div>
                            <p class="check_imei" style="float: left;">Model: </p><p class="check_imei" style="display: block; float: right;"><?php echo $model; echo $cap; ?></p>
                        </div>
                    </li>
                    <?php
                    for($i = 0; $i < sizeof($name);$i++){
                        echo "<li style=\"width: 425px; margin: 0; height: 30px\" class=\"check_imei\">
                                    <div>
                                         <p class=\"check_imei\" style=\"float: left;\">$name[$i] </p>";
                        switch(strtolower($value[$i])){
                            case('<a id="special_info"> check carrier </a>'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"?cmd=CarrierCheck\" class=\"special_info\">Уточнить оператора</a></p>";
                                break;
                            case('<a class="show_email_info"> check carrier </a>'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"?cmd=CarrierCheck\" class=\"special_info\">Уточнить оператора</a></p>";
                                break;
                            case('<a class="show_email_info"> check sim lock </a>'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"?cmd=CarrierCheck\" class=\"special_info\">Уточнить статус</a></p>";
                                break;
                            case('unlocked'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: #039103;\">$value[$i]</p>";
                                break;
                            case('yes'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: #039103;\">$value[$i]</p>";
                                break;
                            case('unknown'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                                break;
                            case('locked'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                                break;
                            case('no'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                                break;
                            case('expired'):
                                echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                                break;
                            default:
                                echo "<p class=\"check_imei\" style=\"display: block; float: right;\">$value[$i]</p>";

                        }

                        echo "</div>
                                </li>";
                    }

                    } else if($error){

                        echo "<li style=\"width: 325px; margin: 0; height: 30px\" class=\"check_imei\">
                            <div style=\"left: 330px; display: inline-block; margin: 100px auto; position: absolute\">
                                    <p class=\"check_imei\" ></p><p class=\"check_imei\" >Сервер перегружен, попробуйте позже...</p>
                                    <p class=\"check_imei\"><a href=\"?cmd=CarrierCheck\" class=\"special_info\" >Вы можете воспользоваться платной услугой</a></p>
                            </div>
                          </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>


    <?php
    // подключаем нижний шаблон
   // require_once( "imei_service/view/templates/bottom.php" );
// ловим сообщения об ошибках
} catch( \imei_service\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \imei_service\base\DBException $exc ) {
    print $exc->getErrorObject();
}
?>