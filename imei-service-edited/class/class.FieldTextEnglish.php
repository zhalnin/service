<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 1:06
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element with only English text
 */
class FieldTextEnglish extends FieldText
{
  // Check given data
  function check()
  {
    // Safe data before insert into database
    if(!get_magic_quotes_gpc())
    {
      $this->_value = mysql_real_escape_string($this->_value);
    }
    if($this->_is_required) $pattern = "|^[a-z]+$|i";
    else $pattern = "|^[a-z]*$|i";

    // Check symbols in field "value"
    // for english alphabet only
    if(!preg_match($pattern, $this->_value))
    {
      return "Поле \"{$this->_caption}\"
              должно содержать только символы латинского алфавита.";
    }
    return "";
  }
}
?>