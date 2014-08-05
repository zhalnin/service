<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 31/07/14
 * Time: 23:51
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

class ArtParagraphEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";

        // получаем id_news редактируемой новости
        $pageo          = intval( $request->getProperty( 'page' ) ); // номер страницы в постраничной навигации
        $idpo           = intval( $request->getProperty( 'idp' ) );
        $idco           = intval( $request->getProperty( 'idc' ) );
        $idpho          = intval( $request->getProperty( 'idph' ) ); // id параграфа
        $idpar          = intval( $request->getProperty( 'idpar') ); // id родительского каталога ( если его нет, то FALSE === 0 )
        $paragraph      = \dmn\domain\ArtParagraph::find( $idpho, $idco, $idpo ); // находим параграф по заданному id
        $paragraphImg   = \dmn\domain\ArtParagraphImg::find( $idpho, $idco, $idpo ); // находим изображения параграфа по заданному id_news

//        echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($paragraphImg, true)."</pre></tt>";

        // если еще не передан запрос и форма не была отправлена
        if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {

            if( is_object( $paragraph ) ) { // если есть объект параграф
                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['name']    = $paragraph->getName(); // название
                $_REQUEST['type']    = $paragraph->getType(); // тиn
                $_REQUEST['align']   = $paragraph->getAlign(); // выравнивание
                // если параграф не скрыт
                if( $paragraph->getHide() == 'show' ) {
                    $_REQUEST['hide'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hide'] = false; // снимаем чекбокс
                }
                $_REQUEST['idc']    = $idco; // id каталога
                $_REQUEST['idp']    = $idpo; // id позиции
                $_REQUEST['page']   = $pageo; // номер страницы в навигации
            }

            if( is_object( $paragraphImg ) ) { // если есть объект изображения параграфа
                $_REQUEST['namepict']   = $paragraphImg->getName(); // название
                $_REQUEST['alt']        = $paragraphImg->getAlt(); // название изображения
                $_REQUEST['small']      = $paragraphImg->getSmall(); // маленькое изображение
                $_REQUEST['big']        = $paragraphImg->getBig(); // большое изображение
                // если изображение не скрыто
                if( $paragraphImg->getHide() == 'show' ) {
                    $_REQUEST['hidepict'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hidepict'] = false; // снимаем чекбокс
                }
                $_REQUEST['pos']        = $paragraphImg->getPos(); // позиция
                $_REQUEST['idp']        = $idpo; // id позици
                $_REQUEST['idc']        = $idco; // id каталога
                $_REQUEST['idph']       = $idpho; // id параграф
            }
        }

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
//        $pos            = new \dmn\classes\FieldHidden("pos",
//            false,
//            $_REQUEST['pos']);
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
//                                                    "pos"           => $pos,
                                                    "page"          => $page,
                                                    "submitted"     => $submitted ),
                                                "Редактировать",
                                                "field");
