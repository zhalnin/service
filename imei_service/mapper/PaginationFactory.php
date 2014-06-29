<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/05/14
 * Time: 21:39
 */

namespace imei_service\mapper;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/base/Exceptions.php" );

/**
 * Class PaginationFactory
 * Выполняет функцию постраничной навигации
 * Необходим для таких плагинов, как гостевая книга, комментарии блогов
 * @package imei_service\mapper
 */
abstract class PaginationFactory {

    protected function __construct() {}

    abstract protected function getTotal();
    abstract protected function getPageNumber();
    abstract protected function getPageLink();
    abstract protected function getParameters();

    /**
     * * Возвращает постраничную навигацию типа: " << ... < ... 1 2 3 ... > ... >> "
     * @return string
     */
    public function printPage() {
        // (string) - возвращаем результат
        $returnPage = "";
        // Для передачи позиции текущей страницы
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) $page = 1;
        $number = (int)( $this->getTotal() / $this->getPageNumber() );
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

//        // Двойная стрелка для перелистывания в начало
//        $returnPage .= "<a href='".
//                        "?page=1{$this->getParameters()}'>".
//                        "&lt;&lt;</a> ... ";

        // Если это не первая страница - то выводим стрелку для одиночного
        // пролистывания
        if( $page != 1 ) {
            // Двойная стрелка для перелистывания в начало
            $returnPage .= "<a href='".
                "?cmd=Guestbook&page=1{$this->getParameters()}'>".
                "&lt;&lt;</a> ... ";

            $returnPage .= " <a href='"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>"
                ."&lt;</a> ... ";
        }

        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "$i ";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Если это не последняя страница, то выводим стрелку для
        // единичного перелистывания
        if( $page != $number ) {
            $returnPage .= " ... <a href='?cmd=Guestbook&page="
                .($page+1)
                ."{$this->getParameters()}'>"
                ."&gt;</a>";
            // Двойная стрелка для перелистывания в конец
            $returnPage .= " ... <a href='"
                ."?cmd=Guestbook&page=$number{$this->getParameters()}'>"
                ."&gt;&gt;</a>";
        }

//        // Двойная стрелка для перелистывания в конец
//        $returnPage .= " ... <a href='"
//            ."?page=$number{$this->getParameters()}'>"
//            ."&gt;&gt;</a>";


