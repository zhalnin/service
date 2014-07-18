<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/07/14
 * Time: 13:09
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/classes/class.Cart.php" );
require_once( "imei_service/domain/CartOrder.php" );
require_once( "imei_service/domain/CartItems.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/domain/ObjectWatcher.php" );

class Paypal  extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $action             = $request->getProperty('act');
        $paypal_email       = "zhalninpal-facilitator@me.com";
        $paypal_currency    = 'RUB';
        $shipping           = 0.00;

        if( ! empty( $action ) && $action == 'paypalThankYou' ) {

            return self::statuses( 'CMD_OK' );

        } else {
            $postdata="";
            foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
            $postdata .= "cmd=_notify-validate";
            $curl = curl_init("https://www.sandbox.paypal.com/cgi-bin/webscr");
            curl_setopt ($curl, CURLOPT_HEADER, 0);
            curl_setopt ($curl, CURLOPT_POST, 1);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
            $response = curl_exec ($curl);


            curl_close ($curl);
            if ($response != "VERIFIED") die("You should not do that ...");
//            $item_name          = $_POST['item_name'];
//            $item_number        = $_POST['item_number'];
//            $payment_status     = $_POST['payment_status'];
//            $payment_amount     = $_POST['mc_gross'];
//            $payment_currency   = $_POST['mc_currency'];
//            $txn_id             = $_POST['txn_id'];
//            $receiver_email     = $_POST['receiver_email'];
//            $payer_email        = $_POST['payer_email'];

            if( $_POST['payment_status'] == 'Completed'
                && \imei_service\classes\Cart::getNoPaypalTransId( $_POST['txn_id'] )
                && $paypal_email == $_POST['receiver_email']
                && $paypal_currency == $_POST['mc_currency']
                && \imei_service\classes\Cart::getPaymentAmountCorrect( $shipping, $_POST )
            ) {
                $firstname          = $_POST['first_name'];     // фамилия
                $lastname           = $_POST['last_name'];      // имя
                $email              = $_POST['payer_email'];    // email покупателя
                $data               = $_POST['memo'];           // сообщение для продавца
                $country            = $_POST['address_country'];// страна
                $address            = $_POST['address_street']; // адрес
                $city               = $_POST['address_city'];   // город
                $zip_code           = $_POST['address_zip'];    // индекс
                $state              = $_POST['address_state'];  // штат
                $status             = $_POST['payment_status']; // статус
                $amount             = $_POST['mc_gross'];       // общая сумма
                $paypal_trans_id    = $_POST['txn_id'];         // id транзакции PayPal
                $created_at         = date( 'Y-m-d H:i:s' );    // дата создания

                if( empty( $data ) ) {
                    $data = '-';
                }

                // создаем экземпляр класса CartOrder
                $cartOrder = new \imei_service\domain\CartOrder( null,
                    $firstname,
                    $lastname,
                    $email,
                    $data,
                    $country,
                    $address,
                    $city,
                    $zip_code,
                    $state,
                    $status,
                    $amount,
                    $paypal_trans_id,
                    $created_at);

                // выполняем операцию на созданным классом - \imei_service\mapper\DomainObjectAssembler
                $cartOrder->finder()->insert( $cartOrder );
                // или альтернативный метод через ObjectWatcher - выполнить надо любым из указанных способов
                // в командном классе, чтобы получить lastInsertId для следующего запроса INSERT
                // \imei_service\domain\ObjectWatcher::instance()->performOperations();

                // получаем только что вставленный ID в таблицу system_cart_orders
                $order_id = $cartOrder->getId();


                // проходим в цикле, чтобы инициализировать нужные нам переменные для вставки в system_cart_items
                for( $i=1; $i <= $_POST['num_cart_items']; $i++ ) {
                    // инициализируем две переменные из строки, типа: 36_2
                    list($id_catalog, $position ) = explode( '_', $_POST["item_number{$i}"] );
                    // получаем предмет по позиции и id каталога
                    $product = \imei_service\classes\Cart::getProduct( $position, $id_catalog );
                    $item_number = $_POST["item_number{$i}"];   // номер единицы
//                $order_id - выше был;                     // id вставленного заказа
                    $item_name = $product['operator'];          // наименование единицы
                    $amount = $product['cost'];                 // стоимость единицы
                    $quantity = $_POST["quantity{$i}"];         // количество позиций одной единицы


                    // создаем экземпляр класса CartItems
                    // после него нет явного вызова операции INSERT, она происходит в контроллере
                    new \imei_service\domain\CartItems( null,
                        $item_number,
                        $order_id,
                        $item_name,
                        $amount,
                        $quantity );

                }

            } else {
                file_put_contents('error_payment.txt',"Проверить статус платежа {$_POST['txn_id']}"."\n",FILE_APPEND );
            }

             return self::statuses( 'CMD_OK' );
        }

    }
}
?>