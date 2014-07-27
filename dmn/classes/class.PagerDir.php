<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19.12.12
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

// Include parent class
require_once("dmn/classes/class.PagerFile.php");

/**
 * Describe pager dir navigation for directory.
 */
class PagerDir extends Pager {
  // Dir's name
  protected $dirname;
  // Quantity of positions on the page
  private $pnumber;
  // Quantity of references from left and right
  // from current page
  private $page_link;
  // Parameters
  private $parameters;
  // Constructor
  public function __construct( $dirname,
                              $pnumber = 10,
                              $page_link = 3,
                              $parameters = "" ) {
      
    $this->dirname = trim($dirname, "/");
    $this->pnumber = $pnumber;
    $this->page_link = $page_link;
    $this->parameters = $parameters;
  }

  /**
   * Total quantity of position in list.
   * @return quantity fo records
   */
  public function get_total()  {
    $countline = 0;
    // Test for file not to start with .(.htaccess)
    $pattern = "|^[^\.].*$|";
    // Open directory
    if( ( $dir = opendir( $this->dirname ) ) !== false ) {
      while( ( $file = readdir( $dir ) ) !== false ) {
        // If current position is file
        // take this into account and not to start with .(.htaccess)
        if( is_file( $this->dirname."/".$file ) &&
          preg_match( $pattern,$file ) ) $countline++;
      }
      // Close directory
      closedir( $dir );
    }
    return $countline;
  }

  /**
   * Quantity of position on the page.
   * @return int
   */
  public function get_pnumber() {
    // Quantity of position on page
    return $this->pnumber;
  }

  /**
   * Quantity of position from left and right
   * form current page.
   * @return int
   */
  public function get_page_link() {
    // Quantity of references from left and right
    // from current page
    return $this->page_link;
  }

    /**
   * String to take along by reference
   * to the other page.
   * @return string
   */
  public function get_parameters() {
    // Additional parameters, which
    // necessary to take along by reference
    return $this->parameters;
  }

    /**
   * Return array of Files's strings
   * by number page $index
   * @return array $arr
   */
  public function get_page() {
    // Test for file not to start with .(.htaccess)
    $pattern = "|^[^\.].*$|";
    // Current page
    $page = intval( $_GET['page'] );
    if( empty( $page ) ) $page = 1;
    // Quantity of records in file
    $total = $this->get_total();
    // Calculate quantity of pages in system
    $number = (int)( $total/$this->get_pnumber() );
    if( (float)( $total/$this->get_pnumber() ) - $number != 0 ) $number++;
    // Check requested number of page is
    // between 1 and get_total
    if( $page <= 0 || $page > $number ) return 0;
    // Eject positions from current page
    $arr = array();
    // Number, from whom begin choose
    // strings from file
    $first = ( $page - 1 )*$this->get_pnumber();
    // Open directory
    if( ( $dir = opendir( $this->dirname ) ) === false ) return 0;
    $i = -1;
    while( ( $file = readdir( $dir ) ) !== false ) {
      // If current position is file and
      // not to start with .(.htaccess)
      if( is_file( $this->dirname."/".$file ) &&
          preg_match( $pattern,$file ) ) {
        // Increment count
        $i++;
        // While number $first did not reach
        // finish iteration
        if( $i < $first ) continue;
        // If end sample is reached
        // leave cycle
        if( $i > $first + $this->get_pnumber() - 1 ) break;
        // Place path to files in array
        // which are going to be returned by method
        $arr[] = $this->dirname."/".$file;
      }
    }
    // Close directory
    closedir( $dir );

    return $arr;
  }
}
?>