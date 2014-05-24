<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/17/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\view;


error_reporting(E_ALL & ~E_NOTICE);
require_once( "imei_service/view/ViewHelper.php" );
require_once( "imei_service/view/utils/utils.printPage.php" );

$request = \imei_service\view\VH::getRequest();
//echo $request->getFeedback()[0];
echo $request->getFeedbackString('< br/>');

//echo "<tt><pre>".print_r($request->getFeedback(), true)."</pre></tt>";


$DB = \imei_service\base\DBRegistry::getDB();
// Проверяем, является ли параметр id числом
$_GET['id'] = intval($_GET['id']);
// Выводим выбранное новостное сообщение
$query = "SELECT * FROM system_news
                    WHERE  hide = 'show'
                    AND id = $_GET[id]";
$sth =  $DB->prepare( $query );
$res = $sth->execute();
//$res = mysql_query($query);
if(!$res)
{
    throw new \Exception(
        "Ошибка при извлечении
        текущей позиции");
}
$news = $sth->fetch();
// Если имеется хотя бы одна запись - выводим ее
if(!empty($news))
{

    if($news['hidedate'] != 'hide' ) {
        $pdate = date('d.m.Y H:i', strtotime($news['putdate']));
        $putdate =  "<span id=\"datetime\">".$pdate."</span>";
    } else {
        $putdate = "";
    }
    if($news['urlpict'] != '' && $news['hidepict'] != 'hide')
    {
        $photo_print = "src='{$news['urlpict_s']}' alt='alt'";
        $img = "<img $photo_print>";

    } else {
        $img = "";
    }
    if($news['url'] != '' && $news['url'] != '-')
    {
        $href = "href='".$news['url']."'";
        $val_href = $news['urltext'];
    }

    echo "<div class='news-all-body'>
                <div class='news-all-title'>
                    <h1 class=\"h2\">".nl2br(\imei_service\view\utils\printPage($news['name']))."</h1>
                </div>
                <div class='news-all-image'>
                  $img
                  $putdate
                </div>
                <div class='news-all-info'>
                    <p>".nl2br(\imei_service\view\utils\printPage($news['body']))."</p>
                </div>
                <div class=\"gs grid4 gs-last r-align\" style=\"\" onclick=window.history.back(); >
                    <div id=\"button_back\" class=\"button rect transactional blues\" title=\"Сбросить\" type=\"button\" style=\"\">
                            <span style=\"\">
                                <span class=\"effect\"></span>
                                <span class=\"label\"> Назад </span>
                            </span>
                    </div><!-- shipping-button -->
                </div>
          </div>";

}

?>