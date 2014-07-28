<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 23:03
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class ArtCatalogEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $id = $request->getProperty( 'idc' );
        $idp = $request->getProperty( 'idp' ); // id родительского каталога
//        echo "<tt><pre>".print_r($idp, true)."</pre></tt>";
        if( $id ) { // если передан id_news
            $catalog = \dmn\domain\ArtCatalog::find( $id ); // находим элементы по заданному id_news

            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {

                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['name']           = $catalog->getName(); // название
                $_REQUEST['description']    = $catalog->getDescription(); // тело новости
                $_REQUEST['keywords']       = $catalog->getKeywords(); // текст ссылки
                $_REQUEST['modrewrite']     = $catalog->getModrewrite(); // название изображения

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
            $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
                "Название для<br/>ReWrite",
                false,
                $_REQUEST['modrewrite']);
            $hide               = new \dmn\classes\FieldCheckbox("hide",
                "Отображать",
                $_REQUEST['hide']);
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
                    "description"   => $description,
                    "keywords"      => $keywords,
                    "modrewrite"    => $modrewrite,
                    "hide"          => $hide,
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
                // устанавливаем описание
                $catalog->setDescription( $form->fields['description']->value );
                // устанавливаем ключевые слова
                $catalog->setKeywords( $form->fields['keywords']->value );
                // устанавливаем флаг услуги
                $catalog->setModrewrite( $form->fields['modrewrite']->value );
                // устанавливаем позицию
                $catalog->setPos( $form->fields['pos']->value );
                // устанавливаем сокрытие/отображение
                $catalog->setHide( $showhide );
                // устанавливаем родительский id
                $catalog->setIdParent( $form->fields['idp']->value );

                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>