<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 18:45
 */
namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );


// Include parent class
//require_once( "dmn/classes/class.Pager.php" );
require_once( "dmn/classes/class.PagerMysql.php" );
// Include exception for error handling
require_once( "dmn/base/Exceptions.php" );


/**
 * Describe pager navigation for MySQL.
 */
class PagerMysqlTwoTables extends PagerMysql {
//    // Name of table
//    protected $tablename;
    // Sort by id
    protected $id;
//    // WHERE-condition
//    protected $where;
//    // Sorting ORDER
//    protected $order;
//    // Quantity of position on the page
//    private $pnumber;
//    // Quantity or reference from left and right
//    // from current page
//    private $page_link;
//    // Parameters
//    protected $parameters;

    // Constructor
    public function __construct($tablename,
                                $where = "",
                                $order = "",
                                $pnumber = 10,
                                $page_link = 3,
                                $parameters = "",
                                $id)  {


//        $this->tablename   = $tablename;
//        $this->where       = $where;
//        $this->order       = $order;
//        $this->pnumber     = $pnumber;
//        $this->page_link   = $page_link;
//        $this->parameters  = $parameters;

        $this->id          = $id;

        parent::__construct( $tablename,
                            $where,
                            $order,
                            $pnumber,
                            $page_link,
                            $parameters );
    }

    /**
     * Total quantity of position in list.
     * @return quantity
     * @throws \dmn\base\AppException
     */
    public function getTotal()  {
        // Form query for take
        // total quantity of records in table
        $query = "SELECT COUNT(*) FROM {$this->tablename[1]}
              {$this->where}
              {$this->order}";

        $sth = $this->getStatement( $query );
        $result = $sth->execute();

        if( ! $result ) {
            throw new \dmn\base\AppException(  "Ошибка обращения к таблице
                              позиций - getTotal()");
        }
        return $sth->fetchColumn();
    }

//SELECT * FROM system_cart_orders t1
//INNER JOIN system_cart_items t2 ON t2.order_id = t1.id
//- $tablename
//- $tablename2
//- $field
//- $field2

    /**
     * Return array of Files's strings
     * by number page $index
     * @return array
     * @throws \dmn\base\AppException
     */
    public function getPage() {
        // Current page
        $page = intval($_GET['page']);
        if(empty($page)) $page = 1;
        // Quantity records in file
        $total = $this->getTotal();
        // Calculate number of pages in system
        $number = (int)($total/$this->getPnumber());
        if((float)($total/$this->getPnumber()) - $number != 0) $number++;
        // Eject position of current page
        $arr = array();
        // Number, from whom begin choose
        // strings from file
        $first = ($page - 1) * $this->getPnumber();
        // Take positions for current page
        $query = "SELECT * FROM {$this->tablename[0]} t1
                  INNER JOIN {$this->tablename[1]} t2
                  ON t2.{$this->id[1]} = t1.{$this->id[0]}
              {$this->where}
              {$this->order}
              LIMIT $first, {$this->getPnumber()}";
        $sth = $this->getStatement( $query );
        $result = $sth->execute();

        if( ! $result ) {
            throw new \dmn\base\AppException(  "Ошибка обращения к таблице
                              позиций - getPage()");
        }
//      echo "<tt><pre>".print_r($arr, true)."</pre></tt>";


        // If have at least one element,
        // fill the array $arr
//    while( $arr[] = $sth->fetchAll() );
//    // Remove last 0 element of array $arr
//    unset($arr[count($arr) - 1]);
//    return $arr;

        return $sth->fetchAll();
    }

}
?>