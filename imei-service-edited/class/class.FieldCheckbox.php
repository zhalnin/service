<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 11.12.12
 * Time: 0:48
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "<input>"
 * type="checkbox"
 */
class FieldCheckbox extends Field
{
  // Class constructor
  public function __construct($name,
                              $caption,
                              $value = "",
                              $parameters = "",
                              $help = "",
                              $help_url = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "checkbox",
                        $caption,
                        false,
                        $value,
                        $parameters,
                        $help,
                        $help_url);
    if($value == "on") $this->_value = true;
    else if($value === true) $this->_value = true;
    else $this->_value = false;
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

    if($this->_value) $checked = "checked";
    else $checked = "";

     // Form tag
    $tag = "<input $style $class
              type=\"".$this->_type."\"
              name=\"".$this->_name."\"
              $checked>\n";
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
    return "";
  }
}
?>
