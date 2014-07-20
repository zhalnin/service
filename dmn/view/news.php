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

    require_once( "dmn/classes/class.PagerMysql.php" );

    require_once( "dmn/view/utils/printPage.php" );

    // Данные переменные определяют название страницы и подсказку
    $title      = 'Управление блоком "Блок новостей"';
    $pageinfo   = '<p class=help>Здесь можно добавить
                    новостной блок, отредактировать или удалить уже
                    существующий блок.</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Содержание страницы

        // Количество ссылок в постраничной навигации
        $page_link = 3;
        // Количество позиций на странице
        $pnumber = 10;
        // Объявляеи объект постраничной навигации
        $obj = new \dmn\classes\PagerMysql('system_news',
            "",
            "ORDER BY pos",
            $pnumber,
            $page_link,
            "&cmd=News");

//    echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
        // Добавить блок
        echo "<a href=?cmd=News&pact=add&page=$_GET[page]
                    title=Добавить новостной блок>
                    Добавить новостной блок</a><br><br>";

        // Получаем содержимое текущей страницы
        $news = $obj->get_page();
        // Если имеется хотя бы одна запись - выводим ее
        if( ! empty( $news ) ) {
            // Выводим ссылки на другие страницы
            echo $obj->print_page();
            echo "<br /><br />";
            ?>
<table width="100%"
       class="table"
       border="0"
       cellpadding="0"
       cellspacing="0">
    <tr class="header" align="center">
        <td width="200">Дата</td>
        <td width="60%">Новость</td>
        <td width="40">Избр-е</td>
        <td>Действия</td>
    </tr>
    <?php
    for($i = 0; $i < count($news); $i++) {
        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $url = "id_news={$news[$i][id_news]}&page=$_GET[page]";
        if($news[$i]['hide'] == 'show') {
            $showhide = "<a href=?cmd=News&ppos=hide&$url
                                    title='Скрыть новость в блоке новостей'>
                                    Скрыть</a>";
            $style = "";
        } else  {
            $showhide = "<a href=?cmd=News&ppos=show&$url
                                    title='Отобразить новость в блоке новостей'>
                                    Отобразить</a> ";
            $style = " class=hiddenrow";
        }
        // Проверяем наличие изображения
        if($news[$i]['urlpict'] != '' &&
            $news[$i]['urlpict'] != '-'&&
            is_file("../../".$news[$i]['urlpict'])) {
            $url_pict = "<b><a href=../../{$news[$i][urlpict]}>есть</a></b>";
        } else {
            $url_pict = "нет";
        }

        $news_url = "";
        if(!empty($news[$i]['url'])) {
            if(!preg_match("|^http://|i", $news[$i]['url'])) {
                $news[$i]['url'] = "http://{$news[$i][url]}";
            }
            $news_url = "<br><b>Ссылка: </b>
                                <a href='{$news[$i][url]}'>
                                {$news[$i][urltext]}</a>";
            if(empty($news[$i]['urltext']))  {
                $news_url = "<br><b>Ссылка: </b>
                                    <a href='{$news[$i][url]}'>
                                    {$news[$i][url]}</a>";
            }
        }

        // Преобразуем дату из формата MySQL YYYY-MM-DD hh:mm:ss
        // в формат DD.MM.YYYY hh:mm:ss
        list($date, $time) = explode(" ", $news[$i]['putdate']);
        list($year, $month, $day) = explode("-", $date);
        $news[$i]['putdate'] = "$day.$month.$year $time";

        // Выводим новость
        echo "<tr $style>
                        <td><p align='center'>{$news[$i][putdate]}</td>

                        <td align=center><a title='Редактировать текст новости'
                                href=?cmd=News&pact=edit&$url>{$news[$i][name]}</a><br>
                                ".nl2br(\dmn\view\utils\printPage($news[$i]['preview']))." $news_url </td>

                        <td align=center>$url_pict</td>

                        <td align=center>
                        <a href=?cmd=News&ppos=uppest&$url alt='поднять выше невидимых блоков'>Наверх</a><br/>
                            <a href=?cmd=News&ppos=up&$url>Вверх</a><br/>
                            $showhide<br/>
                            <a href=?cmd=News&pact=edit&$url title='Редактировать текст новости'>Редактировать</a><br/>
                            <a href=# onClick=\"delete_position('?cmd=News&pact=del&$url',".
            "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                            <a href=?cmd=News&ppos=down&$url>Вниз</a><br/></td>


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