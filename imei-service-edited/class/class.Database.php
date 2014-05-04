<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.12.12
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);


define('DB_HOST', 'localhost');
//define('DB_USER', 'root');
define('DB_USER', 'root');
define('DB_PASSWORD', 'zhalnin5334');
define('DB_DATABASE', 'imei-service');
$tbl_accounts           = 'system_accounts';

$tbl_catalog            = 'system_menu_catalog';
$tbl_position           = 'system_menu_position';
$tbl_paragraph          = 'system_menu_paragraph';
$tbl_paragraph_image    = 'system_menu_paragraph_image';

$tbl_cat_catalog        = 'system_catalog';
$tbl_cat_position       = 'system_position';

$tbl_photo_settings     = 'system_photo_settings';

// Новости
$tbl_news               = 'system_news';

// Контакты
$tbl_contactaddress     = 'system_contactaddress';

/**
 * MySQL database; only one connection is allowed.
 */
class Database
{
  // Store connection
  private $_connection;
  // Store the single instance
  private static $_instance;

  /**
   * Get an instance of the Database.
   * @return Database
   */
  public static function getInstance()
  {
    if(!self::$_instance)
    {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Constructor
   */
  private function __construct()
  {
    // Connect to Database
    $this->_connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if(!$this->_connection)
    {
      throw new ExceptionMySQL(mysql_error(),
            "connection",
            "Невозможно установить
             соединение с MySQL-сервером!");
    }
    // Shoose the table
    if(!mysql_select_db(DB_DATABASE, $this->_connection))
    {
      throw new ExceptionMySQL(mysql_error(),
            "select database",
            "Ошибка выбора базы данных!");
    }

    // Set code for database
    if(!mysql_query('SET names "utf8"'))
    {
      throw new ExceptionMySQL(mysql_error(),
            "encoding",
            "Невозможно установить
             кодировку MySQL-сервера!");
    }

  }

  /**
   * Return result of sql query
   * @static
   * @param $sql
   * @return bool|resource
   */
  public static function query($sql)
  {
              $obj=self::$_instance;
              if(isset($obj->_connection))
              {
//                  $obj->count_sql++;
//                  $start_time_sql = microtime(true);
                 $result=mysql_query($sql);//
//                  $result=mysql_query($sql)or die("<br/><span style='color:red'>Ошибка в SQL запросе:</span> ".mysql_error());
//                  $time_sql = microtime(true) - $start_time_sql;//
//                  echo "<br/><br/><span style='color:blue'> <span style='color:green'># Запрос номер ".$obj->count_sql.": </span>".$sql."</span> <span style='color:green'>(".round($time_sql,4)." msec )</span>";
                  return $result;
              }
              return false;
          }

  /**
   * Return record in object
   * @static
   * @param $object
   * @return object|stdClass
   */
  public static function fetch_object($object)
  {
      return @mysql_fetch_object($object);
  }

  /**
   * Return object
   * @static
   * @param $object
   * @return object|stdClass
   */
  public static function fetch_obj($object)
  {
      return @mysql_fetch_object($object);
  }

  /**
   * Return array
   * @static
   * @param $object
   * @return array
   */
  public static function fetch_array($object)
  {
      return @mysql_fetch_array($object);
  }

  //mysql_insert_id() возвращает ID,
  //сгенерированный колонкой с AUTO_INCREMENT последним запросом INSERT к серверу
  public static function insert_id()
  {
      return @mysql_insert_id();
  }

  
    /**
     * Return association array
     * @param $object
     * @return array
     */
    public static function fetch_assoc($object){
        return @mysql_fetch_assoc($object);
    }

    /**
     * Return next auto_increment id from table
     * @param $table
     * @return mixed
     */
    public static function get_next_id($table){
        $result = self::query("SHOW TABLE STATUS LIKE '$table'");
        $rows = self::fetch_assoc($result);
        return $rows['Auto_increment'];
    }


  /**
   * Empty clone magic method to prevent duplication.
   */
  public function __clone() {}

  /**
   * Empty wakeup magic method to prevent duplication.
   */
  public function __wakeup() {}

}


?>
