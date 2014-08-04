<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 13:39
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CartOrder' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );

class CartOrderEdit extends Command {

    function doExecute( \dmn\controller\Request $request ) {
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        // получаем id_news редактируемой новости
        $id = $request->getProperty( 'order_id' );
//        echo "<tt><pre>".print_r($id, true)."</pre></tt>";
        $items_id = $request->getProperty( 'items_id' );
//        echo "<tt><pre>".print_r($items_id, true)."</pre></tt>";
        if( $id ) { // если передан id
            $cartOrder = \dmn\domain\CartOrder::find( $id ); // находим элементы по заданному id
            $orderId = $cartOrder->getId(); // получаем order id
            $cartItems = \dmn\domain\CartItems::findByOrderId( $orderId, $items_id ); // находим элементы по заданному id
            $cartOrder_date = $cartOrder->getCreatedAt(); // получаем дату и время

            // если еще не передан запрос и форма не была отправлена
            if( empty( $_POST ) &&  $_POST['submitted'] != 'yes' ) {
                if( ! empty( $cartOrder_date ) ) {
                    // месяц
                    $_REQUEST['created_at']['month']  = substr( $cartOrder->getCreatedAt(), 5, 2 );
                    // день
                    $_REQUEST['created_at']['day']    = substr( $cartOrder->getCreatedAt(), 8, 2 );
                    // год
                    $_REQUEST['created_at']['year']   = substr( $cartOrder->getCreatedAt(), 0, 4 );
                    // часы
                    $_REQUEST['created_at']['hour']   = substr( $cartOrder->getCreatedAt(), 11, 2 );
                    // минуты
                    $_REQUEST['created_at']['minute'] = substr( $cartOrder->getCreatedAt(), 14, 2 );

                }
                // Добавляем в глобальный массив данные из запроса к БД
                $_REQUEST['firstname']          = $cartOrder->getFirstName(); // имя
                $_REQUEST['lastname']           = $cartOrder->getLastName(); // фамилия
                $_REQUEST['email']              = $cartOrder->getEmail(); // электронный адрес
                $_REQUEST['data']               = $cartOrder->getData(); // описание
                $_REQUEST['country']            = $cartOrder->getCountry(); // страна
                $_REQUEST['address']            = $cartOrder->getAddress(); // адрес
                $_REQUEST['city']               = $cartOrder->getCity(); // город
                $_REQUEST['zip_code']           = $cartOrder->getZipCode(); // индекс
                $_REQUEST['state']              = $cartOrder->getState(); // штат
                $_REQUEST['status']             = $cartOrder->getStatus(); // статус
                $_REQUEST['amount']             = $cartOrder->getAmount(); // сумма
                $_REQUEST['paypal_trans_id']    = $cartOrder->getPaypalTransId(); // ID paypal транзакции



                $_REQUEST['product_id']         = $cartItems->getProductId(); // название товара
                $_REQUEST['title']              = $cartItems->getTitle(); // название товара
                $_REQUEST['price']              = $cartItems->getPrice(); // название товара
                $_REQUEST['qty']                = $cartItems->getQty(); // название товара
            }

            $firstname          = new \dmn\classes\FieldText( "firstname",
                                                            "Имя",
                                                            false,
                                                           $_REQUEST['firstname'] );
            $lastname           = new \dmn\classes\FieldText( "lastname",
                                                            "Фамилия",
                                                            false,
                                                           $_REQUEST['lastname'] );
            $email              = new \dmn\classes\FieldTextEmail( "email",
                                                            "E-mail",
                                                            true,
                                                           $_REQUEST['email'] );
            $data               = new \dmn\classes\FieldTextarea( "data",
                                                            "Описание",
                                                            true,
                                                           $_REQUEST['data'],
                                                            40,
                                                            5,
                                                            false );
            $title              = new \dmn\classes\FieldTextarea( "title",
                                                            "Название",
                                                            true,
                                                           $_REQUEST['title'],
                                                            40,
                                                            5,
                                                            false );
            $country           = new \dmn\classes\FieldText( "country",
                                                            "Страна",
                                                            false,
                                                           $_REQUEST['country'] );
            $address           = new \dmn\classes\FieldText( "address",
                                                            "Адрес",
                                                            false,
                                                           $_REQUEST['address'] );
            $city               = new \dmn\classes\FieldText( "city",
                                                            "Город",
                                                            false,
                                                           $_REQUEST['city'] );
            $zip_code           = new \dmn\classes\FieldText( "zip_code",
                                                            "Индекс",
                                                            false,
                                                           $_REQUEST['zip_code'] );
            $state              = new \dmn\classes\FieldText( "state",
                                                            "Штат",
                                                            false,
                                                           $_REQUEST['state'] );
            $status             = new \dmn\classes\FieldText( "status",
                                                            "Статус",
                                                            false,
                                                           $_REQUEST['status'] );
            $amount             = new \dmn\classes\FieldText( "amount",
                                                            "Сумма",
                                                            true,
                                                           $_REQUEST['amount'] );
            $price              = new \dmn\classes\FieldText( "price",
                                                            "Стоимость",
                                                            true,
                                                           $_REQUEST['price'] );
            $qty                = new \dmn\classes\FieldTextInt( "qty",
                                                            "Количество",
                                                            true,
                                                           $_REQUEST['qty'] );
            $product_id         = new \dmn\classes\FieldText( "product_id",
                                                            "ID Товара",
                                                            false,
                                                           $_REQUEST['product_id'] );
            $paypal_trans_id    = new \dmn\classes\FieldText( "paypal_trans_id",
                                                            "ID Paypal",
                                                            false,
                                                           $_REQUEST['paypal_trans_id'] );
            $created_at         = new \dmn\classes\FieldDatetime("created_at",
                                                                "Дата заказа",
                                                             $_REQUEST['created_at'] );
            $page               = new \dmn\classes\FieldHiddenInt("page",
                                                                false,
                                                                $_GET['page']);
            $submitted          = new \dmn\classes\FieldHidden( "submitted",
                                                            true,
                                                            "yes" );

            // формируем форму
            $form = new \dmn\classes\Form( array(   "firstname"         => $firstname,
                                                    "lastname"          => $lastname,
                                                    "email"             => $email,
                                                    "data"              => $data,
                                                    "title"             => $title,
                                                    "country"           => $country,
                                                    "address"           => $address,
                                                    "city"              => $city,
                                                    "zip_code"          => $zip_code,
                                                    "state"             => $state,
                                                    "status"            => $status,
                                                    "amount"            => $amount,
                                                    "price"             => $price,
                                                    "qty"               => $qty,
                                                    "product_id"        => $product_id,
                                                    "paypal_trans_id"   => $paypal_trans_id,
                                                    "created_at"        => $created_at,
                                                    "page"              => $page,
                                                    "sumbitted"         => $submitted ),
                                                "Редактировать" ,
                                                "field" );





        }

        // если форма была передана
        if( ! empty( $_POST ) && $_POST['submitted'] == 'yes' ) {
//            echo "<tt><pre>".print_r($form->fields['created_at']->getMysqlFormat(), true)."</pre></tt>";
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


                // получаем объект CartOrder без id - значит будет INSERT
                $cartOrder->setFirstName( $form->fields['firstname']->value );
                $cartOrder->setLastName( $form->fields['lastname']->value );
                $cartOrder->setEmail( $form->fields['email']->value );
                $cartOrder->setData( $form->fields['data']->value );
                $cartOrder->setCountry( $form->fields['country']->value );
                $cartOrder->setAddress( $form->fields['address']->value );
                $cartOrder->setCity( $form->fields['city']->value );
                $cartOrder->setZipCode( $form->fields['zip_code']->value );
                $cartOrder->setState( $form->fields['state']->value );
                $cartOrder->setStatus( $form->fields['status']->value );
                $cartOrder->setAmount( $form->fields['amount']->value );
                $cartOrder->setPayPalTransId( $form->fields['paypal_trans_id']->value );
                $cartOrder->setCreatedAt( $form->fields['created_at']->getMysqlFormat() );



                $cartItems->setProductId( $form->fields['product_id']->value );
                $cartItems->setOrderId( $orderId );
                $cartItems->setTitle( $form->fields['title']->value );
                $cartItems->setPrice( $form->fields['price']->value );
                $cartItems->setQty( $form->fields['qty']->value );



                $this->reloadPage( 0, "dmn.php?cmd=CartOrder&page=$_GET[page]" ); // перегружаем страничку
                // возвращаем статус и переадресацию на messageSuccess
                return self::statuses( 'CMD_OK' );
            }
        } else {
            $request->setObject('form', $form ); // выводим форму заново
        }

    }

}
?>