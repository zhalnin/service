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
    require_once( "dmn/classes/class.PagerMysql.php" );
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
//    require_once( "dmn/domain/CatalogPosition.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request = \dmn\view\VH::getRequest();
    $idp = $request->getProperty('idp');

//    echo "<tt><pre>".print_r($request, true)."</pre></tt>";

    // Данные переменные определяют название страницы и подсказку
    $title      = 'Администрирование перечня услуг';
    $pageinfo   = '<p class=help>Здесь осуществляется администрирование
                            перечня услу по анлоку, добавление новых услуг
                            и позиций</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    $_GET['idp'] = intval($_GET['idp']);

    // Содержание страницы

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляеи объект постраничной навигации
    $obj = new \dmn\classes\PagerMysql('system_menu_catalog',
        "WHERE id_parent=$_GET[idp]",
        "ORDER BY pos",
        $pnumber,
        $page_link,
        "&cmd=ArtCatalog&idp=$_GET[idp]");

    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellpadding="0" cellspacing="0" border="0">
            <tr valign="top"><td height="25"><p>';
    echo "<a class=menu
                href=dmn.php?cmd=ArtCatalog&idp=0&page=$_GET[page]>
                    Корневой каталог</a>-&gt;".
        \dmn\view\utils\navigation($_GET['idp'], "", 'system_menu_catalog','ArtCatalog').
        "<a class=menu href=dmn.php?cmd=ArtCatalog&".
        "pact=add&".
        "idc=$_GET[idp]&".
        "idp=$_GET[idp]&".
        "page=$_GET[page]>Добавить подкаталог</a>";
    echo "</td></tr></table>";

    // Получаем содержимое текущей страницы
    $catalog = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $catalog ) ) {

//        echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";


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
            "idp={$catalog[$i][id_parent]}&".
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


        // Выводим катало
        echo "<tr $style>
                <td><a href=dmn.php?cmd=ArtCatalog&".
            "idp={$catalog[$i]['id_catalog']}&".
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
    }



    if( isset( $idpc ) && $idpc != 0 ) {
?>
    <table cellpadding="0" cellspacing="0">
    <tr valign="top">
        <td>
            <?php
            echo "<a class=menu
                href=?cmd=ArtCatalog&pact=urladd&id_parent=$idp&".
                "page=$_GET[page]
                title=\"Добавить ссылку на страницу текущего
                        или любого другого сайта\">
                Добавить ссылку
          </a>&nbsp;&nbsp;&nbsp;
          <a class=menu
                href=?cmd=ArtCatalog&pact=artadd&id_parent=$idp&".
                "page=$_GET[page]
                title=\"Добавить статью в данный раздел\">
                Добавить статью</a>";
            ?>
            </td>
            </tr>
        </table><br>
<?php
        $obj = new \dmn\classes\PagerMysql( 'system_menu_position',
        " WHERE id_catalog={$idp}",
        " ORDER BY pos",
        $pnumber,
        $page_link,
        "&cmd=ArtCatalog&idp={$idp}" );
        // Получаем содержимое текущей страницы
        $position = $obj->get_page();
//        echo "<tt><pre>".print_r($_GET[page], true)."</pre></tt>";
        if( ! empty( $position ) ) {
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
                    "idc={$idp}&".
                    "page={$_GET[page]}";
                // Выясняем скрыта позиция или нет
                if( $position[$i]['hide'] == 'hide' ) {
                    $strhide = "<a href=?cmd=ArtCatalog&ppos=show&$url>Отобразить</a>";
                    $style = " class='hiddenrow'";
                } else {
                    $strhide = "<a href=?cmd=ArtCatalog&ppos=hide&$url>Скрыть</a>";
                    $style = "";
                }
                // Выясняем является ли позиция статьей или ссылкой
                if( $position[$i]['url'] == 'article' ) {
                    $edit = "?cmd=ArtCatalogEdit";
                    // $url нельзя использовать из-за параметра page
                    $name = "<td><p class='small'>".
                            "<a href=?cmd=ArtParagraph&".
                            "idp={$position[$i][id_position]}&".
                            "idc={$idp}>".
                        \dmn\view\utils\printPage( $position[$i]['name'] )."</a></p></td>";
                } else {
                    $edit = "?cmd=ArtCatalog&pact=edit";
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
                        <a href=?cmd=ArtCatalog&pact=up&$url>Вверх</a><br>
                        $strhide<br>
                        <a href=?cmd=ArtCatalog&pact=edit&$url>Редактировать</a><br>
                        <a href=# onclick=\"delete_position('?cmd=ArtCatalog&pact=delete&$url',".
                    "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                        <a href=?cmd=ArtCatalog&pact=down&$url>Вниз</a>
                    </td>
                 </tr>";
            }
            echo "</table><br><br>";
        }
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