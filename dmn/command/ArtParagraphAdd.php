<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 13:05
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'ArtParagraph' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once( "dmn/view/utils/resizeImage.php" );
require_once( "dmn/domain/ArtParagraph.php" );
require_once( "dmn/domain/ArtParagraphImg.php" );
//require_once( "dmn/mapper/ObjectWatcher.php" );

class ArtParagraphAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $name           = new \dmn\classes\FieldTextArea("name",
                                        "Содержимое",
                                        true,
                                        $_REQUEST['name'],
                                        50,
                                        15 );
        $namepict       = new \dmn\classes\FieldText("namepict",
                                        "Название изображения",
                                        false,
                                        $_REQUEST['namepict'],
                                        false);
        $alt            = new \dmn\classes\FieldText("alt",
                                        "ALT-тег",
                                        false,
                                        $_REQUEST['alt']);
        $big       = new \dmn\classes\FieldFile("big",
                                        "Изображение",
                                        false,
                                        $_FILES,
                                        "imei_service/view/files/article/",
                                        "par_");
        $type           = new \dmn\classes\FieldSelect("type",
                                        "Тип параграфа",
                                        array("text"        => "Параграф",
                                            "title_h1"      => "Заголовок H1",
                                            "title_h2"      => "Заголовок H2",
                                            "title_h3"      => "Заголовок H3",
                                            "title_h4"      => "Заголовок H4",
                                            "title_h5"      => "Заголовок H5",
                                            "title_h6"      => "Заголовок H6",
                                            "list"          => "Список"),
                                        $_REQUEST['type']);
        $align          = new \dmn\classes\FieldSelect("align",
                                        "Выравнивание параграфа",
                                        array("left"    => "Слева",
                                            "center"    => "По центру",
                                            "right"     => "Справа"),
                                        $_REQUEST['align']);
        $hidepict       = new \dmn\classes\FieldCheckbox("hidepict",
                                        "Отображать фото",
                                        $_REQUEST['hidepict']);
        $hide               = new \dmn\classes\FieldCheckbox("hide",
                                        "Отображать",
                                        $_REQUEST['hide']);
        $idpar                = new \dmn\classes\FieldHiddenInt("idpar",
                                        true,
                                        $_REQUEST['idpar']);
        $idp                = new \dmn\classes\FieldHiddenInt("idp",
                                        true,
                                        $_REQUEST['idp']);
        $idc               = new \dmn\classes\FieldHiddenInt("idc",
                                        true,
                                        $_REQUEST['idc']);
        $pos            = new \dmn\classes\FieldHidden("pos",
                                        false,
                                        $_REQUEST['pos']);
        $page               = new \dmn\classes\FieldHiddenInt("page",
                                        false,
                                        $_REQUEST['page']);
        $submitted          = new \dmn\classes\FieldHidden( "submitted",
                                        true,
                                        "yes" );
        // Форма
        $form           = new \dmn\classes\Form(array("name"        => $name,
                                                    "namepict"      => $namepict,
                                                    "alt"           => $alt,
                                                    "big"           => $big,
                                                    "hidepict"      => $hidepict,
                                                    "type"          => $type,
                                                    "align"         => $align,
                                                    "hide"          => $hide,
                                                    "idc"           => $idc,
                                                    "idp"           => $idp,
                                                    "pos"           => $pos,
                                                    "page"          => $page,
                                                    "submitted"     => $submitted ),
                                                "Добавить",
                                                "field");

//        echo "<tt><pre>".print_r($form->fields['pos']->value, true)."</pre></tt>";
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

