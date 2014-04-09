<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 0:58
 * To change this template use File | Settings | File Templates.
 */
 

/**
 * Describe control element "password"
 * <input type="password">
 */
class FieldPassword extends FieldText
{
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
                        $caption,
                        $is_required,
                        $value,
                        $maxlength,
                        $size,
                        $parameters,
                        $help,
                        $help_url);
    $this->_type = "password";
  }
}
?>