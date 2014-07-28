<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 28/07/14
 * Time: 21:39
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
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;

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

    ?>

<!--    меню добавления ссылки или статьи-->
        <table cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td>
                    <?php
                    echo "<a class=menu
                        href=?cmd=ArtCatalog&pact=urladd&idp=$idp&".
                        "page=$_GET[page]
                        title=\"Добавить ссылку на страницу текущего
                                или любого другого сайта\">
                        Добавить ссылку
                  </a>&nbsp;&nbsp;&nbsp;
                  <a class=menu
                        href=?cmd=ArtCatalog&pact=artadd&idp=$idp&".
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
                        "<a href=?cmd=ArtPosition&".
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