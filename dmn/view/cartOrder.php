<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 16:52
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);

try {

    require_once( "dmn/classes/class.PagerMysqlTwoTables.php" );
    require_once( "dmn/command/Command.php" );
    require_once( "dmn/base/Registry.php" );
    require_once( "dmn/domain/CartOrder.php" );
    require_once( "dmn/view/utils/printPage.php" );


    // Данные переменные определяют название страницы и подсказку
    $title      = 'Управление блоком "Заказов"';
    $pageinfo   = '<p class=help>Здесь можно редактировать или удалить уже
                    существующий заказ.</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Содержание страницы

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляеи объект постраничной навигации
    $obj = new \dmn\classes\PagerMysqlTwoTables(array('system_cart_orders','system_cart_items'),
        "",
        "",
        $pnumber,
        $page_link,
        "&cmd=CartOrder",
        array('id','order_id') );
//    echo "<tt><pre>".print_r($obj->getPage(), true)."</pre></tt>";
//    echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
    // Добавить блок
    echo "<a href=?cmd=CartOrder&pact=add&page=$_GET[page]
                    title=Добавить блок заказа>
                    Добавить блок заказа</a><br><br>";

    // Получаем содержимое текущей страницы
//    $cartOrder = $obj->get_page();
    $cartOrder = $obj->getPage();
//    echo "<tt><pre>".print_r( $cartOrder, true )."</pre></tt>";
    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $cartOrder ) ) {
        // Выводим ссылки на другие страницы
        echo $obj;
        echo "<br /><br />";
        ?>
        <table width="100%"
               class="table"
               border="0"
               cellpadding="0"
               cellspacing="0">
            <tr class="header" align="center">
                <td width="10%">Дата</td>
                <td width="5%">Заказ</td>
                <td width="10%">Email</td>
                <td >Описание</td>
                <td width="7%">Стоимость</td>
                <td width="10%">PayPal</td>
                <td width="7%">Статус</td>
                <td width="7%">Действия</td>
            </tr>
    <?php
    for($i = 0; $i < count($cartOrder); $i++) {
        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $url = "order_id={$cartOrder[$i][order_id]}&items_id={$cartOrder[$i][id]}&page=$_GET[page]";

        // Преобразуем дату из формата MySQL YYYY-MM-DD hh:mm:ss
        // в формат DD.MM.YYYY hh:mm:ss
        list($date, $time) = explode(" ", $cartOrder[$i]['created_at']);
        list($year, $month, $day) = explode("-", $date);
        $cartOrder[$i]['created_at'] = "$day.$month.$year $time";

        // Выводим новость
        echo "<tr>
                        <td><p align='center'>{$cartOrder[$i][created_at]}</td>
                        <td align=center>{$cartOrder[$i][order_id]}</td>
                        <td align=center>{$cartOrder[$i][email]}</td>
                        <td align=center>{$cartOrder[$i]['title']}<br /><br />{$cartOrder[$i]['data']}</td>
                        <td align=center>{$cartOrder[$i][price]}</td>
                        <td align=center>{$cartOrder[$i][paypal_trans_id]}</td>
                        <td align=center>{$cartOrder[$i][status]}</td>

                        <td align=center>
                            <a href=# onclick=\"show_detail( '?cmd=CartOrder&pact=detail&{$url}', 400, 450); return false\"
                             title='Детальный просмотр'>Просмотр</a><br/>
                            <a href=?cmd=CartOrder&pact=edit&$url title='Редактировать текст новости'>Редактировать</a><br/>
                            <a href=# onClick=\"delete_position('?cmd=CartOrder&pact=del&$url',".
            "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                      </td>


                    </tr>";
    }
    echo "</table><br>";
    }

    // Выводим ссылки на другие страницы
    echo $obj;

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