<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 18:53
 */
namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
//    require_once( "dmn/domain/CatalogPosition.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request    = \dmn\view\VH::getRequest();
//    $idc        = $request->getProperty('id_catalog');
    $idpar      = intval( $request->getProperty( 'idpar' ) );
    $artCatalog = $request->getObject( 'artCatalog' );  // получаем объект PagerMySQL

//    echo "<tt><pre>".print_r($request, true)."</pre></tt>";

    // Данные переменные определяют название страницы и подсказку
    $title      = 'Администрирование перечня услуг';
    $pageinfo   = '<p class=help>Здесь осуществляется администрирование
                            перечня услу по анлоку, добавление новых услуг
                            и позиций</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

//    $_GET['idpar'] = intval($_GET['idpar']);

    // Содержание страницы
    if( is_object( $artCatalog ) ) {
        // Получаем содержимое текущей страницы
        $catalog = $artCatalog->get_page();
    }
//    echo "<tt><pre>".print_r($idpar, true)."</pre></tt>";
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>';
    echo "<a class=menu
                href=dmn.php?cmd=ArtCatalog&idpar=0>
                    Корневой каталог</a>-&gt;".
        \dmn\view\utils\navigation($idpar, "", 'system_menu_catalog','ArtCatalog').
        "<a class=menu href=dmn.php?cmd=ArtCatalog&".
        "pact=add&".
        "idc=$_GET[idc]&".
        "idp=$_GET[idp]&".
        "idpar=$_GET[idpar]&".
        "page=$_GET[page]>Добавить подкаталог</a>";
    echo "</td></tr></table>";


    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $catalog ) ) {
//        echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
        // Выводим ссылки на другие страницы
        echo $artCatalog;
        echo "<br /><br />";
        ?>
        <table width="100%"
        class="table"
        border="0"
        cellpadding="0"
        cellspacing="0">
        <tr class="header" align="center">
            <td align="center">Название</td>
            <td align="center">Описание</td>
            <td width="20" align="center">Поз.</td>
            <td width="50" align="center">Действия</td>
        </tr>
        <?php
        for($i = 0; $i < count($catalog); $i++) {

            // Если новость отмечена как невидимая (hide='hide'), выводим
            // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
            $url = "idc={$catalog[$i][id_catalog]}&".
                "idpar={$catalog[$i][id_parent]}&".
                "&page=$_GET[page]";
            if($catalog[$i]['hide'] == 'show') {
                $showhide = "<a href=?cmd=ArtCatalog&ppos=hide&$url
                                    title='Скрыть каталог'>
                                    Скрыть</a>";
                $style = "";
            } else  {
                $showhide = "<a href=?cmd=ArtCatalog&ppos=show&$url
                                    title='Отобразить каталог'>
                                    Отобразить</a> ";
                $style = " class=hiddenrow";
            }


            // Выводим каталог
            echo "<tr $style>
                <td><a href=dmn.php?cmd=ArtCatalog&".
                "idpar={$catalog[$i]['id_catalog']}&".
                "page=$_GET[page]>".htmlspecialchars( $catalog[$i][name] )."</a></td>
                <td>
                        ".nl2br(\dmn\view\utils\printPage($catalog[$i]['description']))."</td>
                <td align=center>{$catalog[$i]['pos']}</td>
                <td align=center>
                    <a href=?cmd=ArtCatalog&ppos=up&$url>Вверх</a><br/>
                    $showhide<br/>
                    <a href=?cmd=ArtCatalog&pact=edit&$url title='Редактировать'>Редактировать</a><br/>
                    <a href=# onClick=\"delete_position('?cmd=ArtCatalog&pact=del&$url',".
                "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                    <a href=?cmd=ArtCatalog&ppos=down&$url>Вниз</a><br/></td>
            </tr>";
        }
        echo "</table><br>";
        // Выводим ссылки на другие страницы
        echo $artCatalog;
    }

    if( isset( $idpar ) && $idpar != 0 ) {
        ?>
        <table cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td>
                    <?php
                    echo "<a class=menu
                href=?cmd=ArtUrl&pact=add&idpar=$idpar&".
                        "page=$_GET[page]
                title=\"Добавить ссылку на страницу текущего
                        или любого другого сайта\">
                Добавить ссылку
          </a>&nbsp;&nbsp;&nbsp;
          <a class=menu
                href=?cmd=ArtArt&pact=add&idpar=$idpar&".
                        "page=$_GET[page]
                title=\"Добавить статью в данный раздел\">
                Добавить статью</a>";
                    ?>
                </td>
            </tr>
        </table><br>
        <?php

        $artPosition = $request->getObject( 'artPosition' );
        if( is_object( $artPosition ) ) {
            // Получаем содержимое текущей страницы
            $position = $artPosition->get_page();
        }
//        echo "<tt><pre>".print_r($_GET[page], true)."</pre></tt>";
//        echo "<tt><pre>".print_r($position, true)."</pre></tt>";
        if( ! empty( $position ) ) {
            // Выводим ссылки на другие страницы
            echo $artPosition;
        // Выводим заголовок таблицы
        echo '<table width="100%"
                    class="table"
                     border="0"
                      cellpadding="0"
                       cellspacing="0">
                <tr  class=header align="center">
                    <td align="center">Название</td>
                    <td align="center">URL</td>
                    <td width="20" align="center">Поз.</td>
                    <td width="50">Действия</td>
                </tr>';
        for( $i=0; $i<count( $position ); $i++ ) {
            $url = "idp={$position[$i][id_position]}&".
                "idc={$position[$i][id_catalog]}&".
                "page={$_GET[page]}&".
                "idpar={$idpar}";
            // Выясняем скрыта позиция или нет
            if( $position[$i]['hide'] == 'hide' ) {
                $strhide = "<a href=?cmd=ArtUrl&ppos=show&$url>Отобразить</a>";
                $style = " class='hiddenrow'";
            } else {
                $strhide = "<a href=?cmd=ArtUrl&ppos=hide&$url>Скрыть</a>";
                $style = "";
            }
            // Выясняем является ли позиция статьей или ссылкой
            if( $position[$i]['url'] == 'article' ) {
                $edit = "?cmd=ArtArt&pact=edit";
                // $url нельзя использовать из-за параметра page
                $name = "<td><p class='small'>".
                    "<a href=?cmd=ArtParagraph&".
                    "idp={$position[$i][id_position]}&".
                    "idc={$position[$i][id_catalog]}>".
                    \dmn\view\utils\printPage( $position[$i]['name'] )."</a></p></td>";
            } else {
                $edit = "?cmd=ArtUrl&pact=edit";
                $name = "<td><p class='small'>".
                    \dmn\view\utils\printPage( $position[$i]['name'] ).
                    "</p></td>";
            }
            // Выводим позиции
            echo "<tr $style>
                    $name
                    <td>".\dmn\view\utils\printPage( $position[$i]['url'] )."</td>
                    <td align=center>".\dmn\view\utils\printPage( $position[$i]['pos'] )."</td>
                    <td>
                        <a href=?cmd=ArtUrl&ppos=up&$url>Вверх</a><br>
                        $strhide<br>
                        <a href=$edit&$url>Редактировать</a><br>
                        <a href=# onclick=\"delete_position('?cmd=ArtUrl&pact=delete&$url',".
                "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                        <a href=?cmd=ArtUrl&ppos=down&$url>Вниз</a>
                    </td>
                 </tr>";
        }
        echo "</table><br><br>";

    }
}

    // Выводим ссылки на другие страницы
    echo $artPosition;

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