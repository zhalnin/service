<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13.12.12
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "<input>"
 * <select> ... </select>
 * <options> ... </options>
 */
class FieldRadio extends Field
{
  // Type of answers
  protected $_radio;

  // Class constructor
  public function __construct($name,
                              $caption,
                              $radio = array(),
                              $value,
                              $parameters = "",
                              $help = "",
                              $help_url = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "radio",
                        $caption,
                        false,
                        $value,
                        $parameters,
                        $help,
                        $help_url);

      if($this->_radio != "radio_rate") $this->_radio = $radio;
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

    $this->_type = "radio";

     // Form tag
    $tag = "";
    if(!empty($this->_radio))
    {
      foreach($this->_radio as $key => $value)
      {
//        echo "<tt><pre>". print_r($key ." - ".$value." - ".$this->_value, TRUE) . "</pre></tt>";
        if($key == trim($this->_value)) $checked = "checked";
        else $checked = "";
        if(strpos($this->_parameters, "horizontal") !== false)
        {
          $tag .= "<input $style $class
                    type=".$this->_type."
                    name=".$this->_name."
                    $checked value='$key'>$value";
        }
        else
        {
          $tag[] = "<input $style $class
                      type=".$this->_type."
                      name=".$this->_name."
                      $checked value='$key'>$value\n";
        }
      }
    }
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
    if(!get_magic_quotes_gpc())
    {
      $this->_value = mysql_real_escape_string($this->_value);
    }
    if(!@in_array($this->_value, array_keys($this->_radio)))
    {
      if(empty($this->_value))
      {
        return "Поле \"".$this->_caption."\" содержит
                неодпустимое значение!";
      }
    }
    return "";
  }
}
?>