<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 14:21
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
    ?>
    <p class=help>
        ITALIC: <a href=# onclick="javascript:tagIns('[i]','[/i]'); return false;">[i][/i]</a><br/>
        BOLD: <a href=# onclick="javascript:tagIns('[b]','[/b]'); return false;">[b][/b]</a><br/>
        UNDERLINE: <a href=# onclick="javascript:tagIns('[ins]','[/ins]'); return false;">[ins][/ins]</a><br/>
        URL: <a href=# onclick="javascript:tagIns('[url]','[/url]'); return false;">[url][/url]</a><br/>
        IMG: <a href=# onclick="javascript:tagIns('[img]','[/img]'); return false;">[img][/img]</a><br/>
        COLOR: <a href=# onclick="javascript:tagIns('[color]','[/color]'); return false;">[color][/color]</a><br/>
        SIZE: <a href=# onclick="javascript:tagIns('[size]','[/size]'); return false;">[size][/size]</a><br/>
        MAIL: <a href=# onclick="javascript:tagIns('[mail]','[/mail]'); return false;">[mail][/mail]</a><br/>
    </p>
    <?php
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