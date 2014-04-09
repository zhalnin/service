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
  function archive_refferer($tbl_refferer, $tbl_arch_refferer)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_refferer, $tbl_arch_refferer);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        $referer_number = REFFERER_NUMBER;
      for ($i = $days - 1; $i >= 0; $i--)
      {
        $query = "SELECT name, COUNT(name) AS total
                    FROM $tbl_refferer
                    WHERE putdate LIKE '".date("Y-m-d",$last_day - $i*24*3600)."%'
                    GROUP BY name
                    ORDER BY total DESC
                    LIMIT $referer_number";
          $ref = mysql_query($query);
          if(!$ref)
          {
              throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка суточной архивации - archive_refferer()");
          }
          if(mysql_num_rows($ref))
          {
              while($referrer = mysql_fetch_array($ref))
              {
                  $referrer['name'] = mysql_escape_string($referrer['name']);
                  $sql_referrer[] = "(NULL,
                                    '".date("Y-m-d",$last_day - $i*24*3600)."',
                                    '$referrer[name]',
                                    $referrer[total])";
              }
              if(!empty($sql_referrer))
              {
                    $query = "INSERT INTO $tbl_arch_refferer VALUES ".implode(",",$sql_referrer);
                if(!mysql_query($query))
                {
                    throw new ExceptionMySQL(mysql_error(),
                                                $query,
                                                "Ошибка суточной архивации - archive_refferer()");
                }
              }
          }
        }
    }
  }

  // Функция архивации рефереров в недельные таблицы
  function archive_refferer_week($tbl_arch_refferer, $tbl_arch_refferer_week)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_refferer, $tbl_arch_refferer_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
    $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {

        $referer_number = REFFERER_NUMBER;
      // $begin_day - дата последней архивации... - смотрим далеко ли до
      // конца недели (воскресенье). Интервал включает данные с Понедельника (1)
      // до воскресенья (0).
      $weekday = date('w',$begin_day);

      // Текущему времени приравниваем начальную точку
      $current_date = $begin_day;
      while(floor(($last_day - $current_date)/24/60/60/7))
      {
        $where = "WHERE putdate > '".date("Y-m-d", $current_date)."' AND
                        putdate <= '" .date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";

        // Извлекаем $referer_number самых активных рефереров за неделю
        $query = "SELECT name, SUM(total) AS total
                    FROM $tbl_arch_refferer
                    $where
                    GROUP BY name
                    ORDER BY total DESC
                    LIMIT $referer_number";
        $ref = mysql_query($query);
        if(!$ref)
        {
           throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                   "Ошибка недельной архивации - archive_refferer_week()");
        }
        if(mysql_num_rows($ref))
        {
          while($referrer = mysql_fetch_array($ref))
          {
              $referrer['name'] = mysql_escape_string($referrer['name']);
              $sql_referrer[] = "(NULL,
                             '".date("Y-m-d", $current_date)."',
                             '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                              '$referrer[name]',
                              $referrer[total])";
          }
        }
        // Увеличиваем текущее время до следующей недели
        $current_date += (7 - $weekday)*24*3600;
        $weekday = 0; // Далее идут циклы по целой недели
      }
      if(!empty($sql_referrer))
      {
        $query = "INSERT INTO $tbl_arch_refferer_week VALUES".implode(",", $sql_referrer);
        if(!mysql_query($query))
        {
         throw new ExceptionMySQL(mysql_error(),
                                  $query,
                                  "Ошибка недельной архивации - archive_refferer_week");
        }
      }
    }
  }

  // Функция месячной архивации
  function archive_refferer_month($tbl_arch_refferer, $tbl_arch_refferer_month)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_refferer, $tbl_arch_refferer_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y",$last_day) - date("Y",$begin_day)))*12 +
             floor(date("m",$last_day) - date("m",$begin_day));
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
        $referer_number = REFFERER_NUMBER;
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

        $where = "WHERE YEAR(putdate) = $year AND
                        MONTH(putdate) = '".sprintf("%02d",$month)."'";

         // Извлекаем $referer_number самых активных IP-адресов рефереров за месяц
         $query = "SELECT name, SUM(total) AS total
                  FROM $tbl_arch_refferer
                  $where
                  GROUP BY name
                  ORDER BY total DESC
                  LIMIT $referer_number";
        $ref = mysql_query($query);
        if(!$ref)
        {
           throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                   "Ошибка месячной архивации - archive_refferer_month()");
        }

        if(mysql_num_rows($ref))
        {
            while($referrer = mysql_fetch_array($ref))
            {
                $referrer['name'] = mysql_escape_string($referrer['name']);
                $sql_referrer[] = "(NULL,
                          '$year-".sprintf("%02d",$month)."-01',
                          '$referrer[name]',
                          $referrer[total])";
            }
        }
      }
      // Формируем окончательные запросы и выполняем их
      if(!empty($sql_referrer))
      {
        $query = "INSERT INTO $tbl_arch_refferer_month VALUES".implode(",", $sql_referrer);
        if(!mysql_query($query))
        {
           throw new ExceptionMySQL(mysql_error(),
                                    $query,
                                   "Ошибка месячной архивации - archive_refferer_month()");
        }
      }
    }
  }
?>