<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 03.05.12
 * Time: 18:10
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
// Устанавливаем соединение с базой данных
//require_once("../../config/config.php");
// Подключаем блок авторизации
require_once("../utils/security_mod.php");
// Подключаем классы формы
require_once("../../config/class.config.dmn.php");

// Если поле separator пусто - используем
// по умолчанию в качестве разделителя точку с запятой
if(empty($_REQUEST['separator'])) $_REQUEST['separator'] = ";";
$csvfile = new FieldFile("csvfile",
                        "CSV-файл",
                        true,
                        "../../files/csvfile/");
$separator = new FieldText("separator",
                            "Разделитель",
                            true,
                            $_REQUEST['separator']);
$id_catalog = new FieldHiddenInt("id_catalog",
                                    true,
                                    $_REQUEST['id_catalog']);
try
{
    // Форма
    $form = new Form(array("csvfile"    => $csvfile,
                           "separator"  => $separator,
                           "id_catalog" => $id_catalog),
                "Импортировать",
                "field");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
        // Проверяем корректность заполнения HTML-формы
        // и обрабатываем текстовые поля
        $error = $form->check();
        if(empty($error))
        {
            // Читаем содержимое загруженного файла
            $filename = "../../files/csvfile/".
                        $form->fields['csvfile']->get_filename();
            $content = file_get_contents($filename);
            // Удаляем файл
            unlink($filename);
            // Разделитель
            $separator = $form->fields['separator']->value;
            // Если имеются пустые позиции, забиваем их прочерком "-"
            // В начале файла
            $content = str_replace("\n".$separator,
                                   "\n-".$separator,
                                    $content);
            // В середине файла
            $content = str_replace($separator.$separator,
                                   $separator."-".$separator,
                                   $content);
            // В конце файла
            $content = str_replace($separator."\n",
                                   $separator."-\n",
                                   $content);
            // Разбиваем файл по строкам, каждую из которых заносим
            // в отдельный элемент временного массива $strtmp
            $strtmp = explode("\n",$content);

            // Разбиваем строку по отдельным словам, используя
            // разделитель $separator
            $i = 0;
            foreach($strtmp as $value)
            {
                // Если строка пуста - выходим из цикла. Пустые строки могут
                // появиться, если в конце CSV-файла находятся пустые строки
                if(empty($value)) continue;
                // Разбиваем строку по разделителью
                list($district,     // Район
                     $address,      // Адрес
                     $floor,        // Этаж
                     $floorhouse,   // Этажность дома
                     $material,     // Материал дома
                     $rooms,        // К-во комнат
                     $square_o,     // Площадь общая
                     $square_j,     // Площадь жилая
                     $square_k,     // Площадь комнат
                     $su,           // Санузел
                     $balcony,      // Тип балкона
                     $note,         // Замечания
                     $pricemeter,   // Цена за метр
                     $price,        // Цена
                     $currency      // Валюта
                     ) = explode($separator,$value);
                // Игнорируем строку с заголовками
                if($district == "Район") continue;
                // Увеличиваем значение счетчика
                $i++;
                // Определяем район по первым трем буквам его названия
                switch(substr(strtolower($district),0,3))
                {
                    case 'выб':
                        $district = "viborgskii";
                        break;
                    case 'кал':
                        $district = "kalininskii";
                        break;
                    case 'прим':
                        $district = "primorskii";
                        break;
                    case 'цен':
                        $district = "centralnii";
                        break;
                    case 'адм':
                        $district = "admiralteiskii";
                        break;
                    case 'кра':
                        $district = "krasnoselskii";
                        break;
                    case 'мос':
                        $district = "moskovskii";
                        break;
                    case 'вас':
                        $district = "vasileostrovskii";
                        break;
                    case 'пет':
                        $district = "petrogradskii";
                        break;
                    case 'нев':
                        $district = "nevskii";
                        break;
                }
                // Материал дома
                switch(substr($material,0,3))
                {
                    case 'кир':
                        $material = "brick";
                        break;
                    case 'пан':
                        $material = "concrete";
                        break;
                    case 'мон':
                        $material = "reconcrete";
                        break;
                }
                // Санузел
                switch(substr($su,0,1))
                {
                    case 'с':
                        $su = 'separate';
                        break;
                    case 'р':
                        $su = 'combined';
                        break;
                }
                // Лоджия/Балкон
                switch(substr(strtolower($balcony),0,1))
                {
                    case 'л':
                        $balcony = "loggia";
                        break;
                    case 'б':
                        $balcony = "balcony";
                        break;
                }
                // Валюта
                $currency = trim($currency);
                // Преобразуем ковычки
                $note = mysql_real_escape_string($note);
                $district = mysql_real_escape_string($district);
                $address = mysql_real_escape_string($address);
                $currency = mysql_real_escape_string($currency);
                // Формируем и выполняем SQL-запрос на добавление позиции
                $insert_query[] = "(NULL,
                                    '$note',
                                    '$district',
                                    '$address',
                                    $square_o,
                                    $square_j,
                                    $square_k,
                                    $rooms,
                                    $floor,
                                    $floorhouse,
                                    '$material',
                                    '$su',
                                    '$balcony',
                                    $price,
                                    $pricemeter,
                                    '$currency',
                                    '$show',
                                    $i,
                                    NOW(),
                                    {$form->fields[id_catalog]->value})";
            }
            if(is_array($insert_query))
            {
                // Удаляем записи из таблицы $tbl_cat_position,
                // принадлежащие данному подкаталогу
                $query = "DELETE FROM $tbl_cat_position
                        WHERE id_catalog={$form->fields[id_catalog]->value}";
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка при удалении
                                            старых позиций");
                }
                // Начало формирования SQL-запроса на вставку данных
                // из CSV-файла
                $query = "INSERT INTO $tbl_cat_position
                            VALUES ".implode(",", $insert_query);
                // Выполняем многострочный оператор INSERT
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка при вставке
                                            новых позиций");
                }
            }
            // Осуществляем автоматический переход на страницу
            // администрирования текущего каталога
            header("Location: position.php".
                    "?id_catalog={$form->fields[id_catalog]->value}");
            exit();
        }
    }
    // Начало страницы
    $title = "Импорт позиций из CSV-файла";
    $pageinfo = "<p class=help>Позиции можно импортировать
                    из Excel-формата, предварительно сохранив
                    импортируемый лист как CSV-файл.</p>";
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    echo "<p><a href=# onclick='history.back()'>Назад</a></p>";
    // Выводим сообщение об ошибках, если они имеются
    if(!empty($error))
    {
        foreach($error as $err)
        {
            echo "<span style=\"color: red\">$err</span><br>";
        }
    }
    // Выводим HTML-форму
    $form->print_form();
}
catch(ExceptionMySQL $exc)
{
    require("../utils/exception_mysql.php");
}
catch(ExceptionObject $exc)
{
    require("../utils/exception_object.php");
}
catch(ExceptionMember $exc)
{
    require("../utils/exception_member.php");
}

// Включаем завершение страницы
require_once("../utils/bottom.php");
ob_get_flush();
?>