//            echo "<tt><pre>".print_r($form, true)."</pre></tt>";
            $request->setObject('form', $form ); // выводим форму заново

        // если форма была передана
        if( ! empty( $_POST ) && $_POST['submitted'] == 'yes' ) {
            // проверяем на наличие пустых полей
            $error = $form->check(); // сохраняем в переменную массив сообщений об ошибках
//            echo "<tt><pre>".print_r(! empty($error), true)."</pre></tt>";
            if( empty( $error ) ) { // если нет ошибок
                // Выясняем, скрыта или открыта дректория
                if( $form->fields['hide']->value == 'on' ) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }

                if( is_object( $paragraph ) ) {
                    // устанавливаем название
                    $paragraph->setName( $form->fields['name']->value );
                    // устанавливаем описание
                    $paragraph->setType( $form->fields['type']->value );
                    // устанавливаем описание
                    $paragraph->setAlign( $form->fields['align']->value );
                    // устанавливаем сокрытие/отображение
                    $paragraph->setHide( $showhide );
                    // устанавливаем  id позиции
                    $paragraph->setIdPosition( $idpo );
                    // устанавливаем id каталога
                    $paragraph->setIdCatalog( $idco );
                }


                // Скрытая или открытая позиция
                if( $form->fields['hidepict']->value == 'on' ) $showhidepict = "show";
                else $showhidepict = "hide";

                // Формируем SQL-запрос на редактирование фото
                if( ! empty( $_FILES['big']['name'] ) ) {
                    // Новые изображения
                    $img = $form->fields['big']->getFilename();
                    $rawPhotoSettings = \dmn\domain\ArtParagraphImg::findPhotoSetting(); // работает \imei_service\domain\News - получаем коллекцию
                    if( is_object( $paragraphImg ) ) { // если существует, то UPDATE
                        $bigOld = $paragraphImg->getBig();
                        $smallOld = $paragraphImg->getSmall();
                        if( ! empty( $bigOld ) ) {
                            // Удаляем старые изображения
                            if( file_exists(  "imei_service/view/".$bigOld ) ) {
                                @unlink( "imei_service/view/".$bigOld );
                            }
                        }
                        if( ! empty( $smallOld ) ){
                            // Удаляем старые изображения
                            if( file_exists(  "imei_service/view/".$smallOld ) ) {
                                @unlink( "imei_service/view/".$smallOld );
                            }
                        }


                        if( ! empty( $img ) ) {
                            $big = "files/article/$img";
                            $small = "files/article/s_$img";
                            \dmn\view\utils\resizeImg(  "imei_service/view/files/article/$img",
                                "imei_service/view/files/article/s_$img",
                                $rawPhotoSettings['width'],
                                $rawPhotoSettings['height'] );
                            $paragraphImg->setSmall( $small );
                            $paragraphImg->setBig( $big );
                        }

                        // получаем объект ArtParagraphImg\
                        $paragraphImg->setName( $form->fields['namepict']->value );
                        $paragraphImg->setAlt(  $form->fields['alt']->value );
                        $paragraphImg->setHide( $showhidepict );
                        $paragraphImg->setIdPosition( $idpo );
                        $paragraphImg->setIdCatalog( $idco );
                        $paragraphImg->setIdParagraph( $idpho );

                    } else { // если не существует - INSERT
                        $paragraphImgNew = new \dmn\domain\ArtParagraphImg(); // получаем объект ArtParagraphImg
                        $imgPos = \dmn\domain\ArtParagraphImg::findMaxPos( $idco, $idpo  ); // получаем позицию
                        $positionImg = $imgPos['pos'] + 1; // увеличиваем позицию на единицу

                        if( ! empty( $img ) ) {
                            $big = "files/article/$img";
                            $small = "files/article/s_$img";
                            \dmn\view\utils\resizeImg(  "imei_service/view/files/article/$img",
                                "imei_service/view/files/article/s_$img",
                                $rawPhotoSettings['width'],
                                $rawPhotoSettings['height'] );
                            $paragraphImgNew->setSmall( $small );
                            $paragraphImgNew->setBig( $big );
                        }
                        $paragraphImgNew->setName( $form->fields['namepict']->value );
                        $paragraphImgNew->setAlt(  $form->fields['alt']->value );
                        $paragraphImgNew->setHide( $showhidepict );
                        $paragraphImgNew->setPos( $positionImg );
                        $paragraphImgNew->setIdPosition( $idpo );
                        $paragraphImgNew->setIdCatalog( $idco );
                        $paragraphImgNew->setIdParagraph( $idpho );
                    }
                } else {
                    $paragraphImg->setHide( $showhidepict );
                }
                $this->reloadPage( 0, "dmn.php?cmd=ArtParagraph&idp=$idpo&idc=$idco&idph=$idpho&page=$pageo" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            } else {
                if( is_array( $error ) ) { // если это массив
                    foreach ( $error as $er ) { // проходим в цикле
                        $request->addFeedback( $er ); // добавляем сообщение об ошибке
                    }
                    $bigTmp = $form->fields['big']->getFilename();
                    if( ! empty( $bigTmp ) ) {
                        // Удаляем старые изображения
                        if( file_exists(  "imei_service/view/files/article/".$bigTmp ) ) {
                            @unlink( "imei_service/view/files/article/".$bigTmp );
                        }
                    }
                }
                $request->setObject('form', $form ); // выводим форму заново

                return self::statuses( 'CMD_INSUFFICIENT_DATA' ); // возвращаем статус обработки с ошибкой
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>