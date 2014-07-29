<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/07/14
 * Time: 15:47
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {

    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    require_once( "dmn/domain/CatalogPosition.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request    = \dmn\view\VH::getRequest();
    $catalogs   = $request->getObject( 'catalog' ); // получаем объект PagerMySQL
    $idp        = $request->getProperty( 'idp' );

//    echo "<tt><pre>".print_r($request, true)."</pre></tt>";

    // Данные переменные определяют название страницы и подсказку
    $title      = 'Администрирование перечня услуг';
    $pageinfo   = '<p class=help>Здесь осуществляется администрирование
                            перечня услу по анлоку, добавление новых услуг
                            и позиций</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Содержание страницы
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>';
    echo "<a class=menu
                href=dmn.php?cmd=Catalog&idp=0&page=$_GET[page]>
                    Корневой каталог</a>-&gt;".
        \dmn\view\utils\navigation($idp, "", 'system_catalog','Catalog').
        "<a class=menu href=dmn.php?cmd=Catalog&".
        "pact=add&".
        "idc=$idp&".
        "idp=$idp&".
        "page=$_GET[page]>Добавить подкаталог</a>";
    echo "</td></tr></table>";

    if( is_object( $catalogs ) ) {
        // Получаем содержимое текущей страницы
        $catalog = $catalogs->get_page();
    }
    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $catalog ) ) {

        // Выводим ссылки на другие страницы
        echo $catalogs;
        echo "<br /><br />";
        ?>
        <table width="100%"
               class="table"
               border="0"
               cellpadding="0"
               cellspacing="0">
            <tr class="header" align="center">
                <td align="center">Название</td>
                <td width="70" align="center">Позиции</td>
                <td align="center">Описание</td>
                <td width="20" align="center">Поз.</td>
                <td align="center">Фото</td>
                <td width="50" aligh="center">Действия</td>
            </tr>
    <?php
    for($i = 0; $i < count($catalog); $i++) {

        // подсчитываем количество позиций в каждом каталоге
        $pos = \dmn\domain\CatalogPosition::findCountPos( intval($catalog[$i][id_catalog]) );

        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $url = "idc={$catalog[$i][id_catalog]}&".
                "idp={$catalog[$i][id_parent]}&".
                "&page=$_GET[page]";
        if($catalog[$i]['hide'] == 'show') {
            $showhide = "<a href=?cmd=Catalog&ppos=hide&$url
                                    title='Скрыть каталог'>
                                    Скрыть</a>";
            $style = "";
        } else  {
            $showhide = "<a href=?cmd=Catalog&ppos=show&$url
                                    title='Отобразить каталог'>
                                    Отобразить</a> ";
            $style = " class=hiddenrow";
        }

        // Проверяем наличие изображения
        if($catalog[$i]['urlpict'] != '' &&
            $catalog[$i]['urlpict'] != '-'&&
            is_file("imei_service/view/".$catalog[$i]['urlpict'])) {
            $url_pict = "<b><a href=imei_service/view/{$catalog[$i][urlpict]}>есть</a></b>";
        } else {
            $url_pict = "нет";
        }

        // Выводим катало
        echo "<tr $style>
                <td><a href=dmn.php?cmd=Catalog&".
                "idp={$catalog[$i]['id_catalog']}&".
                "page=$_GET[page]>".htmlspecialchars( $catalog[$i][name] )."</a></td>
                <td align=center>
                        <a href=?cmd=CatalogPosition&idc={$catalog[$i]['id_catalog']}>".
                         "Позиции ($pos[count])</a></td>
                <td>
                        ".nl2br(\dmn\view\utils\printPage($catalog[$i]['description']))."</td>

                <td align=center>{$catalog[$i]['pos']}</td>
                <td align=center>$url_pict</td>

                <td align=center>
                    <a href=?cmd=Catalog&ppos=up&$url>Вверх</a><br/>
                    $showhide<br/>
                    <a href=?cmd=Catalog&pact=edit&$url title='Редактировать'>Редактировать</a><br/>
                    <a href=# onClick=\"delete_position('?cmd=Catalog&pact=del&$url',".
    "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                    <a href=?cmd=Catalog&ppos=down&$url>Вниз</a><br/></td>
            </tr>";
    }
    echo "</table><br>";
    }

    // Выводим ссылки на другие страницы
    echo $catalogs;

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