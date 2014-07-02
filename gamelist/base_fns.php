<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 01/07/14
 * Time: 13:46
 */

function db_connect() {
    $connection = new PDO('mysql:host=localhost;dbname=gamelist','root','zhalnin5334',
                            array( PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                   PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8' ) );
    if( ! $connection ) {
        throw new PDOException( 'Error has occured in base_fns.php' );
    }
    return $connection;
}

function getStatement( $stmt ) {
    $pdo = db_connect();
    return $pdo->prepare( $stmt );
}

function resultToArray( $enter ) {
    $result = array();
    $count = 0;
    foreach ( $enter as $en => $e ) {
//        echo "<tt><pre>".print_r( $e, true )."</pre></tt>";
        $result[$count] = $e;
        $count++;
    }
    return $result;
}

function findProducts() {
    $sth = getStatement( 'SELECT * FROM products ORDER BY products.id DESC' );
    $result = $sth->execute();
    if( ! $result ) {
        throw new PDOException( 'Error has occured in findProducts()' );
    }
    return $sth->fetchAll();
}

function findProduct( $id ) {
    $sth = getStatement( 'SELECT * FROM products WHERE products.id = ?' );
    $result = $sth->execute( array( $id ) );
    if( ! $result ) {
        throw new PDOException( 'Error has occured in findProduct() ' );
    }
    return $sth->fetchAll();
}






try {
//    $stmt = 'SELECT * FROM products';
//    $sth = getStatement( $stmt );
//    $sth->execute();
//    $result = $sth->fetchAll();
//    echo "<tt><pre>".print_r( findProduct( 1 ), true )."</pre></tt>";

} catch ( PDOException $ex ) {
    print $ex->getMessage();
}
