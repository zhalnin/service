<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19.12.12
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */


//require_once("../../class/class.Database.php");
// Include parent class
require_once("class.Pager.php");
// Include exception for error handling
// while refferring to the table
require_once("class.ExceptionMySQL.php");


Database::getInstance();

/**
 * Describe pager navigation for MySQL.
 */
class PagerMysql extends Pager
{
  // Name of table
  protected $_tablename;
  // WHERE-condition
  protected $_where;
  // Sorting ORDER
  protected $_order;
  // Quantity of position on the page
  private $_pnumber;
  // Quantity or reference from left and right
  // from current page
  private $_page_link;
  // Parameters
  protected $_parameters;
  // Constructor
  public function __construct($tablename,
                              $where = "",
                              $order = "",
                              $pnumber = 10,
                              $page_link = 3,
                              $parameters = "")
  {
    $this->_tablename   = $tablename;
    $this->_where       = $where;
    $this->_order       = $order;
    $this->_pnumber     = $pnumber;
    $this->_page_link   = $page_link;
    $this->_parameters  = $parameters;
  }

    /**
   * Total quantity of position in list.
   * @return quantity fo records
   */
  public function get_total()
  {
    // Form query for take
    // total quantity of records in table
    $query = "SELECT COUNT(*) FROM {$this->_tablename}
              {$this->_where}
              {$this->_order}";
    $tot = mysql_query($query);
    if(!$tot)
    {
      throw new ExceptionMySQL(mysql_error(),
                              $query,
                              "Ошибка подсчета количества
                              позиций!");
    }
    return mysql_result($tot, 0);
  }

  /**
   * Quantity of position on the page.
   * @return int
   */
  public function get_pnumber()
  {
    // Quantity of position on the page
    return $this->_pnumber;
  }

  /**
   * Quantity of position from left and right
   * form current page.
   * @return int
   */
  public function get_page_link()
  {
    // Quantity of references from left and right
    // from current page
    return $this->_page_link;
  }

  /**
   * String to take along by reference
   * to the other page.
   * @return string
   */
  public function get_parameters()
  {
    // Additional parameters that
    // is necessarily to take by reference
    return $this->_parameters;
  }

  /**
   * Return array of Files's strings
   * by number page $index
   * @return array $arr
   */
  public function get_page()
  {
    // Current page
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    // Quantity records in file
    $total = $this->get_total();
    // Calculate number of pages in system
    $number = (int)($total/$this->get_pnumber());
    if((float)($total/$this->get_pnumber()) - $number != 0) $number++;
    // Eject position of current page
    $arr = array();
    // Number, from whom begin choose
    // strings from file
    $first = ($page - 1) * $this->get_pnumber();
    // Take positions for current page
    $query = "SELECT * FROM {$this->_tablename}
              {$this->_where}
              {$this->_order}
              LIMIT $first, {$this->get_pnumber()}";
    $tbl = mysql_query($query);
    if(!$tbl)
    {
      throw new ExceptionMySQL(mysql_error(),
                              $query,
                              "Ошибка обращения к таблице
                              позиций");
    }
    // If have at least one element,
    // fill the array $arr
    if(mysql_num_rows($tbl))
    {
      while($arr[] = mysql_fetch_array($tbl));
    }
    // Remove last 0 element of array $arr
    unset($arr[count($arr) - 1]);
    return $arr;
  }
}
?>