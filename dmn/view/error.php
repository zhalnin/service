<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 20:08
 */
namespace dmn\view;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/classes.php" );
// Include exception for error handling
require_once( "dmn/base/Exceptions.php" );
// подключаем помощник для вьюшки
require_once( "dmn/view/ViewHelper.php" );

// получаем объект request
$request        = \dmn\view\VH::getRequest();

// получаем объект Форму
$form = $request->getObject('form');
// получаем сообщения об ошибках
$feedback = $request->getFeedback();
//echo "<tt><pre>".print_r($form, true)."</pre></tt>";

try {
    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    echo "<p><a href='#' onclick='history.back()'>Назад</a></p>";

    if( is_object( $form ) ) {
        // Выводим HTML-форму
        $form->printForm();
    }

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
    require_once("dmn/view/templates/bottom.php");

} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>