<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 23:18
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class ArtCatalogAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $name               = new \dmn\classes\FieldText("name",
            "Название",
            true,
            $_POST['name']);
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
        $modrewrite         = new \dmn\classes\FieldTextEnglish("modrewrite",
            "Название для<br/>ReWrite",
            false,
            $_POST['modrewrite']);
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
            "Добавить",
            "field");

//        echo "<tt><pre>".print_r($form, true)."</pre></tt>";
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

                $rawPos = \dmn\domain\ArtCatalog::findMaxPos( $form->fields['idp']->value ); // работает \imei_service\domain\News - получаем коллекцию
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
                $catalogObj = new \dmn\domain\ArtCatalog();

                // устанавливаем название
                $catalogObj->setName( $form->fields['name']->value );
                // устанавливаем описание
                $catalogObj->setDescription( $form->fields['description']->value );
                // устанавливаем ключевые слова
                $catalogObj->setKeywords( $form->fields['keywords']->value );
                // устанавливаем флаг услуги
                $catalogObj->setModrewrite( $form->fields['modrewrite']->value );
                // устанавливаем позицию
                $catalogObj->setPos( $position );
                // устанавливаем сокрытие/отображение
                $catalogObj->setHide( $showhide );
                // устанавливаем родительский id
                $catalogObj->setIdParent( $form->fields['idp']->value );

                $this->reloadPage( 0, "dmn.php?cmd=ArtCatalog&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>