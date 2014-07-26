<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 12:21
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class CatalogAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $name               = new \dmn\classes\FieldText("name",
                                                        "Название",
                                                        true,
                                                        $_POST['name']);
        $order_title        = new \dmn\classes\FieldText("order_title",
                                                        "Название заказа",
                                                        true,
                                                        $_POST['order_title']);
        $description        = new \dmn\classes\FieldTextarea("description",
                                                        "Описание",
                                                        false,
                                                        $_POST['description'],
                                                        '100',
                                                        '20');
        $keywords           = new \dmn\classes\FieldText("keywords",
                                                        "Ключевые слова",
                                                        false,
                                                        $_POST['keywords']);
        $abbreviatura       = new \dmn\classes\FieldText("abbreviatura",
                                                        "Аббревиатура страны",
                                                        false,
                                                        $_POST['abbreviatura']);
        $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
                                                        "Название для<br/>ReWrite",
                                                        false,
                                                        $_POST['modrewrite']);
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
                                                        $_POST['alt']);
        $title_flag         = new \dmn\classes\FieldText("title_flag",
                                                        "Название страны",
                                                        false,
                                                        $_POST['title_flag']);
        $rounded_flag       = new \dmn\classes\FieldFile("rounded_flag",
                                                        "Маленький флаг",
                                                        false,
                                                        $_FILES,
                                                        "imei_service/view/images/rounded_flag/");
        $alt_flag           = new \dmn\classes\FieldText("alt_flag",
                                                        "ALT-тег",
                                                        false,
                                                        $_POST['alt_flag']);
        $id_parent          = new \dmn\classes\FieldHiddenInt("id_parent",
                                                        true,
                                                        $_REQUEST['id_parent']);
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
                                                            "modrewrite"    => $modrewrite,
                                                            "id_parent"     => $id_parent,
                                                            "page"          => $page,
                                                            "submitted"     => $submitted ),
                                                        "Добавить",
                                                        "field");

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

                $rawPos = \dmn\domain\Catalog::findMaxPos(); // работает \imei_service\domain\News - получаем коллекцию
                $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
                $rawPhotoSettings = \dmn\domain\Catalog::findPhotoSetting(); // получаем массив с размерами фото


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
                // Изображение большое
                $str = $form->fields['urlpict']->getFilename();
                //            echo "<tt><pre>".print_r($str, true)."</pre></tt>";
                if( ! empty( $str ) ) {
                    $img = "images/country_flag/$str";
//                    \dmn\view\utils\resizeImg(  "imei_service/view/files/news/$str",
//                        "imei_service/view/files/news/s_$str",
//                        $rawPhotoSettings['width_news'],
//                        $rawPhotoSettings['height_news'] );
                } else {
                    $img = "";
                }
                // Изображение малое
                $rounded_str = $form->fields['rounded_flag']->getFilename();
                //            echo "<tt><pre>".print_r($str, true)."</pre></tt>";
                if( ! empty( $rounded_flag ) ) {
                    $rounded_img = "images/country_flag/$rounded_str";
//                    \dmn\view\utils\resizeImg(  "imei_service/view/files/news/$str",
//                        "imei_service/view/files/news/s_$str",
//                        $rawPhotoSettings['width_news'],
//                        $rawPhotoSettings['height_news'] );
                } else {
                    $rounded_img = "";
                }


                // получаем объект News без id - значит будет INSERT
                $catalogObj = new \dmn\domain\Catalog();

                // устанавливаем название
                $catalogObj->setName( $form->fields['name']->value );
                // устанавливаем название для заказа
                $catalogObj->setOrderTitle( $form->fields['order_title']->value );
                // устанавливаем описание
                $catalogObj->setDescription( $form->fields['description']->value );
                // устанавливаем ключевые слова
                $catalogObj->setKeywords( $form->fields['keywords']->value );
                // устанавливаем аббревиатуру
                $catalogObj->setAbbreviatura( $form->fields['abbreviatura']->value );
                // устанавливаем флаг услуги
                $catalogObj->setModrewrite( $form->fields['modrewrite']->value );
                // устанавливаем позицию
                $catalogObj->setPos( $form->fields['pos']->value );
                // устанавливаем сокрытие/отображение
                $catalogObj->setHide( $form->fields['hide']->value );
                // устанавливаем путь до большого изображения
                $catalogObj->setUrlpict( $img );
                // устанавливаем название основного изображения
                $catalogObj->setAlt( $form->fields['alt']->value );
                // устанавливаем изображение для услуги
                $catalogObj->setRoundedFlag( $rounded_img );
                // устанавливаем название услуги
                $catalogObj->setTitleFlag( $form->fields['title_flag']->value );
                // устанавливаем название изображения
                $catalogObj->setAltFlag( $form->fields['alt_flag']->value );
                // устанавливаем родительский id
                $catalogObj->setIdParent( $id_parent );

                $this->reloadPage( 0, "dmn.php?cmd=Catalog&id_parent=$_REQUEST[id_parent]&page=$_GET[page]" ); // перегружаем страничку
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>