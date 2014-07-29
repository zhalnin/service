<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 19:03
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {

    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request            = \dmn\view\VH::getRequest();
    $catalogPositions   = $request->getObject( 'catalogPosition' );
    $idp                = $request->getProperty( 'idp' );
    $idc                = $request->getProperty( 'idc' );

//    echo "<tt><pre>".print_r($catalogPositions, true)."</pre></tt>";

    // Данные переменные определяют название страницы и подсказку
    $title      = 'Администрирование перечня услуг';
    $pageinfo   = '<p class=help>Здесь осуществляется администрирование
                            перечня услу по анлоку, добавление новых услуг
                            и позиций</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    $_GET['idp'] = intval($_GET['idp']);


    // Содержание страницы
    if( is_object( $catalogPositions ) ) {
        // Получаем содержимое текущей страницы
        $catalog = $catalogPositions->get_page();
    }
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>';
    echo "<a class=menu
                href=dmn.php?cmd=Catalog&idp=0&page=$_GET[page]>
                    Корневой каталог</a>-&gt;".
        \dmn\view\utils\navigation($idc, "", 'system_catalog', 'Catalog' ).
        "<a class=menu href=dmn.php?cmd=CatalogPosition&".
        "pact=add&".
        "idc=$idc&".
        "idp=$idc&".
        "page=$_GET[page]>Добавить позицию</a>";
    echo "</td></tr></table>";


    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $catalog ) ) {

//        echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
        // Выводим ссылки на другие страницы
        echo $catalogPositions;
        echo "<br /><br />";
        ?>
        <table width="100%"
               class="table"
               border="0"
               cellpadding="0"
               cellspacing="0">
            <tr class="header" align="center">
                <td width=100>Наименование</td>
                <td width=150>Стоимость</td>
                <td width=150>Совместимость</td>
                <td width=150>Сроки</td>
                <td width=100>Действия</td>
            </tr>
    <?php
    for($i = 0; $i < count($catalog); $i++) {

        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $url = "idc={$catalog[$i]['id_catalog']}&".
            "idp={$catalog[$i]['id_position']}&".
            "&page=$_GET[page]";
        if($catalog[$i]['hide'] == 'show') {
            $showhide = "<a href=?cmd=CatalogPosition&ppos=hide&$url
                                    title='Скрыть каталог'>
                                    Скрыть</a>";
            $style = "";
        } else  {
            $showhide = "<a href=?cmd=CatalogPosition&ppos=show&$url
                                    title='Отобразить каталог'>
                                    Отобразить</a> ";
            $style = " class=hiddenrow";
        }

//    echo "<tt><pre>".print_r($catalog[$i][urlpict], true)."</pre></tt>";

        // Выводим каталог
        echo "<tr $style>
                <td>{$catalog[$i]['operator']}</td>
                <td align=center>{$catalog[$i]['cost']}</td>
                <td align=center>{$catalog[$i]['compatible']}</td>
                <td align=center>{$catalog[$i]['timeconsume']}</td>
                <td align=center>
                 <a href=# onclick=\"show_detail( 'dmn.php?cmd=CatalogPosition&pact=detail&{$url}', 400, 250); return false\"
                             title='Детальный просмотр'>Просмотр</a><br/>
                    <a href=?cmd=CatalogPosition&ppos=up&$url>Вверх</a><br/>
                    $showhide<br/>
                    <a href=?cmd=CatalogPosition&pact=edit&$url title='Редактировать'>Редактировать</a><br/>
                    <a href=# onClick=\"delete_position('?cmd=CatalogPosition&pact=del&$url',".
            "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                    <a href=?cmd=CatalogPosition&ppos=down&$url>Вниз</a><br/></td>
            </tr>";
    }
    echo "</table><br>";
    }

    // Выводим ссылки на другие страницы
    echo $catalogPositions;

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