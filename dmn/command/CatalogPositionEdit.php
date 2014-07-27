<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 13:42
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
// Подключаем функцию изменения размера изображения
require_once("dmn/view/utils/resizeImage.php");

class CatalogPositionEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        // получаем id_news редактируемой новости
        $idc = $request->getProperty( 'idc' );
        $idp = $request->getProperty( 'idp' ); // id родительского каталога
        if( $idc ) { // если передан id_news
            $catalogPosition = \dmn\domain\CatalogPosition::find( $idc ); // находим элементы по заданному id_news
            $catalogPositionDate = $catalogPosition->getPutdate();
//        echo "<tt><pre>".print_r($catalogPosition, true)."</pre></tt>";

            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {

                // получаем массив с датой в class.FieldDateTime затем из mktime формируем дату
                if( ! empty( $catalogPositionDate ) ) {
                    // месяц
                    $_REQUEST['putdate']['month']  = substr( $catalogPosition->getPutdate(), 5, 2 );
                    // день
                    $_REQUEST['putdate']['day']    = substr( $catalogPosition->getPutdate(), 8, 2 );
                    // год
                    $_REQUEST['putdate']['year']   = substr( $catalogPosition->getPutdate(), 0, 4 );
                    // часы
                    $_REQUEST['putdate']['hour']   = substr( $catalogPosition->getPutdate(), 11, 2 );
                    // минуты
                    $_REQUEST['putdate']['minute'] = substr( $catalogPosition->getPutdate(), 14, 2 );

                }

                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['operator']       = $catalogPosition->getOperator(); // название
                $_REQUEST['cost']           = $catalogPosition->getCost(); // превьюшка
                $_REQUEST['timeconsume']    = $catalogPosition->getTimeconsume(); // тело новости
                $_REQUEST['compatible']     = $catalogPosition->getCompatible(); // текст ссылки
                $_REQUEST['status']         = $catalogPosition->getStatus(); // ссылка
                $_REQUEST['currency']       = $catalogPosition->getCurrency(); // название изображения
                $_REQUEST['hide']           = $catalogPosition->getHide();
                $_REQUEST['pos']            = $catalogPosition->getPos();
                $_REQUEST['putdate']        = $catalogPosition->getPutdate();

                // если новость не скрыта
                if( $catalogPosition->getHide() == 'show' ) {
                    $_REQUEST['hide'] = true; // отмечаем чекбокс
                } else {
                    $_REQUEST['hide'] = false; // снимаем чекбокс
                }
                $_REQUEST['idc'] = $idc;
                $_REQUEST['idp'] = $idp;
                $_REQUEST['page'] = intval( $_GET['page'] );
            }
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
            $idp     = new \dmn\classes\FieldHiddenInt("idp",
                true,
                $_REQUEST['idp']);
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
                                                            "idp"           => $idp,
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

                // Выясняем, скрыта или открыта дректория
                if($form->fields['hide']->value) {
                    $showhide = "show";
                } else {
                    $showhide = "hide";
                }


                // устанавливаем название
                $catalogPosition->setOperator( $form->fields['operator']->value );
                // устанавливаем стоимость
                $catalogPosition->setCost( $form->fields['cost']->value );
                // устанавливаем время выполнения
                $catalogPosition->setTimeconsume( $form->fields['timeconsume']->value );
                // устанавливаем совместимость
                $catalogPosition->setCompatible( $form->fields['compatible']->value );
                // устанавливаем статус
                $catalogPosition->setStatus( $form->fields['status']->value );
                // устанавливаем валюту
                $catalogPosition->setCurrency( $form->fields['currency']->value );
                // устанавливаем сокрытие/отображение
                $catalogPosition->setHide( $showhide );
                // устанавливаем позицию
                $catalogPosition->setPos( $form->fields['pos']->value );
                // устанавливаем дату
                $catalogPosition->setPutdate( $form->fields['putdate']->getMysqlFormat() );
                // устанавливаем id каталога
                $catalogPosition->setIdCatalog( $form->fields['idc']->value );


                $this->reloadPage( 0, "dmn.php?cmd=CatalogPosition&idc=$_REQUEST[idc]&idp=$_REQUEST[idp]&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }
    }
}
?>