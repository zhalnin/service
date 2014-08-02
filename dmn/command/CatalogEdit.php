<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 13:02
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'Catalog' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class CatalogEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $id = $request->getProperty( 'idc' );
        $idp = $request->getProperty( 'idp' ); // id родительского каталога
//        echo "<tt><pre>".print_r($idp, true)."</pre></tt>";
        if( $id ) { // если передан id_news
            $catalog = \dmn\domain\Catalog::find( $id ); // находим элементы по заданному id_news
//            echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {

                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['name']       = $catalog->getName(); // название
                $_REQUEST['order_title']    = $catalog->getOrderTitle(); // превьюшка
                $_REQUEST['description']       = $catalog->getDescription(); // тело новости
                $_REQUEST['keywords']    = $catalog->getKeywords(); // текст ссылки
                $_REQUEST['abbreviatura']        = $catalog->getAbbreviatura(); // ссылка
                $_REQUEST['modrewrite']        = $catalog->getModrewrite(); // название изображения
                $_REQUEST['urlpict'] = $catalog->getUrlpict();
                $_REQUEST['alt'] = $catalog->getAlt();
                $_REQUEST['title_flag'] = $catalog->getTitleFlag();
                $_REQUEST['rounded_flag'] = $catalog->getRoundedFlag();
                $_REQUEST['alt_flag'] = $catalog->getAltFlag();
                // если новость не скрыта
                if( $catalog->getHide() == 'show' ) {
                    $_REQUEST['hide'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hide'] = false; // снимаем чекбокс
                }
                $_REQUEST['idc'] = $id;
                $_REQUEST['idp'] = $idp;
                $_REQUEST['page'] = $_GET['page'];
            }
            $name               = new \dmn\classes\FieldText("name",
                                                            "Название",
                                                            true,
                                                            $_REQUEST['name']);
            $order_title        = new \dmn\classes\FieldText("order_title",
                                                            "Название заказа",
                                                            true,
                                                            $_REQUEST['order_title']);
            $description        = new \dmn\classes\FieldTextarea("description",
                                                            "Описание",
                                                            false,
                                                            $_REQUEST['description'],
                                                            '100',
                                                            '20');
            $keywords           = new \dmn\classes\FieldText("keywords",
                                                            "Ключевые слова",
                                                            false,
                                                            $_REQUEST['keywords']);
            $abbreviatura       = new \dmn\classes\FieldText("abbreviatura",
                                                            "Аббревиатура страны",
                                                            false,
                                                            $_REQUEST['abbreviatura']);
            $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
                                                            "Название для<br/>ReWrite",
                                                            false,
                                                            $_REQUEST['modrewrite']);
            $hide               = new \dmn\classes\FieldCheckbox("hide",
                                                            "Отображать",
                                                            $_REQUEST['hide']);
            $urlpict            = new \dmn\classes\FieldFile("urlpict",
                                                            "Большой флаг",
                                                            false,
                                                            $_FILES,
                                                            "imei_service/view/images/country_flag/");
            $alt                = new \dmn\classes\FieldText("alt",
                                                            "ALT-тег",
                                                            false,
                                                            $_REQUEST['alt']);
            $title_flag         = new \dmn\classes\FieldText("title_flag",
                                                            "Название страны",
                                                            false,
                                                            $_REQUEST['title_flag']);
            $rounded_flag       = new \dmn\classes\FieldFile("rounded_flag",
                                                            "Маленький флаг",
                                                            false,
                                                            $_FILES,
                                                            "imei_service/view/images/rounded_flag/");
            $alt_flag           = new \dmn\classes\FieldText("alt_flag",
                                                            "ALT-тег",
                                                            false,
                                                            $_REQUEST['alt_flag']);
            $idp          = new \dmn\classes\FieldHiddenInt("idp",
                                                            true,
                                                            $_REQUEST['idp']);
            $page               = new \dmn\classes\FieldHiddenInt("page",
                                                            false,
                                                            $_REQUEST['page']);
            $submitted      = new \dmn\classes\FieldHidden( "submitted",
                                                            true,
                                                            "yes" );
            // Форма
            $form               = new \dmn\classes\Form( array( "name"          => $name,
                                                                "order_title"   => $order_title,
                                                                "description"   => $description,
                                                                "keywords"      => $keywords,
                                                                "abbreviatura"  => $abbreviatura,
                                                                "modrewrite"    => $modrewrite,
                                                                "hide"          => $hide,
                                                                "urlpict"       => $urlpict,
                                                                "alt"           => $alt,
                                                                "title_flag"    => $title_flag,
                                                                "rounded_flag"  => $rounded_flag,
                                                                "alt_flag"      => $alt_flag,
                                                                "idp"           => $idp,
                                                                "page"          => $page,
                                                                "submitted"     => $submitted ),
                                                            "Редактировать",
                                                            "field");
