<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.12.12
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */
namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

// Include parent class
require_once("dmn/classes/class.Pager.php");
/**
 * Describe pager list navigation for text file.
 */
class PagerFile extends Pager
{
  // File's name
  protected $_filename;
  // Quantity of position on page
  private $_pnumber;
  // Quantity of reference from left and right
  // from current page
  private $_page_link;
  // Parameters
  private $_parameters;
  // Constructor
  public function __construct($filename,
                              $pnumber = 10,
                              $page_link = 3,
                              $parameters = "")
  {
    $this->_filename = $filename;
    $this->_pnumber = $pnumber;
    $this->_page_link = $page_link;
    $this->_parameters = $parameters;
  }
  /**
   * Total quantity of position in list.
   * @return quantity fo records
   */
  public function get_total()
  {
    $countline = 0;
    // Open file
    $fd = fopen($this->_filename, "r");
    if($fd)
    {
      // Count quantity of records
      // in file
      while(!feof($fd))
      {
        fgets($fd,1024);
        $countline++;
      }
      // Close file
      fclose($fd);
    }
    return $countline;
  }

  /**
   * Quantity of position on the page.
   * @return int
   */
  public function get_pnumber()
  {
    // Quantity of position on page
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
    // Additional parameters, which
    // necessary to take along by reference
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
    // Check, is requested number of page
    // at the interval from 1 to get_total()
    if($page <= 0 || $page > $number) return 0;
    // Eject position of current page
    $arr = array();
    $fd = fopen($this->_filename, "r");
    if(!$fd) return 0;
    // Number, from whom begin choose
    // strings from file
    $first = ($page - 1)*$this->get_pnumber();
    for($i = 0; $i < $total; $i++)
    {
      $str = fgets($fd, 10000);
      // Finish iteration until
      // achieved number $first
      if($i < $first) continue;
      // If end of file is achieved
      // leave cycle
      if($i > $first + $this->get_pnumber() - 1) break;
      // Put line of file in array,
      // which to be going to return by method
      $arr[] = $str;
    }
    // Close file
    fclose($fd);

    return $arr;
  }
}
?>