        return $returnPage;
    }


    /**
     * Возвращает постраничную навигацию типа: " Предыдущая Следующая 1 2 3 "
     * @return string
     */
    public function printPageNav() {
        // (string) - возвращаем результат
        $returnPage = "";
        // Для передачи позиции текущей страницы
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) $page = 1;
        $number = (int)( $this->getTotal() / $this->getPageNumber() );
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

        $returnPage .= "<span class='pagination'>
                            <span class='pagination-prevnext'>";
        // Если это первая страница - то выводим <span>
        if( $page == 1 ) {
            $returnPage .= "<span class='pagination-prev-inactive'>&nbsp;Предыдущая&nbsp;</span>";
            // Если это не первая страница - то выводим стрелку для одиночного
            // пролистывания
        } else {
            $returnPage .= "<a class='pagination-prev' href='"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>&nbsp;"
                ."Предыдущая&nbsp;</a>";
        }
        // Если это последняя страница, то выводим <span>
        if( $page == $number ) {
            $returnPage .= "<span class='pagination-next-inactive'>&nbsp;Следующая&nbsp;</span>";
            // Если это не последняя страница, то выводим стрелку для
            // единичного перелистывания
        } else {
            $returnPage .= "<a class='pagination-next' href='?cmd=Guestbook&page="
                .($page+1)
                ."{$this->getParameters()}'>&nbsp;"
                ."Следующая&nbsp;</a>";
        }

        $returnPage .= "</span>&nbsp;<span class='pagination-numbers'>";




        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "&nbsp;<span class='pagination-current'>$i</span>&nbsp;";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
        }

        $returnPage .= "</span></span>";
        return $returnPage;
    }

    /**
     * * Возвращает постраничную навигацию типа: " [1-10][11-20] ... [31-40][41-50] "
     * @return string
     */
    public function __toString() {
        $returnPage = "";

        $page = intval( $_GET['page'] );
        if( empty( $page ) ) { $page = 1; }

        $number = (int)($this->getTotal() / $this->getPageNumber() );
        // Если число нечетное, то прибавляем еще единицу
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

        // Если страница 5 и более, то выводим ссылку на блок из 10-ти страниц
        // [1-10] - всего желаемое отображение 10-ти блоков на странице
        if( $page - $this->getPageLink() > 1 ) {
            $returnPage .= "<a href='"
                ."?cmd=Guestbook&page=1{$this->getParameters()}'>"
                ."[1-{$this->getPageNumber()}]"
                ."</a>&nbsp;&nbsp; ... &nbsp;&nbsp;";
            // Выводим три ссылки на следующие страницы
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='"
                    ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                    ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                    ."-".$i * $this->getPageNumber()
                    ."]</a>&nbsp;";
            }
            // Если страница 4 и до 2-х
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='"
                    ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                    ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                    ."-".$i * $this->getPageNumber()
                    ."]</a>&nbsp;";
            }
        }

        // Если все меньше 11-ти (их всего 11)
        if( $page + $this->getPageLink() < $number ) {
            // Пока страницы меньше или равно сумме страницы + 10, т.е. 1 <= 11
            for( $i = $page; $i <= $page + $this->getPageLink(); $i++ ) {
                // Если страница попдает в текущий блок
                if( $page == $i ) {
                    $returnPage .= "&nbsp;["
                        .( ( $i - 1) * $this->getPageNumber() + 1 )
                        ."-".$i * $this->getPageNumber()
                        ."]&nbsp;";
                } else {
                    // Если не текущая, то выводим три блока по 10
                    $returnPage .= "&nbsp;<a href='"
                        ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                        ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                        ."-". $i * $this->getPageNumber()
                        ."]</a>&nbsp;";
                }
            }
            // Выводим ссылку на последнюю страницу
            $returnPage .= "&nbsp; ... &nbsp;&nbsp;"
                ."<a href='"
                ."?cmd=Guestbook&page=$number{$this->getParameters()}'>"
                ."[".( ( $number - 1 ) * $this->getPageNumber() + 1 )
                ."-{$this->getTotal()}]</a>&nbsp;";
        } else {
            for( $i = $page; $i <= $number; $i++ ) {
                // Если это текущая страница
                if( $number == $i ) {
                    // Если это последний блок страниц
                    if( $page == $i ) {
                        // Указываем текущий последний блок страниц
                        $returnPage .= "&nbsp;["
                            .( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-{$this->getTotal()}]&nbsp;";
                    } else {
                        // Если не последний блок не текущий, то отображаем его,
                        // как ссылки
                        $returnPage .= "&nbsp;<a href='"
                            ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                            ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-{$this->getTotal()}]</a>&nbsp;";
                    }
                } else {
                    // Если это не последняя текущая страница,
                    // но текущая, то отображаем ее
                    if( $page == $i ) {
                        $returnPage .= "&nbsp;["
                            .( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-".$i * $this->getPageNumber()
                            ."]&nbsp;";
                    } else {
                        // Отображаем блоки страниц справа от текущей
                        $returnPage .= "&nbsp;<a href='"
                            ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                            ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                            ."-".$i * $this->getPageNumber()
                            ."]</a>&nbsp;";
                    }
                }
            }
        }
        return $returnPage;
    }
}


/**
 * Class GuestbookPaginationFactory
 * Постраничная навигация для Гостевой книги
 * @package imei_service\mapper
 */
class GuestbookPaginationFactory extends PaginationFactory  {
    protected static $PDO;
    // Имя таблицы в БД
    protected $tableName;
    // Условие WHERE
    protected $where;
    // Условие ORDER BY
    protected $order;
    // Количество позиций на странице
    protected $pageNumber;
    // Количество ссылок на другие страницы
    // слева и справа от текущей позиции
    protected $pageLink;
    // Дополнительные параметры
    protected $parameters;


    public function __construct( $tableName,
                                 $where = "",
                                 $order = "",
                                 $pageNumber = 10,
                                 $pageLink = 3,
                                 $parameters = "" ) {

        $this->tableName = $tableName;
        $this->where = $where;
        $this->order = $order;
        $this->pageNumber = $pageNumber;
        $this->pageLink = $pageLink;
        $this->parameters = $parameters;

        if( ! isset( self::$PDO ) ) {
            $dsn = \imei_service\base\DBRegistry::getDB();
            if( is_null( $dsn ) ) {
                throw new \imei_service\base\AppException( "No DSN" );
            }
            self::$PDO = $dsn;
        }
    }


