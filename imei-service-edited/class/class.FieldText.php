<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.12.12
 * Time: 16:46
 * To change this template use File | Settings | File Templates.
 */

// Error handling
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Describe control element "text"
 * <input type="text">
 */
class FieldText extends Field
{
  // Size of text field
  public $size;
  // Max length of field
  public $maxlength;

  // Construct of class
  public function __construct($name,
                              $caption,
                              $is_required = false,
                              $value = "",
                              $maxlength = 255,
                              $size = 41,
                              $parameters = "",
                              $help = "",
                              $help_url = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "text",
                        $caption,
                        $is_required,
                        $value,
                        $parameters,
                        $help,
                        $help_url);
    // Initiate class member
    $this->size       = $size;
    $this->maxlength  = $maxlength;
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
    // Check if not empty size
    if(!empty($this->size)) $size = "size=".$this->size;
    else $size = "";
    // Check if not empty maxlength
    if(!empty($this->maxlength))
    {
      $maxlength = "maxlength=".$this->maxlength;
    }
    else $maxlength = "";
    // Form tag
    $tag = "<input $style $class
              type=\"".$this->_type."\"
              name=\"".$this->_name."\"
              value=\"".htmlspecialchars($this->_value, ENT_QUOTES)."\"
              $size $maxlength>\n";
    // Check if field is required
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
    // Make safe text before insert text into DataBase
    if(!get_magic_quotes_gpc())
    {
      $this->_value = mysql_real_escape_string($this->_value);
    }
    // If field is required
    if($this->_is_required)
    {
      // Check if it empty
      if(empty($this->_value))
      {
        return "Поле \"".$this->_caption."\" не заполнено!";
      }
    }
    return "";
  }
}
?>