<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 11:06
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "text" for email
 * <input type="text">
 */
class FieldTextEmail extends FieldText
{

  /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    // If field is required
    if($this->_is_required || !empty($this->_value))
    {
      $pattern = "|^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,6}$|i";
      // Check if it empty
      if(!preg_match($pattern,$this->_value))
      {
        return "Введите email в виде <i>mysite@mydomain.ru</i>";
      }
    }
    return "";
  }
}
?>