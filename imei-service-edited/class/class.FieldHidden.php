<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 22:29
 * To change this template use File | Settings | File Templates.
 */
// Error handling
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Describe control element "hidden"
 * <input type="hidden">
 */
class FieldHidden extends Field
{
   /**
   * Construct of class.
   */
  public function __construct($name,
                              $is_required = false,
                              $value = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "hidden",
                        "-",
                        $is_required,
                        $value,
                        $parameters,
                        "",
                        "");
  }

    /**
   * Function for name of field
   * and tag of control element
   * @return array
   */
  public function get_html()
  {
    // Form tag
    $tag = "<input type=\"".$this->_type."\"
                    name=\"".$this->_name."\"
                    value=\"".
                    htmlspecialchars($this->_value, ENT_QUOTES)."\">\n";
    // Return array for class Form
    return array("",$tag);
  }

    /**
   * Check correct of date
   * @return string
   */
  function check()
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
      if(empty($this->_value)) return "Скрытое поле не заполнено!";
    }
    return "";
  }
}
?>