    function getStatement( $str ) {
        if( ! isset( $this->statements[$str] ) ) {
            $this->statements[$str] = self::$PDO->prepare( $str );
        }
        return $this->statements[$str];
    }


    public function getTotal() {
        list( $where, $values ) = $this->buildWhere( $this->where );
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $sth = $this->getStatement( "SELECT COUNT(*) FROM {$this->tableName}
                                        {$where} {$this->order}" );

        $this->bindWhereValue( $sth, $this->where );

        $result = $sth->execute();
        if( ! $result ) {
            throw new \PDOException("Ошибка при подсчете позиций в getTotal()" );
        }
        $count = $sth->fetchColumn();
        $sth->closeCursor();
        return $count;
    }

    public function getPageNumber() { return $this->pageNumber; }

    public function getPageLink() { return $this->pageLink; }

    public function getParameters() { return $this->parameters; }

    public function getPage() {
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) { $page = 1; }
        $total = $this->getTotal();
        $number = (int)( $total / $this->getPageNumber() );
        if( (float)( $total / $this->getPageNumber() - $number ) != 0 ) { $number++; }
        $arr = array();
        $first = ( $page - 1 ) * $this->getPageNumber();

        list( $where, $values ) = $this->buildWhere( $this->where );
        $fields = implode( ",", $this->where->getObjectFields() );
//        echo "<tt><pre>".print_r($values, true)."</pre></tt>";
        $sth = $this->getStatement( "SELECT $fields FROM $this->tableName "."$where "."{$this->order} "." LIMIT :start, :end ");

        $this->bindWhereValue( $sth, $this->where );
        $sth->bindValue(':start', intval($first), \PDO::PARAM_INT );
        $sth->bindValue(':end', intval($this->getPageNumber()), \PDO::PARAM_INT );

        $result = $sth->execute( );
        if( ! $result ) {
            throw new \PDOException( "Ошибка при выборке в getPage()" );
        }
//        while( $arr[] = $sth->fetchAll() );
//        unset( $arr[count($arr) - 1]);
//        $sth->closeCursor();

        $arr = $sth->fetchAll();
        return $arr;
    }


    /**
     * Строим конструкцию Where
     * WHERE name = :name AND id = :id
     * @param IdentityObject $obj
     * @return array
     */
    function buildWhere( IdentityObject $obj ) {
        if( $obj->isVoid() ) {
            return array( "", array() );
        }
        $compstrings = array();
        $values = array();
        foreach ($obj->getComps() as $comp) {
            $compstrings[] = "{$comp['name']} {$comp['operator']} :{$comp['name']} ";
            $values[] = ":{$comp['name']} , {$comp['value']}";
        }
        $where = "WHERE " . implode( " AND ", $compstrings );
        return array( $where, $values );
    }

    /**
     * Связывает имя переменной с ее значением
     * sth->bindValue(:name,'Alex')
     * @param $sth
     * @param $obj
     */
    function bindWhereValue( $sth, $obj) {
//        echo "<tt><pre>".print_r($sth, true)."</pre></tt>";
        foreach( $obj->getComps() as $val ) {
            $sth->bindValue(":".$val['name'],$val['value']);
        }
    }
}


/**
 * Class SearchPaginationFactory
 * Постраничная навигация для Поиска
 * @package imei_service\mapper
 */
class SearchPaginationFactory extends PaginationFactory  {
    protected static $PDO;
    // Имя таблицы в БД
    protected $tableName;
    // Условие WHERE
    protected $where;
    // Условие ORDER BY
    protected $order;
    // Количество позиций на странице
    protected $pageNumber;
    // Количество ссылок на другие страницы
    // слева и справа от текущей позиции
    protected $pageLink;
    // Дополнительные параметры
    protected $parameters;
    protected $search_news;
    protected $search_faq;
    protected $search_service;
    protected $search_catalog_service;


