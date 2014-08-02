<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 23:12
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);
try {
    require_once( "dmn/view/utils/navigation.php" );
    require_once( "dmn/view/utils/printPage.php" );
    require_once( "dmn/view/ViewHelper.php" );

    $request    = \dmn\view\VH::getRequest();
    $idc        = $request->getProperty( 'idc' );
    $idp        = $request->getProperty( 'idp' );
    $paragraphs = $request->getObject( 'paragraph' );
    $position   = $request->getObject( 'position' );
    $catalog    = $request->getObject( 'catalog' );
//    echo "<tt><pre>".print_r($request, true)."</pre></tt>";
    if( is_object( $paragraphs ) ) {
        // Получаем содержимое текущей страницы
        $paragraph = $paragraphs->getPage();
    }

    // Данные переменные определяют название страницы и подсказку
    $title = $titlepage = 'Позиция ('.$catalog->getName().
        ' - '.$position->getName().')';
    $pageinfo = '<p class=help>Здесь осуществляется
                администрирование позиции ('.$catalog->getName().
        ' - '.$position->getName().').
                Параграф может представлять собой как обычный
                текстовый абзац, так и заголовок. Возможно
                использование шести уроней заголовков
                (H1, H2, H3, H4, H5, H6), H1 - самый крупный
                заголовок, применяемый обычно для названия
                страниц; далее заголовки уменьшаются в
                размере, то есть H6 - это самый мелкий заголовок.</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");


//    echo "<tt><pre>".print_r($catalog, true)."</pre></tt>";
//    echo "<tt><pre>".print_r($position, true)."</pre></tt>";
//    echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
//    echo "<tt><pre>".print_r($request, true)."</pre></tt>";

    if( $idc != 0 ) { // если  id каталога не ноль, то выводим строку навигации
        echo '<table cellpadding="0" cellspacing="0" border="0">

                  <tr valign="top">
                    <td height="25"><p>';
        echo "<a class=menu href=?cmd=ArtCatalog&idpar=0>
                Корневой каталог</a>-&gt;".
            \dmn\view\utils\navigation($idc,
                "",
                'system_menu_catalog','ArtCatalog' ) .$position->getName();
        echo "</td>
                </tr>
              </table>";
    }

    // Добавить параграф
    echo "<form>";
    echo "<input class='button'
                type='submit'
                value='Добавить параграф'><br/><br/>";
    echo "<input type='hidden' name='page' value='$_GET[page]'><br/><br/>";
    // Выводим заголовок таблицы разделов
    echo "<input type='hidden'
                 name='idc'
                 value=$_GET[idc]>";
    echo "<input type='hidden'
                 name='idp'
                 value=$_GET[idp]>";
    echo "<input type='hidden'
                 name='cmd'
                 value=ArtParagraph>";
    echo "<input type='hidden'
                 name='pact'
                 value=add>";
    echo "<table width='100%'
                 class='table'
                 border='0'
                 cellpadding='0'
                 cellspacing='0'>
             <tr class='header' align='center'>
                <td width='20' align='center'>
                    <input type='radio' name='pos' value='-1' checked>
                </td>
                <td align='center'>Содержимое</td>
                <td width='100' align='center'>Изображение<br/> и файлы</td>
                <td width='100' align='center'>Тип</td>
                <td width='20' align='center'>Поз.</td>
                <td width='50'>Действия</td>
             </tr>";

    if( ! empty( $paragraph ) ) {
//        echo "<tt><pre>".print_r($paragraph, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        for( $i = 0; $i < count( $paragraph ); $i++ ) {
            $url = "idph={$paragraph[$i][id_paragraph]}".
                    "&idp=$idp&".
                    "idc=$idc".
                    "&page=$_GET[page]";
            // Выясняем тип параграфа
            $type = "Параграф";
            switch( $paragraph[$i]['type'] ) {
                case 'text':
                    $type = 'Параграф';
                    break;
                case 'title_h1':
                    $type = "Заголовок H1";
                    break;
                case 'title_h2':
                    $type = "Заголовок H2";
                    break;
                case 'title_h3':
                    $type = "Заголовок H3";
                    break;
                case 'title_h4':
                    $type = "Заголовок H4";
                    break;
                case 'title_h5':
                    $type = "Заголовок H5";
                    break;
                case 'title_h6':
                    $type = "Заголовок H6";
                    break;
                case 'list':
                    $type = "Список";
                    break;
            }
            // Выясняем тип выравнивания параграфа
            $align = "";
            switch( $paragraph[$i]['align'] ) {
                case 'left':
                    $align = "align=left";
                    break;
                case 'center':
                    $align = "align=center";
                    break;
                case 'right':
                    $align = "align=right";
                    break;
            }
            // Выясняем скрыт раздел или нет
            if( $paragraph[$i]['hide'] == 'hide' )  {
                $strhide = "<a href=?cmd=ArtParagraph&ppos=show&$url>Отобразить</a>";
                $style="class=hiddenrow";
            } else {
                $strhide = "<a href=?cmd=ArtParagraph&ppos=hide&$url>Скрыть</a>";
                $style = "";
            }


            // Вычисляем, сколько изображений у данного элемента
            $countImg = \dmn\domain\ArtParagraphImg::findCountPos( $paragraph[$i]['id_paragraph'], $idp, $idc );
            $paragraphImg = \dmn\domain\ArtParagraphImg::find( $paragraph[$i]['id_paragraph'], $idc, $idp );
            if( is_object( $paragraphImg ) ) {
                $img = $paragraphImg->getBig();
                if( ! empty( $img ) ) {
                    if( file_exists(  "imei_service/view/$img" ) ) {
                        $size = @getimagesize( "imei_service/view/$img" );
                    }
                }
            }

            $total_image = $countImg['count'];
            if( ! empty( $countImg ) ) $print_image = " ($total_image)";
            else $print_image = "";

//        echo "<tt><pre>".print_r($size, true)."</pre></tt>";

            echo "<tr $style $class>
                    <td align=center>
                        <input type=radio name=pos value=".$paragraph[$i]['pos']." />
                    </td>
                    <td><p $align>".
                nl2br( \dmn\view\utils\printPage( $paragraph[$i]['name'] ) ).
                "</p></td>";
            if( $total_image > 0 ) {
                echo "<td align=center>
                        <a href=# onclick=\"show_detail( '?cmd=ArtParagraph&pact=detail&{$url}', {$size[0]}, {$size[1]} ); return false\">Изображения$print_image</a>
                    </td>";
            } else {
                echo "<td align=center>
                        <p>Изображений нет</p>
                    </td>";
            }
            echo "<td align=center>".\dmn\view\utils\printPage( $type )."</td>
                    <td align=center>".$paragraph[$i]['pos']."</td>
                    <td>
                        <a href=?cmd=ArtParagraph&ppos=up&$url>Вверх</a><br>
                    $strhide<br>
                    <a href=?cmd=ArtParagraph&pact=edit&$url>Редактировать</a><br>
                    <a href=# onClick=\"delete_position('?cmd=ArtParagraph&pact=del&$url',".
                "'Вы действительно хотите удалить параграф');\">Удалить</a><br>
                    <a href=?cmd=ArtParagraph&ppos=down&$url>Вниз</a></td>
                </tr>";
        }

    }
    echo "</table><br><br>";
    echo "</form>";


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