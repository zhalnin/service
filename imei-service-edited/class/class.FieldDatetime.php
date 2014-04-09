<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16.12.12
 * Time: 19:25
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe DateTime"
 */
class FieldDatetime extends Field
{

  // Time in format UNIXSTAMP
  protected $_time;
  // Minimum allowable year
  protected $_begin_year;
  // Maximum allowable year
  protected $_end_year;

  public function __construct($name,
                              $caption,
                              $time,
                              $begin_year = 2000,
                              $end_year = 2020,
                              $parameters = "",
                              $help = "",
                              $help_url = "")
  {
    parent::__construct($name,
                        "datetime",
                        $caption,
                        false,
                        $value,
                        $parameters,
                        $help,
                        $help_url);

    if(empty($time)) $this->_time = time();
    else if(is_array($time))
    {
      $this->_time = mktime($time['hour'],
                            $time['minute'],
                            0,
                            $time['month'],
                            $time['day'],
                            $time['year']);
    }
    else $this->_time = $time;
    $this->_begin_year = $begin_year;
    $this->_end_year = $end_year;
  }

  /**
   * Date in format of MySQL
   * @return date
   */
  public function get_mysql_format()
  {
    return date("Y-m-d H:i:s", $this->_time);
  }

   /**
   * Function for name of field
   * and tag of control element
   * @return array
   */
  public function get_html()
  {
    // Check if not empty style
    if(!empty($this->css_style))
    {
      $style = "style=\"".$this->css_style."\"";
    }
    else $style = "";
    // Check if not empty class
    if(!empty($this->css_class))
    {
      $class = "class=\"".$this->css_class."\"";
    }
    else $class = "";

    // From tag
    $date_month   = @date("m", $this->_time);
    $date_day     = @date("d", $this->_time);
    $date_year    = @date("Y", $this->_time);
    $date_hour    = @date("H", $this->_time);
    $date_minute  = @date("i", $this->_time);

    // Drop-down list for day
    $tag = "<select title=\"Число\"
            $style $class type=\"text\"
            name='".$this->_name."[day]'>\n";
    for($i = 1; $i <= 31; $i++)
    {
      if($date_day == $i) $temp = "selected";
      else $temp = "";
      $tag .= "<option value=\"$i\" $temp>".sprintf("%02d", $i);
    }
    $tag .= "</select>";

    // Drop-down list for month
    $tag .= "<select title=\"Месяц\"
            $style $class type=\"text\"
            name='".$this->_name."[month]'>\n";
    for($i = 1; $i <= 12; $i++)
    {
      if($date_month == $i) $temp = "selected";
      else $temp = "";
      $tag .= "<option value=\"$i\" $temp>".sprintf("%02d", $i);
    }
    $tag .= "</select>";

    // Drop-down list for year
    $tag .= "<select title=\"Год\"
            $style $class type=\"text\"
            name='".$this->_name."[year]'>\n";
    for($i = 2004; $i <= 2017; $i++)
    {
      if($date_year == $i) $temp = "selected";
      else $temp = "";
      $tag .= "<option value=\"$i\" $temp>$i";
    }
    $tag .= "</select>";

    // Drop-down list for hour
    $tag .= "&nbsp;&nbsp;<select
            title=\"Часы\" $style $class
            type=\"text\" name='".$this->_name."[hour]'>";
    for($i = 0; $i <= 23; $i++)
    {
      if($date_hour == $i) $temp = "selected";
      else $temp = "";
      $tag .= "<option value=\"$i\" $temp>".sprintf("%02d", $i);
    }
    $tag .= "</select>";

    // Drop-down list for minutes
    $tag .= "<select title=\"Минуты\"
             $style $class
             type=\"text\"
             name='".$this->_name."[minute]'>";
    for($i = 0; $i <= 59; $i++)
    {
      if($date_minute == $i) $temp = "selected";
      else $temp = "";
      $tag .= "<option value=\"$i\" $temp>".sprintf("%02d", $i);
    }
    $tag .= "</select>";

    if($this->_is_required) $this->_caption .= " *";

     // Form prompt
    $help = "";
    if(!empty($this->_help))
    {
      $help .= "<span style='color:blue'>".
                  nl2br($this->_help)."</span>";
    }
    if(!empty($help)) $help .= "<br/>";
    if(!empty($this->_help_url))
    {
      $help .= "<span style='color: blue'><a href='".
                $this->_help_url.">помощь</a></span>";
    }

    // Return array for class Form
    return array($this->_caption, $tag, $help);
  }

    /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    if(date('Y', $this->_time) > $this->_end_year ||
       date('Y', $this->_time) < $this->_begin_year)
    {
      return "Поле \"".$this->_caption."\" содержит
              недопустимые значения (его значение
              должно лежать в диапазаоне ".
              $this->_begin_year."-".$this->_end_year.")";
    }
    return "";
  }
}
?>