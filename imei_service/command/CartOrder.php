<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/07/14
 * Time: 17:50
 */

namespace imei_service\command;
error_reporting( E_ALL & ~E_NOTICE );
session_start();

require_once( "imei_service/command/Command.php" );
require_once( "imei_service/domain/CartOrder.php" );
require_once( "imei_service/domain/CartItems.php" );
require_once( "imei_service/view/utils/utils.checkEmail.php" );
require_once( "imei_service/classes/class.SendMail.php" );
require_once( "imei_service/classes/class.Cart.php" );


/**
 * Class CartOrder
 * Получает форму из cart, парсирует запрос и выполняет INSERT в
 * system_cart_order, system_cart_items
 * @package imei_service\command
 */
class CartOrder extends Command {

    function doExecute( \imei_service\controller\Request $request ) {
//        Добавляем в system_cart_orders
        $firstname          = 'anonymous';                          // имя
        $lastname           = 'anonymous';                          // фамилия
        $email              = $request->getProperty( 'email' );     // электронная почта
        $country            = '-';                                  // страна
        $address            = '-';                                  // адрес
        $city               = '-';                                  // город
        $zip_code           = '-';                                  // индекс
        $state              = '-';                                  // штат
        $status             = 'waiting';                             // статус заказа
        $amount             = $request->getProperty( 'subtotal' );  // общая сумма
        $paypal_trans_id    = '-';                                  // ID транзакции PayPal
        $created_at         = date('Y-m-d H:i:s');                  // дата и время создания заказа
        $data               = $request->getProperty( 'data' );      // данные в текстовом поле
        $email_admin        = 'imei_service@icloud.com';
        $type               = 'cart_order';
        $shipping           = 0.00;


        if( $request->getProperty( 'submitted') !== 'yes' ) { // если форма не отправлена
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $email ) ) { // если поле email пустое
            $request->addFeedback( 'Заполните поле "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( checkEmail( $email ) == false ) { // если email не надлежащего формата
            $request->addFeedback( 'Введите корректный адрес "Email"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
        if( empty( $data ) ) { // если поле данных описания предмета пустое
            $request->addFeedback( 'Заполните поле "Данных"' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }

        // запрос к БД для уточнения фактической стоимости и суммы заказа вцелом
        $costFromDb = \imei_service\classes\Cart::getPaymentAmountCorrectManual( $shipping, $_POST );

        if( $costFromDb === true ) { // если сумма заказа из БД совпадает с суммой заказа из формы
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
            //        $cartOrder->finder()->insert( $cartOrder );
            // или альтернативный метод через ObjectWatcher - выполнить надо любым из указанных способов
            // в командном классе, чтобы получить lastInsertId для следующего запроса INSERT
            \imei_service\domain\ObjectWatcher::instance()->performOperations();

            // получаем только что вставленный ID в таблицу system_cart_orders
            $order_id = $cartOrder->getId();

            // проходим в цикле, чтобы узнать количество добавляемых позиций
            foreach( $_POST as $key => $val ) {
                if( preg_match('|amount_(.*)|', $key, $match ) ) {
                    $count = $match[1];
                }
            }
            // проходим в цикле, чтобы инициализировать нужные нам переменные для вставки в system_cart_items
            for( $i=1; $i <= $count; $i++ ) {

                $item_number    = $_POST['item_number_'.$i];    // номер предмета
                $item_name      = $_POST['item_name_'.$i];      // наименование предмета
                $amount         = $_POST['amount_'.$i];         // стоимость предмета
                $quantity       = $_POST['quantity_'.$i];       // количество позиций одного предмета

                // создаем экземпляр класса CartItems
                // после него нет явного вызова операции INSERT, она происходит в контроллере
                new \imei_service\domain\CartItems( null,
                    $item_number,
                    $order_id,
                    $item_name,
                    $amount,
                    $quantity );

            }

            // после успешного добавления заказа и предметов закакза удаляем сессию
            //        session_unset();
            //        session_destroy();
            $_SESSION['cart_imei_service'] = array();
            $_SESSION['total_items_imei_service'] = 0;
            $_SESSION['total_price_imei_service'] = 0.00;


            $_POST['order_id'] = $order_id;
            $_POST['created_at'] = $created_at;
            $cart = $_POST;

            $commsManager = \imei_service\classes\MailConfig::get( $type );  // параметр - тип commsManager
            $commsManager->make(1)->email( $email_admin, $email, null, null, null, $type, null, null, $cart ); // отправляем письмо админу
            $commsManager->make(2)->email( $email_admin, $email, null, null, null, $type, null, null, $cart ); // отправляем письмо клиенту
            //
            // возвращаем статус успешного завершения и передаресуем на cartOrderSuccess
            return self::statuses( 'CMD_OK' );

        } else {
            $request->addFeedback( 'Форма не корректна, вернитесь в корзину и попробуйте отправить еще раз,<br />
                                    если ошибка повторится - удалите предметы из корзины и выберете их повторно' );
            return self::statuses( 'CMD_INSUFFICIENT_DATA' );
        }
    }
}

?>