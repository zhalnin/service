<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 1:29
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element with only integer
 */
class FieldTextInt extends FieldText {
  // Min value of field
  protected $_min_value;
  // Max value of field
  protected $_max_value;

  // Construct of class
  public function __construct($name,
                              $caption,
                              $is_required = false,
                              $value = "",
                              $min_value = 0,
                              $max_value = 0,
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
    // Initiate class member
    $this->_min_value = intval($min_value);
    $this->_max_value = intval($max_value);

    // Min value should be more than max value
    if($this->_min_value > $this->_max_value)
    {
      throw Exception("Минимальное значение должно
                      быть больше максимального
                      значения. Поле \"{$this->_caption}\".");
    }
  }

  /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    $pattern = "|^[-\d]*$|i";
    if($this->_is_required)
    {
      if($this->_min_value != $this->_max_value)
      {
        if($this->_value < $this->_min_value ||
           $this->_value > $this->_max_value)
        {
          return "Поле \"".$this->_caption."\"
                  должно быть больше ".$this->_min_value."
                  и меньше ".$this->_max_value."";
        }
      }
      $pattern = "|^[-\d]+$|i";
    }
    if(!preg_match($pattern, $this->_value))
    {
      return "Поле \"".$this->_caption."\"
              должно содержать только цифры";
    }
    return "";
  }
}
?>