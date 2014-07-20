<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/07/14
 * Time: 15:47
 */

namespace dmn\view;
error_reporting(E_ALL & ~E_NOTICE);

//$collection = \dmn\domain\CartOrder::findAll();
//$cartOrderCollection = \dmn\domain\CartOrder::findAll();
//
//foreach ( $cartOrderCollection as $collection ) {
//    print $collection->getFirstName()."<br />";
//}
//
//
//echo "<tt><pre>".print_r( $collection, true )."</pre></tt>";



//require_once("../../class/class.Database.php");
// Include parent class
require_once( "dmn/classes/class.PagerMysqlTwoTables.php" );
// Include exception for error handling
require_once( "dmn/base/Exceptions.php" );


/**
 * Describe pager navigation for MySQL.
 */
class PagerMysqlTwoTable extends \dmn\classes\Pager {
    // Name of table
    protected $tablename;
    // Sort by id
    protected $id;
    // WHERE-condition
    protected $where;
    // Sorting ORDER
    protected $order;
    // Quantity of position on the page
    private $pnumber;
    // Quantity or reference from left and right
    // from current page
    private $page_link;
    // Parameters
    protected $parameters;

    // Constructor
    public function __construct($tablename,
                                $where = "",
                                $order = "",
                                $pnumber = 10,
                                $page_link = 3,
                                $parameters = "",
                                $id)  {


        $this->tablename   = $tablename;
        $this->where       = $where;
        $this->order       = $order;
        $this->pnumber     = $pnumber;
        $this->page_link   = $page_link;
        $this->parameters  = $parameters;
        $this->id          = $id;

        parent::__construct();
    }

    /**
     * Total quantity of position in list.
     * @return quantity fo records
     */
    public function get_total()  {
        // Form query for take
        // total quantity of records in table
        $query = "SELECT COUNT(*) FROM {$this->tablename[1]}
              {$this->where}
              {$this->order}";

        $sth = $this->getStatement( $query );
        $result = $sth->execute();

        if( ! $result ) {
            throw new \dmn\base\AppException(  "Ошибка обращения к таблице
                              позиций - get_total()");
        }
        return $sth->fetchColumn();
    }

    /**
     * Quantity of position on the page.
     * @return int
     */
    public function get_pnumber() {
        // Quantity of position on the page
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
    public function get_parameters()  {
        // Additional parameters that
        // is necessarily to take by reference
        return $this->parameters;
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
     * @return array $arr
     */
    public function getPage() {
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
        $query = "SELECT * FROM {$this->tablename[0]} t1
                  INNER JOIN {$this->tablename[1]} t2
                  ON t2.{$this->id[1]} = t1.{$this->id[0]}
              {$this->where}
              {$this->order}
              LIMIT $first, {$this->get_pnumber()}";
        $sth = $this->getStatement( $query );
        $result = $sth->execute();

        if( ! $result ) {
            throw new \dmn\base\AppException(  "Ошибка обращения к таблице
                              позиций - get_page()");
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


<?php


try {

//    require_once( "dmn/classes/class.PagerMysql.php" );
    require_once( "dmn/command/Command.php" );
    require_once( "dmn/base/Registry.php" );
    require_once( "dmn/domain/CartOrder.php" );
    require_once( "dmn/view/utils/printPage.php" );


    // Данные переменные определяют название страницы и подсказку
    $title      = 'Управление блоком "Блок новостей"';
    $pageinfo   = '<p class=help>Здесь можно добавить
                    новостной блок, отредактировать или удалить уже
                    существующий блок.</p>';

    // Включаем заголовок страницы
    require_once("dmn/view/templates/top.php");

    // Содержание страницы

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляеи объект постраничной навигации
    $obj = new \dmn\classes\PagerMysqlTwoTables(array('system_cart_orders','system_cart_items'),
        "",
        "",
        $pnumber,
        $page_link,
        "&cmd=CartOrder",
        array('id','order_id') );

//    echo "<tt><pre>".print_r($obj, true)."</pre></tt>";
    // Добавить блок
    echo "<a href=?cmd=CartOrder&pact=add&page=$_GET[page]
                    title=Добавить новостной блок>
                    Добавить новостной блок</a><br><br>";

    // Получаем содержимое текущей страницы
//    $cartOrder = $obj->get_page();
    $cartOrder = $obj->getPage();
//    echo "<tt><pre>".print_r( $cartOrder, true )."</pre></tt>";
    // Если имеется хотя бы одна запись - выводим ее
    if( ! empty( $cartOrder ) ) {
        // Выводим ссылки на другие страницы
        echo $obj->print_page();
        echo "<br /><br />";
        ?>
        <table width="100%"
               class="table"
               border="0"
               cellpadding="0"
               cellspacing="0">
            <tr class="header" align="center">
                <td width="10%">Дата</td>
                <td width="10%">Email</td>
                <td >Описание</td>
                <td width="7%">Стоимость</td>
                <td width="10%">PayPal</td>
                <td width="7%">Статус</td>
                <td width="7%">Действия</td>
            </tr>
    <?php
    for($i = 0; $i < count($cartOrder); $i++) {
        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $url = "id={$cartOrder[$i][id]}&page=$_GET[page]";

        // Преобразуем дату из формата MySQL YYYY-MM-DD hh:mm:ss
        // в формат DD.MM.YYYY hh:mm:ss
        list($date, $time) = explode(" ", $cartOrder[$i]['created_at']);
        list($year, $month, $day) = explode("-", $date);
        $cartOrder[$i]['created_at'] = "$day.$month.$year $time";

        // Выводим новость
        echo "<tr>
                        <td><p align='center'>{$cartOrder[$i][created_at]}</td>
                        <td align=center>{$cartOrder[$i][email]}</td>
                        <td align=center>{$cartOrder[$i]['title']}<br /><br />{$cartOrder[$i]['data']}</td>
                        <td align=center>{$cartOrder[$i][price]}</td>
                        <td align=center>{$cartOrder[$i][paypal_trans_id]}</td>
                        <td align=center>{$cartOrder[$i][status]}</td>

                        <td align=center>
                            <a href=?cmd=CartOrder&pact=detail&$url title='Детальный просмотр'>Просмотр</a><br/>
                            <a href=?cmd=CartOrder&pact=edit&$url title='Редактировать текст новости'>Редактировать</a><br/>
                            <a href=# onClick=\"delete_position('?cmd=CartOrder&pact=del&$url',".
            "'Вы действительно хотите удалить раздел?');\"  title='Удалить новость'>Удалить</a><br/>
                      </td>


                    </tr>";
    }
    echo "</table><br>";
    }

    // Выводим ссылки на другие страницы
    echo $obj;

    // Включаем завершение страницы
    require_once("dmn/view/templates/bottom.php");

} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}
?>