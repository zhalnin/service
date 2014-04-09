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
  function archive_enterpoints($tbl_ip, $tbl_pages, $tbl_arch_enterpoint)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_enterpoint);
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
        $enterpoint_number = ENTERPOINT_NUMBER;
      for ($i = $days - 1; $i >= 0; $i--)
      {
        $query = "SELECT ip, min(putdate) AS putdate
                    FROM $tbl_ip
                    WHERE putdate LIKE '".date("Y-m-d", $last_day - $i*24*3600)."%' AND
                          browsers != 'none' AND
                          systems != 'none'
                    GROUP BY ip";
          $tbp = mysql_query($query);
          if(!$tbp)
          {
              throw new ExceptionMySQL(mysql_error(),
                                        $query,
                                        "Ошибка суточной архивации - archive_enterpoints()");
          }
          if(mysql_num_rows($tbp))
          {
              while($enterpoint = mysql_fetch_array($tbp))
              {
                  $query = "SELECT $tbl_pages.name AS name
                            FROM $tbl_ip, $tbl_pages
                            WHERE $tbl_ip.ip = '".$enterpoint['ip']."' AND
                                  $tbl_ip.putdate = '".$enterpoint['putdate']."' AND
                                  $tbl_ip.id_page = $tbl_pages.id_page
                            GROUP BY name";
                  $page[] = query_result($query);
              }
              if(!empty($page)) continue;
              $count_array = array_count_values($page);
              arsort($count_array);
              $j = 0;
              foreach($count_array as $pag => $tot)
              {
                  if($j >= $enterpoint_number) break;
                  $sql[] = "(NULL,
                            '".date("Y-m-d", $last_day - $i*24*3600)."',
                            '$pag',
                            $tot)";
                  $j++;
              }
              unset($count_array, $page);
          }
      }
        if(!empty($sql))
        {
            $query = "INSERT INTO $tbl_arch_enterpoint VALUES ".implode(",",$sql);
            if(!mysql_query($query))
            {
                throw new ExceptionMySQL(mysql_error(),
                                            $query,
                                            "Ошибка суточной архивации - archive_enterpoints()");
            }
        }
    }
  }
  
  // Функция недельной архивации
  function archive_enterpoints_week($tbl_arch_enterpoint, $tbl_arch_enterpoint_week)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_enterpoint, $tbl_arch_enterpoint_week, 'putdate_begin');
    // Вычисляем сколько недель прошло с даты последней архивации
    $week = floor(($last_day - $begin_day)/24/60/60/7);
    // Если прошло больше недели - архивируем данные
    if($week > 0)
    {
        $enterpoint_number = ENTERPOINT_NUMBER;
      // $last_date - дата последней архивации... - смотрим далеко ли до
      // конца недели (воскресенье). Интервал включает данные с Понедельника (1)
      // до воскресенья (0).
      $weekday = date('w',$begin_day);
  
      // Текущему времени приравниваем начальную точку
      $current_date = $begin_day;
      while(floor(($last_day - $current_date)/24/60/60/7))
      {
        $where = "WHERE putdate >= '".date("Y-m-d", $current_date)."' AND
                        putdate < '" .date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."'";
  
        $query = "SELECT page AS name, SUM(total) AS total
                    FROM $tbl_arch_enterpoint
                    $where
                    GROUP BY name
                    ORDER BY total DESC
                    LIMIT $enterpoint_number";
        $ent = mysql_query($query);
        if(!$ent)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка недельной архивации - archive_enterpoints_week()");
        }
        if(mysql_num_rows($ent))
        {
          while($enterpoints = mysql_fetch_array($ent))
          {
            $sql_enterpoints[] = "(NULL,
                             '".date("Y-m-d", $current_date)."',
                             '".date("Y-m-d", $current_date + 24*3600*(7 - $weekday))."',
                              '$enterpoints[name]',
                              $enterpoints[total])";
          }
        }
        // Увеличиваем текущее время до следующей недели
        $current_date += (7 - $weekday)*24*3600;
        $weekday = 0; // Далее идут циклы по целой недели
      }
      if(!empty($sql_enterpoints))
      {
        $query = "INSERT INTO $tbl_arch_enterpoint_week VALUES".implode(",", $sql_enterpoints);
        if(!mysql_query($query))
        {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                  "Ошибка недельной архивации - archive_enterpoints_week");
        }
      }
    }
  }
  
  // Функция месячной архивации
  function archive_enterpoints_month($tbl_arch_enterpoint, $tbl_arch_enterpoint_month)
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y")) + 2;
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_arch_enterpoint, $tbl_arch_enterpoint_month);
    // Вычисляем сколько недель прошло с даты последней архивации
    $month = (floor(date("Y",$last_day) - date("Y",$begin_day)))*12 + 
             floor(date("m",$last_day) - date("m",$begin_day)); 
    // Если прошло больше месяца - архивируем данные
    if($month > 0)
    {
        $enterpoint_number = ENTERPOINT_NUMBER;
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
  
         $query = "SELECT page AS name, SUM(total) AS total
                  FROM $tbl_arch_enterpoint
                  $where
                  GROUP BY name
                  ORDER BY total DESC
                  LIMIT $enterpoint_number";
        $ent = mysql_query($query);
        if(!$ent)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка месячной архивации - archive_enterpoints_month()");
        }
  
        if(mysql_num_rows($ent))
        {
            while($enterpoints = mysql_fetch_array($ent))
            {
                $sql_enterpoints[] = "(NULL,
                          '$year-".sprintf("%02d",$month)."-01 23:59:59',
                          '$enterpoints[name]',
                          $enterpoints[total])";
            }
        }
      }
      // Формируем окончательные запросы и выполняем их
      if(!empty($sql_enterpoints))
      {
        $query = "INSERT INTO $tbl_arch_enterpoint_month VALUES".implode(",", $sql_enterpoints);
        if(!mysql_query($query))
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка месячной архивации - archive_enterpoints_month()");
        }
      }
    }
  }
?>