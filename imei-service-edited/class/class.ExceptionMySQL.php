<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.12.12
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */
 
class ExceptionMySQL extends Exception
{
  // Error message
  protected $_mysql_error;
  // SQL - query
  protected $_sql_query;

  public function __construct($mysql_error, $sql_query, $message)
  {
    $this->_mysql_error = $mysql_error;
    $this->_sql_query = $sql_query;
    // Invoke parent construct
    parent::__construct($message);
//      echo "<tt><pre>".print_r($message, true)."</pre><tt>";
  }

  public function getMySQLError()
  {
    return $this->_mysql_error;
  }

  public function getSQLQuery()
  {
    return $this->_sql_query;
  }
}

?>