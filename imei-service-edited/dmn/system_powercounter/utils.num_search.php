<?php
  ////////////////////////////////////////////////////////////
  // Система учёта посещаемости сайта - PowerCounter
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Левин А.В (loki_angel@mail.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_Reporting(E_ALL & ~E_NOTICE);

  // Функция суточной архивации
  function archive_num_searchquery($tbl_searchquerys, $tbl_arch_num_searchquery)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_searchquerys, $tbl_arch_num_searchquery);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
      for ($i = $days - 1; $i >= 0; $i--)
      {

            $begin = "SELECT COUNT(*)
                    FROM $tbl_searchquerys
                    WHERE putdate LIKE '".date("Y-m-d",$last_day - $i*24*3600)."%' AND
                          searches = ";
          // Подсчитываем количество обращений за сутки
          $number_yandex      = query_result("$begin 'yandex'");
          $number_google      = query_result("$begin 'google'");
          $number_rambler     = query_result("$begin 'rambler'");
          $number_aport       = query_result("$begin 'aport'");
          $number_msn         = query_result("$begin 'msn'");
          $number_mail        = query_result("$begin 'mail'");

          $sql_num_queries[] = "(NULL,
                                '".date("Y-m-d", $last_day - $i*24*3600)."',
                                $number_yandex,
                                $number_google,
                                $number_rambler,
                                $number_aport,
                                $number_msn,
                                $number_mail)";
      }

      if(!empty($sql_num_queries))
      {
        $query = "INSERT INTO $tbl_arch_num_searchquery VALUES ".implode(",",$sql_num_queries);
        if(!mysql_query($query))
        {
            throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка суточной архивации - archive_num_searchquery()");
        }
      }

    }
  }

  // Функция архивации рефереров в недельные таблицы
  function archive_num_searchquery_week($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_week)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
    $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {
      // $begin_day - дата последней архивации... - смотрим далеко ли до
      // конца недели (воскресенье). Интервал включает данные с Понедельника (1)
      // до воскресенья (0).
      $weekday = date('w',$begin_day);

      // Текущему времени приравниваем начальную точку
      $current_date = $begin_day;
      while(floor(($last_day - $current_date)/24/60/60/7))
      {
        $end = "FROM $tbl_arch_num_searchquery
                    WHERE putdate > '".date("Y-m-d", $current_date)."' AND
                        putdate <= '" .date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";

         // Подсчитываем количество обращений за неделю
          $number_yandex    = query_result("SELECT SUM(number_yandex) $end");
          $number_google    = query_result("SELECT SUM(number_google) $end");
          $number_rambler   = query_result("SELECT SUM(number_rambler) $end");
          $number_aport     = query_result("SELECT SUM(number_aport) $end");
          $number_msn       = query_result("SELECT SUM(number_msn) $end");
          $number_mail      = query_result("SELECT SUM(number_mail) $end");

        // Формируем запрос для архивной таблицы
        $sql_num_queries[] = "(NULL,
                             '".date("Y-m-d", $current_date)."',
                             '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                             $number_yandex,
                             $number_google,
                             $number_rambler,
                             $number_aport,
                             $number_msn,
                             $number_mail)";
        // Увеличиваем текущее время до следующей недели
        $current_date += (7 - $weekday)*24*3600;
        $weekday = 0; // Далее идут циклы по целой недели
      }
      if(!empty($sql_num_queries))
      {
        $query = "INSERT INTO $tbl_arch_num_searchquery_week VALUES ".implode(",", $sql_num_queries);
        if(!mysql_query($query))
        {
         throw new ExceptionMySQL(mysql_error(),
                                  $query,
                                  "Ошибка недельной архивации - archive_num_searchquery_week");
        }
      }
    }
  }

  // Функция месячной архивации
  function archive_num_searchquery_month($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_month)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y",$last_day) - date("Y",$begin_day)))*12 +
             floor(date("m",$last_day) - date("m",$begin_day));
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
      // Архивируем данные по всем месяцам, по которым архивация
      // не проводилась
      for($i = date("Y",$begin_day)*12 + date("m",$begin_day); $i < date("Y",$last_day)*12 + date("m",$last_day); $i++)
      {
        $year = (int)($i/12);
        $month = ($i%12);
        if($month == 0)
        {
          $year--;
          $month = 12;
        }

        $end = "FROM $tbl_arch_num_searchquery
                WHERE YEAR(putdate) = $year AND
                        MONTH(putdate) = '".sprintf("%02d",$month)."'";


          // Подсчитываем количество обращений за неделю
          $number_yandex    = query_result("SELECT SUM(number_yandex) $end");
          $number_google    = query_result("SELECT SUM(number_google) $end");
          $number_rambler   = query_result("SELECT SUM(number_rambler) $end");
          $number_aport     = query_result("SELECT SUM(number_aport) $end");
          $number_msn       = query_result("SELECT SUM(number_msn) $end");
          $number_mail      = query_result("SELECT SUM(number_mail) $end");

        // Формируем запрос для архивной таблицы
        $sql_num_queries[] = "(NULL,
                             '$year-".sprintf("%02d",$month)."-01',
                             $number_yandex,
                             $number_google,
                             $number_rambler,
                             $number_aport,
                             $number_msn,
                             $number_mail)";

      }
      // Формируем окончательные запросы и выполняем их
      if(!empty($sql_num_queries))
      {
        $query = "INSERT INTO $tbl_arch_num_searchquery_month VALUES".implode(",", $sql_num_queries);
        if(!mysql_query($query))
        {
           throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                   "Ошибка месячной архивации - archive_num_searchquery_month()");
        }
      }
    }
  }
?>