<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19.12.12
 * Time: 14:53
 * To change this template use File | Settings | File Templates.
 */

require_once("class.PagerFile.php");
/**
 * Describe pager file search for file.
 */
class PagerFileSearch extends PagerFile
{
  // Start of word
  private $_search;
  // Constructor
  public function __construct($search,
                              $filename,
                              $pnumber = 10,
                              $page_link = 3)
  {
    parent::__construct($filename,
                        $pnumber,
                        $page_link,
                        "&search=".urlencode($search));
    $this->_search = $search;
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
        $str = fgets($fd, 10000);
        if(preg_match("|^".preg_quote($this->_search)."|i",$str))
        {
          $countline++;
        }
      }
      // Close file
      fclose($fd);
    }
    return $countline;
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

    while(!feof($fd))
    {
      $str = fgets($fd, 10000);
      if(preg_match("|^".preg_quote($this->_search)."|i", $str))
      {
        $countline++;
        // Finish iteration until
        // achieved number $first
        if($countline < $first + 1) continue;

        // Insert strings in array
        // which are going to return by method
        $arr[] = $str;

        // If end of file is achieved
        // leave cycle
        if($countline >= $first + $this->get_pnumber()) break;
      }
    }
    // Close file
    fclose($fd);

    return $arr;
  }
}
?>