//                Вставляем параграф
                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }

                $formPos = $form->fields['pos']->value;
                // если позиция меньше ноля
                if( empty( $formPos ) ) {
                    $paragraphObjNul = \dmn\domain\ArtParagraph::findMaxPos( $form->fields['idc']->value,
                                                                      $form->fields['idp']->value );
                    $position = $paragraphObjNul['pos'] + 1;
//                    echo "<tt><pre> nul - ".print_r($paragraphObjNul, true)."</pre></tt>";
                } elseif( $form->fields['pos']->value < 0 ) {
                    // находим такой параграф с id параграфа и каталога
                    $paragraphObjLs = \dmn\domain\ArtParagraph::find( $form->fields['idp']->value,
                                                                      $form->fields['idc']->value );
//                    echo "<tt><pre> less - ".print_r($paragraphObjLs, true)."</pre></tt>";
                    if( is_object( $paragraphObjLs ) ) {
                        foreach ( $paragraphObjLs as $paragraphObjL) {
                            $pos = $paragraphObjL->getPos() + 1; // получаем его позицию и инкрементируем
                            $paragraphObjL->setPos( $pos ); // обновляем значение
                        }

                    }
                    $position = 1; // позицию приравниваем к единице
                    \dmn\domain\ObjectWatcher::instance()->performOperations(); // выполняем UPDATE
                } else { // то добавляем параграф ниже выбранного
                    // находим такой параграф с id параграфа и каталога и позицией
                    $paragraphObjGr = \dmn\domain\ArtParagraph::findPos( $form->fields['idp']->value,
                                                                        $form->fields['idc']->value,
                                                                        $form->fields['pos']->value );
//                    echo "<tt><pre> gr - ".print_r($paragraphObjGr, true)."</pre></tt>";
                    if( is_object( $paragraphObjGr ) ) {
                        foreach( $paragraphObjGr as $paragraphObjG ) {
                            $pos =  $paragraphObjG->getPos() + 1;  // получаем позицию из формы и инкрементируем
                            $paragraphObjG->setPos( $pos ); // обновляем значение
                        }
                    }
                    $position = $form->fields['pos']->value + 1; // позицию приравниваем к значению из формы + один
                    \dmn\domain\ObjectWatcher::instance()->performOperations(); // выполняем UPDATE
//                                        echo "<tt><pre>".print_r($paragraphObjGr, true)."</pre></tt>";
                }

//                echo "<tt><pre>".print_r($position, true)."</pre></tt>";

                // получаем объект ArtParagraph без id - значит будет INSERT
                $paragraphObj = new \dmn\domain\ArtParagraph();
                // устанавливаем название
                $paragraphObj->setName( $form->fields['name']->value );
                // устанавливаем описание
                $paragraphObj->setType( $form->fields['type']->value );
                // устанавливаем описание
                $paragraphObj->setAlign( $form->fields['align']->value );
                // устанавливаем позицию
                $paragraphObj->setPos( $position );
                // устанавливаем сокрытие/отображение
                $paragraphObj->setHide( $showhide );
                // устанавливаем  id позиции
                $paragraphObj->setIdPosition( $form->fields['idp']->value );
                 // устанавливаем id каталога
                $paragraphObj->setIdCatalog( $form->fields['idc']->value );

                \dmn\domain\ObjectWatcher::instance()->performOperations();
                $paragraphId = $paragraphObj->getId(); // получаем id только что вставленной записи

                if( empty( $paragraphId ) ) {
                    throw new \dmn\base\AppException( "Error ", " while INSERT paragraph" );
                }

//                echo "<tt><pre>".print_r($paragraphId, true)."</pre></tt>";


//                Вставляем изображения параграфа
                // Изображение
                $img = $form->fields['big']->getFilename();
                if( ! empty( $img ) ) { // если изображение загружается
                    // Скрытая или открытая фотография
                    if($form->fields['hidepict']->value) $showhidepict = "show";
                    else $showhidepict = "hide";

                    $imgPos = \dmn\domain\ArtParagraphImg::findMaxPos( $form->fields['idc']->value, $form->fields['idp']->value ); // получаем позицию
                    $positionImg = $imgPos['pos'] + 1; // увеличиваем позицию на единицу
                    $rawPhotoSettings = \dmn\domain\ArtParagraphImg::findPhotoSetting(); // получаем массив с размерами фото
//                echo "<tt><pre>".print_r($rawPhotoSettings, true)."</pre></tt>";
                    $big = "files/article/$img";
                    $small = "files/article/s_$img";
                    \dmn\view\utils\resizeImg(  "imei_service/view/files/article/$img",
                                                "imei_service/view/files/article/s_$img",
                                                $rawPhotoSettings['width_faq'],
                                                $rawPhotoSettings['height_faq'] );

                // получаем объект ArtParagraphImg без id - значит будет INSERT
                $artParImg = new \dmn\domain\ArtParagraphImg();
                $artParImg->setName( $form->fields['namepict']->value );
                $artParImg->setAlt(  $form->fields['alt']->value );
                $artParImg->setSmall( $small );
                $artParImg->setBig( $big );
                $artParImg->setHide( $showhidepict );
                $artParImg->setPos( $positionImg );
                $artParImg->setIdPosition(  $form->fields['idp']->value );
                $artParImg->setIdCatalog( $form->fields['idc']->value );
                $artParImg->setIdParagraph( $paragraphId );

                }
                $this->reloadPage( 0, "dmn.php?cmd=ArtParagraph&idp=$_REQUEST[idp]&idc=$_REQUEST[idc]&page=$_GET[page]" ); // перегружаем страничку
//                 возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>