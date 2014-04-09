<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 23:37
 * To change this template use File | Settings | File Templates.
 */

// Error handling
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Describe control element "hidden" for value integer
 * <input type="hidden">
 */
class FieldHiddenInt extends FieldHidden
{
   /**
   * Check correct of date
   * @return string
   */
  function check()
  {
    $pattern = "|^[\d]+$|";
    // If field is required
    if($this->_is_required)
    {
      // Check if it is integer
      if(!preg_match($pattern, $this->_value))
      {
        return "Скрытое поле должно быть целым числом!";
      }
    }
    // If field is not required
    $pattern = "|^[\d]*$|";
    if(!preg_match($pattern, $this->_value))
    {
      return "Скрытое поле должно быть целым числом!";
    }
    return "";
  }
}
?>