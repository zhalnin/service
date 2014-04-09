<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.12.12
 * Time: 13:30
 * To change this template use File | Settings | File Templates.
 */

/**
 * Arise when missing member of class
 * is initialized
 */
class ExceptionMember extends Exception
{
  // Name of noneexistent member
  protected $_key;

  public function __construct($key, $message)
  {
    // protected member
    $this->key = $key;

    // Invoke construct of parent class
    parent::__construct($message);
  }

  // Take protected member
  public function getKey()
  {
    return $this->_key;
  }
}
?>