    public function __construct( $tableName ,
                                 $where = "",
                                 $order = "",
                                 $pageNumber = 10,
                                 $pageLink = 3,
                                 $parameters = "") {

        $this->tableName = $tableName;
        $this->where = $where;
        $this->order = $order;
        $this->pageNumber = $pageNumber;
        $this->pageLink = $pageLink;
        $this->parameters = $parameters;

        // разбиваем строку запроса по пробелам
        $qSearch = preg_split("|[\s]+|", $_GET['q']  );

        // проходим в цикле по полученному массиву и создаем 4 дополнительных массива для SELECT
        foreach( $qSearch as $qLine ) {
            // system_news
            $this->search_news[] = "( ( LCASE( {$this->tableName[0]}.name ) RLIKE '".$qLine."') OR ( LCASE( {$this->tableName[0]}.body ) RLIKE '".$qLine."' ) )";
            // system_menu_paragraph
            $this->search_faq[] = "( LCASE( {$this->tableName[1]}.name ) RLIKE '".$qLine."')";
            // system_position
            $this->search_service[] = "( LCASE( {$this->tableName[2]}.operator ) RLIKE '".$qLine."')";
            // system_catalog
            $this->search_catalog_service[] = "( LCASE( {$this->tableName[3]}.name ) RLIKE '".$qLine."')";
        }

        if( ! isset( self::$PDO ) ) {
            $dsn = \imei_service\base\DBRegistry::getDB();
            if( is_null( $dsn ) ) {
                throw new \imei_service\base\AppException( "No DSN" );
            }
            self::$PDO = $dsn;
        }
    }


    function getStatement( $str ) {
        if( ! isset( $this->statements[$str] ) ) {
            $this->statements[$str] = self::$PDO->prepare( $str );
        }
        return $this->statements[$str];
    }


    public function getTotal() {

        $count = 0;
        $stmt0 = "SELECT COUNT(system_news.id_news)
                    FROM system_news
                    WHERE ".implode(" AND ",$this->search_news)." AND
                                    system_news.hide = 'show'";
        $stmt1 = "SELECT COUNT(DISTINCT system_menu_position.id_position)
                    FROM system_menu_paragraph, system_menu_position
                    WHERE ".implode(" AND ", $this->search_faq)." AND
                                    system_menu_position.hide = 'show' AND
                                    system_menu_paragraph.hide = 'show' AND
                                    system_menu_position.id_position = system_menu_paragraph.id_position";
        $stmt2 = "SELECT COUNT( system_position.id_catalog )
                            FROM system_position, system_catalog
                            WHERE ".implode(' AND ', $this->search_service )."
                                            AND system_position.hide = 'show'
                                            AND system_catalog.hide = 'show'
                                            AND system_position.id_catalog = system_catalog.id_catalog";
        $stmt3 = "SELECT COUNT( system_catalog.id_catalog )
                              FROM system_catalog
                              WHERE ".implode(' AND ', $this->search_catalog_service )."
                                                AND system_catalog.hide = 'show'";
//        echo "<tt><pre> first - ".print_r( $stmt1 , true)."</pre></tt>";
        $res[] = $this->getStatement( $stmt0 );
        $res[] = $this->getStatement( $stmt1 );
        $res[] = $this->getStatement( $stmt2 );
        $res[] = $this->getStatement( $stmt3 );

        foreach( $res as $r ) {
            $result = $r->execute();
            if( ! $result ) {
                throw new \PDOException("Ошибка при подсчете позиций в getTotal()" );
            }
            $count += $r->fetchColumn();
            $r->closeCursor();
        }

//        echo "<tt><pre>".print_r( $count , true)."</pre></tt>";
        return $count;
    }

    public function getPageNumber() { return $this->pageNumber; }

    public function getPageLink() { return $this->pageLink; }

    public function getParameters() { return $this->parameters; }

