<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 11:03
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );
if( ! defined( 'CartOrder' ) ) die();
require_once( 'dmn/view/utils/security_mod.php' );
// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );
// Подключаем вспомогательные классы
require_once( "dmn/classes.php" );
//// Подключаем функцию изменения размера изображения
//require_once("dmn/view/utils/resizeImage.php");

class CartOrderAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        $firstname          = new \dmn\classes\FieldText( "firstname",
                                                            "Имя",
                                                            false,
                                                            $_POST['firstname'] );
        $lastname           = new \dmn\classes\FieldText( "lastname",
                                                            "Фамилия",
                                                            false,
                                                            $_POST['lastname'] );
        $email              = new \dmn\classes\FieldTextEmail( "email",
                                                            "E-mail",
                                                            true,
                                                            $_POST['email'] );
        $data               = new \dmn\classes\FieldTextarea( "data",
                                                            "Описание",
                                                            true,
                                                            $_POST['data'],
                                                            40,
                                                            5,
                                                            false );
        $title              = new \dmn\classes\FieldTextarea( "title",
                                                            "Название",
                                                            true,
                                                            $_POST['title'],
                                                            40,
                                                            5,
                                                            false );
        $country           = new \dmn\classes\FieldText( "country",
                                                            "Страна",
                                                            false,
                                                            $_POST['country'] );
        $address           = new \dmn\classes\FieldText( "address",
                                                            "Адрес",
                                                            false,
                                                            $_POST['address'] );
        $city               = new \dmn\classes\FieldText( "city",
                                                            "Город",
                                                            false,
                                                            $_POST['city'] );
        $zip_code           = new \dmn\classes\FieldText( "zip_code",
                                                            "Индекс",
                                                            false,
                                                            $_POST['zip_code'] );
        $state              = new \dmn\classes\FieldText( "state",
                                                            "Штат",
                                                            false,
                                                            $_POST['state'] );
        $status             = new \dmn\classes\FieldText( "status",
                                                            "Статус",
                                                            false,
                                                            $_POST['status'] );
        $amount             = new \dmn\classes\FieldText( "amount",
                                                            "Сумма",
                                                            true,
                                                            $_POST['amount'] );
        $price              = new \dmn\classes\FieldText( "price",
                                                            "Стоимость",
                                                            true,
                                                            $_POST['price'] );
        $qty                = new \dmn\classes\FieldTextInt( "qty",
                                                            "Количество",
                                                            true,
                                                            $_POST['qty'] );
        $product_id         = new \dmn\classes\FieldText( "product_id",
                                                            "ID Товара",
                                                            false,
                                                            $_POST['product_id'] );
        $paypal_trans_id    = new \dmn\classes\FieldText( "paypal_trans_id",
                                                            "ID Paypal",
                                                            false,
                                                            $_POST['paypal_trans_id'] );
        $created_at         = new \dmn\classes\FieldDatetime("created_at",
                                                            "Дата заказа",
                                                            $_POST['created_at']);
        $page               = new \dmn\classes\FieldHiddenInt("page",
                                                            false,
                                                            $_REQUEST['page']);
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
                                                "Добавить" ,
                                                "field" );

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

                // получаем объект CartOrder без id - значит будет INSERT
                $cartOrder = new \dmn\domain\CartOrder();
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

                \dmn\domain\ObjectWatcher::instance()->performOperations();

                $orderId = $cartOrder->getId(); // получаем id только что вставленной позиции

                if( empty( $orderId ) ) {
                    throw new \dmn\base\AppException( "Error ", " while INSERT items to cart_items" );
                }

                // получаем объект CartItems без id - значит будет INSERT
                $cartItems = new \dmn\domain\CartItems();
                $cartItems->setProductId( $form->fields['product_id']->value );
                $cartItems->setOrderId( $orderId );
                $cartItems->setTitle( $form->fields['title']->value );
                $cartItems->setPrice( $form->fields['price']->value );
                $cartItems->setQty( $form->fields['qty']->value );

                $this->reloadPage( 0, "dmn.php?cmd=CartOrder&page=$_GET[page]" ); // перегружаем страничку
                return self::statuses( 'CMD_OK' );
            }

        } else {
            $request->setObject('form', $form );
        }
    }
}
?>