//            echo "<tt><pre>".print_r($form, true)."</pre></tt>";
            $request->setObject('form', $form ); // выводим форму заново

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
                $urlpictTmp = $form->fields['urlpict']->getFilename();
                if( ! empty( $urlpictTmp ) ) {
                    // Удаляем старые изображения
                    if( file_exists(  "imei_service/view/images/country_flag/".$urlpictTmp ) ) {
                        @unlink( "imei_service/view/images/country_flag/".$urlpictTmp );
                    }
                }
                $rounded_flagTmp = $form->fields['rounded_flag']->getFilename();
                if( ! empty( $rounded_flagTmp ) ) {
                    // Удаляем старые изображения
                    if( file_exists(  "imei_service/view/images/rounded_flag/".$rounded_flagTmp ) ) {
                        @unlink( "imei_service/view/images/rounded_flag/".$rounded_flagTmp );
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                //
                //            $rawPos = \dmn\domain\News::findMaxPos(); // работает \imei_service\domain\News - получаем коллекцию
                //            $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
                $rawPhotoSettings = \dmn\domain\Catalog::findPhotoSetting(); // получаем массив с размерами фото
                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }

                // Изображение
                if( ! empty( $_FILES['urlpict']['name'] ) ) {
                    // Удаляем старые изображения
//                    echo "<tt><pre> urlpict - ".print_r( "imei_service/view/".$catalog->getUrlpict() , true)."</pre></tt>";
                    if( file_exists(  "imei_service/view/".$catalog->getUrlpict() ) ) {
                        @unlink( "imei_service/view/".$catalog->getUrlpict() );
                    }
                    // Новые изображения
                    $str = $form->fields['urlpict']->getFilename();
                    // если новое изображение присутствует
                    if( ! empty( $str ) ) {
                        $img = "images/country_flag/$str"; // большое изображение
                        // устанавливаем путь до большого изображения
                        $catalog->setUrlpict( $img );
                        // обновляем малое изображение
                    }
                }

                // Изображение
                if( ! empty( $_FILES['rounded_flag']['name'] ) ) {
//                    echo "<tt><pre> rounded - ".print_r( $catalog->getRoundedFlag() , true)."</pre></tt>";
                    // Удаляем старые изображения
                    if( file_exists(  "imei_service/view/".$catalog->getRoundedFlag() ) ) {
                        @unlink( "imei_service/view/".$catalog->getRoundedFlag() );
                    }
                    // Новые изображения
                    $str = $form->fields['rounded_flag']->getFilename();
                    // если новое изображение присутствует
                    if( ! empty( $str ) ) {
                        $rounded_img = "images/rounded_flag/$str"; // большое изображение
                        // устанавливаем изображение для услуги
                        $catalog->setRoundedFlag( $rounded_img );
                        // обновляем малое изображение
                    }
                }

                // устанавливаем название
                $catalog->setName( $form->fields['name']->value );
                // устанавливаем название для заказа
                $catalog->setOrderTitle( $form->fields['order_title']->value );
                // устанавливаем описание
                $catalog->setDescription( $form->fields['description']->value );
                // устанавливаем ключевые слова
                $catalog->setKeywords( $form->fields['keywords']->value );
                // устанавливаем аббревиатуру
                $catalog->setAbbreviatura( $form->fields['abbreviatura']->value );
                // устанавливаем флаг услуги
                $catalog->setModrewrite( $form->fields['modrewrite']->value );
//                // устанавливаем позицию
//                $catalog->setPos( $form->fields['pos']->value );
                // устанавливаем сокрытие/отображение
                $catalog->setHide( $showhide );
                // устанавливаем название основного изображения
                $catalog->setAlt( $form->fields['alt']->value );
                // устанавливаем название услуги
                $catalog->setTitleFlag( $form->fields['title_flag']->value );
                // устанавливаем название изображения
                $catalog->setAltFlag( $form->fields['alt_flag']->value );
                // устанавливаем родительский id
                $catalog->setIdParent( $form->fields['idp']->value );

                $this->reloadPage( 0, "dmn.php?cmd=Catalog&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>