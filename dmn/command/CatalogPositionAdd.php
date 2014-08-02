<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 12:32
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CatalogPosition' ) ) die();

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

/**
 * Class catalogPositionAdd
 * Добавление позиции к каталогу
 * @package dmn\command
 */
class catalogPositionAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $_REQUEST['idc'] = intval($_REQUEST['idc']);

        $operator       = new \dmn\classes\FieldTextarea( "operator",
                                            "Услуга",
                                            true,
                                            $_REQUEST["operator"],
                                            37,
                                            3 );
        $cost           = new \dmn\classes\FieldText("cost",
                                            "Стоимость",
                                            true,
                                            $_REQUEST['cost']);
        $timeconsume    = new \dmn\classes\FieldText("timeconsume",
                                            "Сроки выполнения",
                                            true,
                                            $_REQUEST['timeconsume']);
        $compatible    = new \dmn\classes\FieldText("compatible",
                                            "Совместимость",
                                            false,
                                            $_REQUEST['compatible']);
        $status         = new \dmn\classes\FieldText("status",
                                            "Статус аппарата",
                                            false,
                                            $_REQUEST['status']);
        $currency       = new \dmn\classes\FieldSelect("currency",
                                            "Валюта",
                                            array("RUR" => "RUR",
                                                "EUR" => "EUR",
                                                "USD" => "USD"),
                                            $_REQUEST['currency']);
        $hide           = new \dmn\classes\FieldCheckbox("hide",
                                            "Отображать",
                                            $_REQUEST['hide']);
        $putdate           = new \dmn\classes\FieldDatetime("putdate",
                                            "Дата добавления",
                                            $_REQUEST['putdate']);
        $idc     = new \dmn\classes\FieldHiddenInt("idc",
                                            true,
                                            $_REQUEST['idc']);
        $submitted          = new \dmn\classes\FieldHidden( "submitted",
                                            true,
                                            "yes" );
        $form           = new \dmn\classes\Form(array("operator"     => $operator,
                                                    "cost"          => $cost,
                                                    "timeconsume"   => $timeconsume,
                                                    "compatible"    => $compatible,
                                                    "status"        => $status,
                                                    "currency"      => $currency,
                                                    "hide"          => $hide,
                                                    "putdate"       => $putdate,
                                                    "idc"           => $idc,
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

                $rawPos = \dmn\domain\CatalogPosition::findMaxPos( $form->fields['idc']->value ); // работает \imei_service\domain\News - получаем коллекцию

                $position = $rawPos['pos'] + 1; // увеличиваем позицию на единицу
//                $rawPhotoSettings = \dmn\domain\Catalog::findPhotoSetting(); // получаем массив с размерами фото

                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }


                // получаем объект News без id - значит будет INSERT
                $catalogPositionObj = new \dmn\domain\CatalogPosition();
                // устанавливаем название
                $catalogPositionObj->setOperator( $form->fields['operator']->value );
                // устанавливаем стоимость
                $catalogPositionObj->setCost( $form->fields['cost']->value );
                // устанавливаем время выполнения
                $catalogPositionObj->setTimeconsume( $form->fields['timeconsume']->value );
                // устанавливаем совместимость
                $catalogPositionObj->setCompatible( $form->fields['compatible']->value );
                // устанавливаем статус
                $catalogPositionObj->setStatus( $form->fields['status']->value );
                // устанавливаем валюту
                $catalogPositionObj->setCurrency( $form->fields['currency']->value );
                // устанавливаем сокрытие/отображение
                $catalogPositionObj->setHide( $showhide );
                // устанавливаем позицию
                $catalogPositionObj->setPos( $position );
                // устанавливаем дату
                $catalogPositionObj->setPutdate( $form->fields['putdate']->getMysqlFormat() );
                // устанавливаем id каталога
                $catalogPositionObj->setIdCatalog( $form->fields['idc']->value );

//                echo "<tt><pre> pos - ".print_r($catalogPositionObj, true)."</pre></tt>";

                $this->reloadPage( 0, "dmn.php?cmd=CatalogPosition&idc=$_REQUEST[idc]&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>