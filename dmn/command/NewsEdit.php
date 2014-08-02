<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 22/07/14
 * Time: 16:33
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

class NewsEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $id = $request->getProperty( 'idn' );
        if( $id ) { // если передан id_news
            $news = \dmn\domain\News::find( $id ); // находим элементы по заданному id_news
            $news_date = $news->getPutdate();

            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {
                // получаем массив с датой в class.FieldDateTime затем из mktime формируем дату
                if( ! empty( $news_date ) ) {
                    // месяц
                    $_REQUEST['date']['month']  = substr( $news->getPutdate(), 5, 2 );
                    // день
                    $_REQUEST['date']['day']    = substr( $news->getPutdate(), 8, 2 );
                    // год
                    $_REQUEST['date']['year']   = substr( $news->getPutdate(), 0, 4 );
                    // часы
                    $_REQUEST['date']['hour']   = substr( $news->getPutdate(), 11, 2 );
                    // минуты
                    $_REQUEST['date']['minute'] = substr( $news->getPutdate(), 14, 2 );

                }
                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['name']       = $news->getName(); // название
                $_REQUEST['preview']    = $news->getPreview(); // превьюшка
                $_REQUEST['body']       = $news->getBody(); // тело новости
                $_REQUEST['urltext']    = $news->getUrltext(); // текст ссылки
                $_REQUEST['url']        = $news->getUrl(); // ссылка
                $_REQUEST['alt']        = $news->getAlt(); // название изображения
                // если дата не скрыта
                if( $news->getHidedate() == 'show' ) {
                    $_REQUEST['hidedate'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hidedate'] = false; // снимаем чекбокс
                }
                // если изображение не скрыто
                if( $news->getHidepict() == 'show' ) {
                    $_REQUEST['hidepict'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hidepict'] = false; // снимаем чекбокс
                }
                // если новость не скрыта
                if( $news->getHide() == 'show' ) {
                    $_REQUEST['hide'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hide'] = false; // снимаем чекбокс
                }
            }

            $name           = new \dmn\classes\FieldText("name",
                                                        "Название",
                                                        true,
                                                        $_REQUEST['name'] );
            $preview        = new \dmn\classes\FieldTextarea("preview",
                                                        "Превью",
                                                        true,
                                                        $_REQUEST['preview'],
                                                        50,
                                                        10,
                                                        false);
            $body           = new \dmn\classes\FieldTextarea("body",
                                                        "Содержимое",
                                                        true,
                                                        $_REQUEST['body'],
                                                        60,
                                                        20,
                                                        false);
            $date           = new \dmn\classes\FieldDatetime("date",
                                                        "Дата новости",
                                                        $_REQUEST['date'] );
            $hidedate       = new \dmn\classes\FieldCheckbox("hidedate",
                                                        "Отображать дату",
                                                        $_REQUEST['hidedate'] );
            $urltext        = new \dmn\classes\FieldText("urltext",
                                                        "Текст ссылки",
                                                        false,
                                                        $_REQUEST['urltext'] );
            $url            = new \dmn\classes\FieldText("url",
                                                        "Ссылка",
                                                        false,
                                                        $_REQUEST['url'] );
            $alt            = new \dmn\classes\FieldText("alt",
                                                        "ALT-тег",
                                                        false,
                                                        $_REQUEST['alt'] );
            $filename       = new \dmn\classes\FieldFile("filename",
                                                        "Изображение",
                                                        false,
                                                        $_FILES,
                                                        "imei_service/view/files/news/",
                                                        "news_");
            $hide           = new \dmn\classes\FieldCheckbox("hide",
                                                        "Отображать",
                                                        $_REQUEST['hide'] );
            $hidepict       = new \dmn\classes\FieldCheckbox("hidepict",
                                                        "Отображать фото",
                                                        $_REQUEST['hidepict'] );
            // Удаление изображения
            $delimg         = new \dmn\classes\FieldCheckbox("delimg",
                                                        "Удалить изображение",
                                                        $_REQUEST['delimg']);
            $page           = new \dmn\classes\FieldHiddenInt("page",
                                                        false,
                                                        $_GET['page'] );
            $id_news        = new \dmn\classes\FieldHiddenInt( "id_news",
                                                        "",
                                                        $_REQUEST['id_news'] );
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
                                                    "delimg"   => $delimg,
                                                    "page"     => $page,
                                                    "id_news"  => $id_news,
                                                    "sumbitted"=> $submitted ),
                                                "Редактировать" ,
                                                "field" );

//            $request->setObject('form', $form ); // выводим форму заново

        }

        // если форма была передана
        if( ! empty( $_POST ) && $_POST['submitted'] == 'yes' ) {
            // проверяем на наличие пустых полей
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках
            if( ! empty( $error ) ) { // если есть ошибки
                if( is_array( $error ) ) { // если это массив
                    foreach ( $error as $er ) { // проходим в цикле
                        $request->addFeedback( $er ); // добавляем сообщение об ошибке
                    }
                }
                $filenameTmp = $form->fields['filename']->getFilename();
                if( ! empty( $filenameTmp ) ) {
                    // Удаляем старые изображения
                    if( file_exists(  "imei_service/view/files/news/".$filenameTmp ) ) {
                        @unlink( "imei_service/view/files/news/".$filenameTmp );
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                //
                //            $rawPos = \dmn\domain\News::findMaxPos(); // работает \imei_service\domain\News - получаем коллекцию
                //            $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
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
                // Удаляем старые файлы, если они имеются
                $delimg = $form->fields['delimg']->value;
                if( ! empty( $delimg ) ) { // если чекбокс установлен
                    $path = str_replace( "//", "/","imei_service/view/".$news->getUrlpict_s() ); // путь до большого изображения
                    $path_small = str_replace( "//", "/","imei_service/view/".$news->getUrlpict() ); // путь до малого изображения
                    if( file_exists( $path ) ) { // если большое изображение существует
                        @unlink( $path ); // удаляем
                    }
                    if( file_exists( $path_small ) ) { // если малое изображение существует
                        @unlink( $path_small ); // удаляем
                    }
                }
//                $url_pict = $url_pict_s = "";

                // Изображение
                if( ! empty( $_FILES['filename']['name'] ) ) {
                    // Удаляем старые изображения
                    if( file_exists(  "imei_service/view/".$news->getUrlpict() ) ) {
                        @unlink( "imei_service/view/".$news->getUrlpict() );
                    }
                    if( file_exists(  "imei_service/view/".$news->getUrlpict_s() ) ) {
                        @unlink( "imei_service/view/".$news->getUrlpict_s() );
                    }
                    // Новые изображения
                    $str = $form->fields['filename']->getFilename();
                    // если новое изображение присутствует
                    if( ! empty( $str ) ) {
                        $url_pict = "files/news/$str"; // большое изображение
                        $url_pict_s = "files/news/s_$str"; // малое изображение
                        // меняем размер изображения
                        \dmn\view\utils\resizeImg(  "imei_service/view/files/news/$str",
                                                    "imei_service/view/files/news/s_$str",
                                                    $rawPhotoSettings['width_news'],
                                                    $rawPhotoSettings['height_news'] );
                        // обновляем большое изображение
                        $news->setUrlpict( $url_pict );
                        // обновляем малое изображение
                        $news->setUrlpict_s( $url_pict_s );
                    }
                }

//                echo "<tt><pre>".print_r( $url_pict , true)."</pre></tt>";

                // обновляем название
                $news->setName( $form->fields['name']->value );
                // обновляем превьюшку
                $news->setPreview( $form->fields['preview']->value );
                // обновляем тело новости
                $news->setBody( $form->fields['body']->value );
                // обновляем дату
                $news->setPutdate( $form->fields['date']->getMysqlFormat() );
                // обновляем чекбокс сокрытия даты
                $news->setHidedate( $hidedate );
                // обновляем текст ссылки
                $news->setUrltext( $form->fields['urltext']->value );
                // обновляем ссылку
                $news->setUrl( $form->fields['url']->value );
                // обновляем название изображения
                $news->setAlt( $form->fields['alt']->value );
                // обновляем позицию
//                $news->setPos( $position );
                // обновляем чекбокс сокрытия новости
                $news->setHide( $showhide );
                // обновляем чекбокс сокрытия изображения
                $news->setHidepict( $showhidepict );

                $this->reloadPage( 0, "dmn.php?cmd=News&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>