<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 01/04/14
 * Time: 10:01
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\classes;

require_once("imei_service/base/Registry.php" );

abstract class Pager {

    protected function __construct(){}

    /**
     * Общее кол-во записей
     * @return mixed
     */
    abstract protected function getTotal();

    /**
     * Количество позиций на странице
     * @return mixed
     */
    abstract protected function getPageNumber();

    /**
     * Количество ссылок слева и справа от текущей страницы
     * @return mixed
     */
    abstract protected function getPageLink();

    /**
     * Дополнительные параметры, которые необходимо передать по ссылке
     * @return mixed
     */
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
//        $returnPage .= "<a href='$_SERVER[PHP_SELF]".
//                        "?page=1{$this->getParameters()}'>".
//                        "&lt;&lt;</a> ... ";

        // Если это не первая страница - то выводим стрелку для одиночного
        // пролистывания
        if( $page != 1 ) {
            // Двойная стрелка для перелистывания в начало
            $returnPage .= "<a href='$_SERVER[PHP_SELF]".
                "?cmd=Guestbook&page=1{$this->getParameters()}'>".
                "&lt;&lt;</a> ... ";

            $returnPage .= " <a href='$_SERVER[PHP_SELF]"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>"
                ."&lt;</a> ... ";
        }

        // Если текущая страница больше, чем желаемое количество + 1 ( 4 ), то
        // указываем ссылки на предыдущие страницы, пример:
        // страница 5 > желаемого количества отображаемых ссылок плюс 1 4
        // в цикле проходим 5-3(2) < 5 --> выводим ссылки на страницы 2, 3, 4
        if( $page > $this->getPageLink() + 1 ) {
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "$i ";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>$i</a> ";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>$i</a> ";
            }
        }

        // Если это не последняя страница, то выводим стрелку для
        // единичного перелистывания
        if( $page != $number ) {
            $returnPage .= " ... <a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page="
                .($page+1)
                ."{$this->getParameters()}'>"
                ."&gt;</a>";
            // Двойная стрелка для перелистывания в конец
            $returnPage .= " ... <a href='$_SERVER[PHP_SELF]"
                ."?cmd=Guestbook&page=$number{$this->getParameters()}'>"
                ."&gt;&gt;</a>";
        }

//        // Двойная стрелка для перелистывания в конец
//        $returnPage .= " ... <a href='$_SERVER[PHP_SELF]"
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
            $returnPage .= "<a class='pagination-prev' href='$_SERVER[PHP_SELF]"
                ."?cmd=Guestbook&page=".($page-1)."{$this->getParameters()}'>&nbsp;"
                ."Предыдущая&nbsp;</a>";
        }
        // Если это последняя страница, то выводим <span>
        if( $page == $number ) {
            $returnPage .= "<span class='pagination-next-inactive'>&nbsp;Следующая&nbsp;</span>";
            // Если это не последняя страница, то выводим стрелку для
            // единичного перелистывания
        } else {
            $returnPage .= "<a class='pagination-next' href='$_SERVER[PHP_SELF]?cmd=Guestbook&page="
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
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если меньше ( 4 ), то от 1 до 3-х - указываем ссылки на страницы 1, 2, 3
            // если page меньше 4-х, то и выводим меньше
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
        }

        // Указываем текущую страницу
        $returnPage .= "&nbsp;<span class='pagination-current'>$i</span>&nbsp;";

        // Если страница 1-я, то указываем ссылки на страницы справа - 2, 3, 4
        if( $page + $this->getPageLink() < $number ) {
            for( $i = $page + 1; $i <= $page + $this->getPageLink(); $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
            }
            // Если уже 2-я страница и более, то указываем сслылки на страницы 3, 4, 5
        } else {
            for( $i = $page + 1; $i <= $number; $i++ ) {
                $returnPage .= "<a href='$_SERVER[PHP_SELF]?cmd=Guestbook&page=$i'>&nbsp;$i&nbsp;</a>";
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
            $returnPage .= "<a href='$_SERVER[PHP_SELF]"
                ."?cmd=Guestbook&page=1{$this->getParameters()}'>"
                ."[1-{$this->getPageNumber()}]"
                ."</a>&nbsp;&nbsp; ... &nbsp;&nbsp;";
            // Выводим три ссылки на следующие страницы
            for( $i = $page - $this->getPageLink(); $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='$_SERVER[PHP_SELF]"
                    ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                    ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                    ."-".$i * $this->getPageNumber()
                    ."]</a>&nbsp;";
            }
            // Если страница 4 и до 2-х
        } else {
            for( $i = 1; $i < $page; $i++ ) {
                $returnPage .= "&nbsp;<a href='$_SERVER[PHP_SELF]"
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
                    $returnPage .= "&nbsp;<a href='$_SERVER[PHP_SELF]"
                        ."?cmd=Guestbook&page=$i{$this->getParameters()}'>"
                        ."[".( ( $i - 1 ) * $this->getPageNumber() + 1 )
                        ."-". $i * $this->getPageNumber()
                        ."]</a>&nbsp;";
                }
            }
            // Выводим ссылку на последнюю страницу
            $returnPage .= "&nbsp; ... &nbsp;&nbsp;"
                ."<a href='$_SERVER[PHP_SELF]"
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
                        $returnPage .= "&nbsp;<a href='$_SERVER[PHP_SELF]"
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
                        $returnPage .= "&nbsp;<a href='$_SERVER[PHP_SELF]"
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


class PagerMysql extends Pager {
    protected static $db;
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
        if( ! isset( self::$db ) ) {
            self::$db = \imei_service\base\DBRegistry::getDB();
        }
    }

    public function getTotal() {
        $selectCount = "SELECT COUNT(*) FROM {$this->tableName}
                        {$this->where}
                        {$this->order}";

        $sth = self::$db->prepare( $selectCount );
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
        $selectStmt = "SELECT * FROM {$this->tableName}
                        {$this->where}
                        {$this->order}
                        LIMIT :start, :end";
//        echo "<tt><pre>".print_r( $selectStmt, true )."</pre></tt>";
        $sth = self::$db->prepare( $selectStmt );
        $sth->bindValue(':start', intval($first), \PDO::PARAM_INT );
        $sth->bindValue(':end', intval($this->getPageNumber()), \PDO::PARAM_INT );
        $result = $sth->execute();
        if( ! $result ) {
            throw new \PDOException( "Ошибка при выборке в getPage()" );
        }
        while( $arr[] = $sth->fetch() );
        unset( $arr[count($arr) - 1]);
        $sth->closeCursor();
//        echo "<tt><pre>".print_r( $arr, true )."</pre></tt>";
        return $arr;
    }
}
?>