    public function getPage() {
        $page = intval( $_GET['page'] );
        if( empty( $page ) ) { $page = 1; }
        $total = $this->getTotal();
//        echo "<tt><pre>".print_r($total, true)."</pre></tt>";
        $number = (int)( $total / $this->getPageNumber() );
        if( (float)( $total / $this->getPageNumber() - $number ) != 0 ) { $number++; }
        $arr = array();
        $first = ( $page - 1 ) * $this->getPageNumber();
//        echo "<tt><pre> first - ".print_r( $first , true)."</pre></tt>";
//        echo "<tt><pre> total - ".print_r( $total , true)."</pre></tt>";

        $tmp = "        SELECT system_menu_position.id_position AS id_position,
                                    system_menu_position.id_catalog AS id_catalog,
                                    system_menu_position.name AS name,
                                    'faq' AS link,
                                    '' AS type,
                                    '' AS ctr
                            FROM system_menu_paragraph, system_menu_position
                            WHERE ".implode(' AND ', $this->search_faq)."
                                                AND system_menu_paragraph.hide = 'show'
                                                AND system_menu_position.hide = 'show'
                                                AND system_menu_position.id_position = system_menu_paragraph.id_position
                            GROUP BY system_menu_position.id_position
                            UNION
                            SELECT system_position.id_position AS id_position,
                                 system_position.id_catalog AS id_catalog,
                                 system_position.operator AS name,
                                 'service' AS link,
                                 '' AS type,
                                 '' AS ctr
                            FROM system_position, system_catalog
                            WHERE ".implode(' AND ', $this->search_service)."
                                            AND system_position.hide = 'show'
                                            AND system_catalog.hide = 'show'
                                            AND system_position.id_catalog = system_catalog.id_catalog
                            GROUP BY system_position.id_catalog
                            UNION
                            SELECT 0,
                                 system_catalog.id_catalog AS id_catalog,
                                 system_catalog.name AS name,
                                 'service_catalog' AS link,
                                 system_catalog.modrewrite AS type,
                                 system_catalog.abbreviatura AS ctr
                            FROM system_catalog
                            WHERE ".implode(' AND ', $this->search_catalog_service )."
                                            AND system_catalog.hide = 'show'
                            GROUP BY system_catalog.id_catalog
                            UNION
                            SELECT system_news.id_news AS id_position,
                                     0,
                                     system_news.name AS name,
                                     'news' AS link,
                                     '' AS type,
                                     '' AS ctr
                            FROM system_news
                            WHERE ".implode(' AND ', $this->search_news )."
                                               AND system_news.hide = 'show'
                            ORDER BY name
                            LIMIT $first, {$this->getPageNumber()}";

        $sth = $this->getStatement( $tmp );
//        echo "<tt><pre>".print_r( $sth , true)."</pre></tt>";
        $result = $sth->execute( );
        if( ! $result ) {
            throw new \PDOException( "Ошибка при выборке в getPage()" );
        }
        $arr = $sth->fetchAll();
        return $arr;
    }


    /**
     * Возвращает постраничную навигацию типа: " Предыдущая Следующая 1 2 3 "
     * @return string
     */
    public function printPageSearchNav() {
        // (string) - возвращаем результат
        $returnPage = "";
        // Для передачи позиции текущей страницы
        $page = intval( $_GET['page'] );
        $q = $_GET['q'];
        if( empty( $page ) ) $page = 1;
        $number = (int)( $this->getTotal() / $this->getPageNumber() );
        if( (float)( $this->getTotal() / $this->getPageNumber() ) - $number != 0 ) { $number++; }

        $returnPage .= "<span class='pagination'>
                            <span class='pagination-prevnext'>";
        // Если это первая страница - то выводим <span>
        if( $page == 1 ) {
            $returnPage .= "<span class='pagination-prev-inactive'>&nbsp;Предыдущая&nbsp;</span>";
            // Если это не первая страница - то выводим стрелку для одиночного
            // пролистывания
        } else {
            $returnPage .= "<a class='pagination-prev' href='"
                ."?cmd=Search&page=".($page-1)."&q=$q'>&nbsp;"
                ."Предыдущая&nbsp;</a>";
        }
        // Если это последняя страница, то выводим <span>
        if( $page == $number ) {
            $returnPage .= "<span class='pagination-next-inactive'>&nbsp;Следующая&nbsp;</span>";
            // Если это не последняя страница, то выводим стрелку для
            // единичного перелистывания
        } else {
            $returnPage .= "<a class='pagination-next' href='?cmd=Search&page="
                .($page+1)
                ."&q=$q'>&nbsp;"
                ."Следующая&nbsp;</a>";
        }

        $returnPage .= "</span>&nbsp;<span class='pagination-numbers'>";




        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Search&page=$i&q=$q'>&nbsp;$i&nbsp;</a>";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='?cmd=Search&page=$i&q=$q'>&nbsp;$i&nbsp;</a>";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "&nbsp;<span class='pagination-current'>$i</span>&nbsp;";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='?cmd=Search&page=$i&q=$q'>&nbsp;$i&nbsp;</a>";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
//            print $i;
//            print $page;
//            print $number;
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='?cmd=Search&page=$i&q=$q'>&nbsp;$i&nbsp;</a>";
            }
        }

        $returnPage .= "</span></span>";
        return $returnPage;
    }
}

?>