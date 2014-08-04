<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/08/14
 * Time: 13:16
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
if( ! defined( 'LoginAdmin' ) ) die();
try {
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    // подключаем помощник для вьюшки
    require_once( "dmn/view/ViewHelper.php" );

// получаем объект request
    $request    = \dmn\view\VH::getRequest();
// получаем объект Форму
    $form       = $request->getObject('form');
    $login   = $request->getObject( 'login' );
    // получаем сообщения об ошибках
    $feedback = $request->getFeedback();

    if( is_object( $login ) ) {
        // Получаем содержимое текущей страницы
        $account = $login->getPage();
    }

// Данные переменные определяют название страницы и подсказку.
    $title = 'Вход в CMS';
    $pageinfo = '<p class="help">Здесь вам необходимо ввести ваш логин и пароль
                                для входа в панель управления.</p>';
        if( is_object( $form ) ) {
            // Включаем заголовок страницы
            require_once("dmn/view/templates/topAuth.php");

                // Выводим HTML-форму
                $form->printForm();

                if( ! empty( $feedback ) ) { // Вывод сообщений об ошибках
                print "<div class='guestbook-error' style='color: rgb(255, 0, 0);'>";
                    print "<ul>\n";
                        print "<li>\n";
                            print $request->getFeedbackString('</li><li>');
                            print "</li>\n";
                        print "</ul>\n";
                    print "</div>";
                }
                echo "</div>";

            // Включаем завершение страницы
            require_once("dmn/view/templates/bottomAuth.php");
        }
} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>