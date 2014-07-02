<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/06/14
 * Time: 12:36
 */


function db_connect() {
    $connection = new PDO( 'mysql:host=localhost;dbname=gamelist','root','zhalnin5334',
                            array( PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                   PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8' ) );
    if( !$connection ) {
        throw new PDOException( 'Error in db_connect' );
    }

    return $connection;
}

function db_result_to_array( $result ) {
    $res_array = array();
    for( $count=0; $row = $result->fetch(); $count++ ) {
        $res_array[$count] = $row;
    }
    return $res_array;
}

function findProducts() {
    $db = db_connect();
    $query = "SELECT * FROM products ORDER BY products.id DESC";
    $sth = $db->prepare( $query );
    $sth->execute();
    $result = db_result_to_array( $sth );
    return $result;
}

function findProduct( $id ) {
    $db = db_connect();
    $query = "SELECT * FROM products WHERE id = ?";
    $sth = $db->prepare( $query );
    $sth->execute( array( $id ) );
    $result = $sth->fetch();
//    echo "<tt><pre>".print_r($result, true)."</pre></tt>";
    return $result;
}






try {
//    $row = findProducts();
//    $row = findProduct(1);
//        echo "<tt><pre>".print_r($row, true)."</pre></tt>";

} catch ( PDOException $ex ) {
    print $ex->getMessage();
} catch ( Exception $ex ) {
    print $ex->getMessage();
}
