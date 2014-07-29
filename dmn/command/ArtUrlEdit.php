<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 18:29
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class ArtUrlEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $idp = $request->getProperty( 'idp' );
        $idc = $request->getProperty( 'idc' ); // id родительского каталога
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        if( $idp ) { // если передан id_news
            $catalog = \dmn\domain\ArtUrl::find( $idp ); // находим элементы по заданному id_news
            echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {

                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['name']           = $catalog->getName(); // название
                $_REQUEST['url']            = $catalog->getUrl(); // тело новости
                $_REQUEST['keywords']       = $catalog->getKeywords(); // текст ссылки
                $_REQUEST['modrewrite']     = $catalog->getModrewrite(); // название изображения

                // если новость не скрыта
                if( $catalog->getHide() == 'show' ) {
                    $_REQUEST['hide'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hide'] = false; // снимаем чекбокс
                }
                $_REQUEST['idc'] = $idc;
                $_REQUEST['idp'] = $idp;
                $_REQUEST['page'] = $_GET['page'];
            }
            $name               = new \dmn\classes\FieldText("name",
                "Название",
                true,
                $_REQUEST['name']);
            $url                = new \dmn\classes\FieldText("url",
                "URL",
                false,
                $_REQUEST['url']);
            $keywords           = new \dmn\classes\FieldText("keywords",
                "Ключевые слова",
                false,
                $_REQUEST['keywords']);
            $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
                "Название для<br/>ReWrite",
                false,
                $_REQUEST['modrewrite']);
            $hide               = new \dmn\classes\FieldCheckbox("hide",
                "Отображать",
                $_REQUEST['hide']);
            $idc          = new \dmn\classes\FieldHiddenInt("idc",
                true,
                $_REQUEST['idc']);
            $page               = new \dmn\classes\FieldHiddenInt("page",
                false,
                $_REQUEST['page']);
            $submitted      = new \dmn\classes\FieldHidden( "submitted",
                true,
                "yes" );
            // Форма
            $form               = new \dmn\classes\Form( array( "name"          => $name,
                                                                "url"           => $url,
                                                                "keywords"      => $keywords,
                                                                "modrewrite"    => $modrewrite,
                                                                "hide"          => $hide,
                                                                "idc"           => $idc,
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
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            } else {

                //
                //            $rawPos = \dmn\domain\News::findMaxPos(); // работает \imei_service\domain\News - получаем коллекцию
                //            $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }

//                echo "<tt><pre>".print_r( $form , true)."</pre></tt>";
                // устанавливаем название
                $catalog->setName( $form->fields['name']->value );
                // устанавливаем ссылку
                $catalog->setUrl( $form->fields['url']->value );
                // устанавливаем ключевые слова
                $catalog->setKeywords( $form->fields['keywords']->value );
                // устанавливаем флаг услуги
                $catalog->setModrewrite( $form->fields['modrewrite']->value );
//                // устанавливаем позицию
//                $catalog->setPos( $form->fields['pos']->value );
                // устанавливаем сокрытие/отображение
                $catalog->setHide( $showhide );
                // устанавливаем родительский id
                $catalog->setIdCatalog( $form->fields['idc']->value );

                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idpar=$_REQUEST[idc]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>