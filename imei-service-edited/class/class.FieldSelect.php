<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13.12.12
 * Time: 11:28
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "<input>"
 * <select> ... </select>
 * <options> ... </options>
 */
class FieldSelect extends Field
{

  // Array with value of <options> into <select>
  protected $_options;
  // Boolean to admit selection of several point in <select>
  protected $_multi;
  // Height of multiple select
  protected $_select_size;

  // Class constructor
  public function __construct($name,
                              $caption,
                              $options = array(),
                              $value,
                              $multi = false,
                              $select_size = 4,
                              $parameters = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "select",
                        $caption,
                        false,
                        $value,
                        $parameters);

    $this->_options     = $options;
    $this->_multi       = $multi;
    $this->_select_size = $select_size;
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
    // If multiple select and height of this select is set
    if($this->_multi && $this->_select_size)
    {
      $multi = "multiple size=".$this->_select_size;
      $this->_name = $this->_name."[]";
    }
    else $multi = "";

    // Form tag
    $tag = "<select $style $class name=\"".$this->_name."\" $multi>\n";
    if(!empty($this->_options))
    {
      foreach($this->_options as $key => $value)
      {
//echo "<tt><pre>". print_r($key ." - ".$value." - ".$this->_value, TRUE) . "</pre></tt>";
        if(is_array($this->_value))
        {
          if(in_array($key, $this->_value)) $selected = "selected";
          else $selected = "";
        }
        else if($key == trim($this->_value)) $selected = "selected";
        else $selected = "";
          $tag .= "<option value=\"".htmlspecialchars($key, ENT_QUOTES)."\" $selected>".
                  htmlspecialchars($value, ENT_QUOTES)."</option>\n";

      }
    }
    $tag .= "</select>\n";

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
    if(!in_array($this->_value, array_keys($this->_options)))
    {
      if(empty($this->_value))
      {
        return "Поле \"".$this->_caption."\"
                содержит недопустимое значение.";
      }
    }
//          echo "<tt><pre>". print_r($this->_value, TRUE) . "</pre></tt>";
    if(!get_magic_quotes_gpc())
    {
      for($i = 0; $i < count($this->_value); $i++)
      {
        $this->_value[$i] = mysql_real_escape_string($this->_value[$i]);
      }
    }

    return "";
  }

  /**
   * It is for selected element
   * @return
   */
  public function selected()
  {
    return $this->_value[0];
  }

  /**
   * For save into DB array with multiple data
   * @return serialize string
   *
   * For taking result back you should to unserialize result
   */
  public function get_select_value()
  {
    if(is_array($this->_value))
    {
      if(!get_magic_quotes_gpc())
      {
        for($i = 0; $i < count($this->_value); $i++)
        {
          $arr[] = mysql_real_escape_string($this->_options[$this->_value[$i]]);

        }
        return serialize($arr);
      }
    }
  }
}
?>