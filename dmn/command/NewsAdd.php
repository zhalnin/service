<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 20:07
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'News' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class NewsAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $name           = new \dmn\classes\FieldText("name",
                                                    "Название",
                                                    true,
                                                    $_POST['name']);
        $preview       = new \dmn\classes\FieldTextarea("preview",
                                                    "Превью",
                                                    true,
                                                    $_POST['preview'],
                                                    50,
                                                    10,
                                                    false);
        $body           = new \dmn\classes\FieldTextarea("body",
                                                    "Содержимое",
                                                    true,
                                                    $_POST['body'],
                                                    60,
                                                    20,
                                                    false);
        $date           = new \dmn\classes\FieldDatetime("date",
                                                    "Дата новости",
                                                    $_POST['date']);
        $hidedate       = new \dmn\classes\FieldCheckbox("hidedate",
                                                    "Отображать дату",
                                                    $_POST['hidedate']);
        $urltext        = new \dmn\classes\FieldText("urltext",
                                                    "Текст ссылки",
                                                    false,
                                                    $_POST['urltext']);
        $url            = new \dmn\classes\FieldText("url",
                                                    "Ссылка",
                                                    false,
                                                    $_POST['url']);
        $alt            = new \dmn\classes\FieldText("alt",
                                                    "ALT-тег",
                                                    false,
                                                    $_POST['alt']);
        $filename       = new \dmn\classes\FieldFile("filename",
                                                    "Изображение",
                                                    false,
                                                    $_FILES,
                                                    "imei_service/view/files/news/",
                                                    "news_");
        $hide           = new \dmn\classes\FieldCheckbox("hide",
                                                    "Отображать",
                                                    $_REQUEST['hide']);
        $hidepict       = new \dmn\classes\FieldCheckbox("hidepict",
                                                    "Отображать фото",
                                                    $_REQUEST['hidepict']);
        $page           = new \dmn\classes\FieldHiddenInt("page",
                                                    false,
                                                    $_REQUEST['page']);
        $submitted      = new \dmn\classes\FieldHidden( "submitted",
                                                        true,
                                                        "yes" );

        // формируем форму
        $form = new \dmn\classes\Form( array(   "name"     => $name,
                                                "preview"  => $preview,
                                                "body"     => $body,
                                                "date"     => $date,
                                                "hidedate" => $hidedate,
                                                "urltext"  => $urltext,
                                                "url"      => $url,
                                                "alt"      => $alt,
                                                "filename" => $filename,
                                                "hide"     => $hide,
                                                "hidepict" => $hidepict,
                                                "page"     => $page,
                                                "sumbitted"=> $submitted ),
                                        "Добавить" ,
                                        "field" );

        if( $_POST['submitted'] == 'yes' ) {
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках
            if( ! empty( $error ) ) { // если есть ошибки
                if( is_array( $error ) ) { // если это массив
                    foreach ( $error as $er ) { // проходим в цикле
                        $request->addFeedback( $er ); // добавляем сообщение об ошибке
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                $rawPos = \dmn\domain\News::findMaxPos( null ); // работает \imei_service\domain\News - получаем коллекцию
                $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
                $rawPhotoSettings = \dmn\domain\News::findPhotoSetting(); // получаем массив с размерами фото


                // Отображать дату или нет
                if($form->fields['hidedate']->value) $hidedate = "show";
                else $hidedate = "hide";

                // Скрытая или открытая фотография
                if($form->fields['hidepict']->value) $showhidepict = "show";
                else $showhidepict = "hide";

                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }
                // Изображение
                $str = $form->fields['filename']->getFilename();
                //            echo "<tt><pre>".print_r($str, true)."</pre></tt>";
                if( ! empty( $str ) ) {
                    $url_pict = "files/news/$str";
                    $url_pict_s = "files/news/s_$str";
                    \dmn\view\utils\resizeImg(  "imei_service/view/files/news/$str",
                                                "imei_service/view/files/news/s_$str",
                                                $rawPhotoSettings['width_news'],
                                                $rawPhotoSettings['height_news'] );
                } else {
                    $url_pict = "";
                    $url_pict_s = "";
                }

                // получаем объект News без id - значит будет INSERT
                $newsObj = new \dmn\domain\News();

                // устанавливаем название
                $newsObj->setName( $form->fields['name']->value );
                // устанавливаем превьюшку
                $newsObj->setPreview( $form->fields['preview']->value );
                // устанавливаем тело новости
                $newsObj->setBody( $form->fields['body']->value );
                // устанавливаем дату
                $newsObj->setPutdate( $form->fields['date']->getMysqlFormat() );
                // устанавливаем чекбокс сокрытия даты
                $newsObj->setHidedate( $hidedate );
                // устанавливаем текст ссылки
                $newsObj->setUrltext( $form->fields['urltext']->value );
                // устанавливаем ссылку
                $newsObj->setUrl( $form->fields['url']->value );
                // устанавливаем название изображения
                $newsObj->setAlt( $form->fields['alt']->value );
                // устанавливаем большое изображение
                $newsObj->setUrlpict( $url_pict );
                // устанавливаем малое изображение
                $newsObj->setUrlpict_s( $url_pict_s );
                // устанавливаем позицию
                $newsObj->setPos( $position );
                // устанавливаем чекбокс сокрытия новости
                $newsObj->setHide( $showhide );
                // устанавливаем чекбокс сокрытия изображения
                $newsObj->setHidepict( $showhidepict );

                $this->reloadPage( 0, "dmn.php?cmd=News&page=$_GET[page]" ); // перегружаем страничку
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>