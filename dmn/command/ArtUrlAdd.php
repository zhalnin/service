<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 17:49
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class ArtUrlAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $name               = new \dmn\classes\FieldText("name",
                                                        "Название",
                                                        true,
                                                        $_POST['name']);
        $url                = new \dmn\classes\FieldText("url",
                                                        "URL",
                                                        true,
                                                        $_POST['url']);
        $keywords           = new \dmn\classes\FieldText("keywords",
                                                        "Ключевые слова",
                                                        false,
                                                        $_POST['keywords']);
        $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
                                                        "Название для<br/>ReWrite",
                                                        false,
                                                        $_POST['modrewrite']);
        $hide               = new \dmn\classes\FieldCheckbox("hide",
                                                        "Отображать",
                                                        $_REQUEST['hide']);
        $idpar                = new \dmn\classes\FieldHiddenInt("idpar",
                                                        true,
                                                        $_REQUEST['idpar']);
        $page               = new \dmn\classes\FieldHiddenInt("page",
                                                        false,
                                                        $_REQUEST['page']);
        $submitted          = new \dmn\classes\FieldHidden( "submitted",
                                                        true,
                                                        "yes" );
        // Форма
        $form               = new \dmn\classes\Form( array( "name"          => $name,
                                                            "url"           => $url,
                                                            "keywords"      => $keywords,
                                                            "modrewrite"    => $modrewrite,
                                                            "hide"          => $hide,
                                                            "idpar"         => $idpar,
                                                            "page"          => $page,
                                                            "submitted"     => $submitted ),
                                                        "Добавить",
                                                        "field");

//        echo "<tt><pre>".print_r($form, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
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

                $rawPos = \dmn\domain\ArtUrl::findMaxPos( $form->fields['idpar']->value ); // работает \imei_service\domain\News - получаем коллекцию
//                echo "<tt><pre>".print_r($rawPos, true)."</pre></tt>";
//                echo "<tt><pre>".print_r($form->fields['idp']->value, true)."</pre></tt>";
                $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу

                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }

                // получаем объект News без id - значит будет INSERT
                $catalogObj = new \dmn\domain\ArtUrl();

                // устанавливаем название
                $catalogObj->setName( $form->fields['name']->value );
                // устанавливаем описание
                $catalogObj->setUrl( $form->fields['url']->value );
                // устанавливаем ключевые слова
                $catalogObj->setKeywords( $form->fields['keywords']->value );
                // устанавливаем флаг услуги
                $catalogObj->setModrewrite( $form->fields['modrewrite']->value );
                // устанавливаем позицию
                $catalogObj->setPos( $position );
                // устанавливаем сокрытие/отображение
                $catalogObj->setHide( $showhide );
                // устанавливаем родительский id
                $catalogObj->setIdCatalog( $form->fields['idpar']->value );

                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idpar=$_REQUEST[idpar]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>