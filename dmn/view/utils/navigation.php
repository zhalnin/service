<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 25/07/14
 * Time: 18:58
 */
namespace dmn\view\utils;
error_reporting(E_ALL & ~E_NOTICE);
require_once( "dmn/base/Registry.php" );


function navigation( $id_catalog, $link, $catalog ) {
//    echo "<tt><pre>".print_r($sth, true)."</pre></tt>";
    $pdo = \dmn\base\DBRegistry::getDB();
    $id_catalog = intval($id_catalog);
    $stmt = "SELECT * FROM system_catalog
                WHERE id_catalog = ?";
    $sth = $pdo->prepare( $stmt );
    $result = $sth->execute( array( $id_catalog ) );
    if(! $result ) {
        throw new \dmn\base\DBException(mysql_error(),
            $result,
            "Ошибка обращения к
            таблице каталога
            navigation()");
    }
    $raw = $sth->fetchAll();
    if( ! empty( $raw ) ) {
        $link = "<a class=menu
                    href=dmn.php?cmd=Catalog&id_parent=".$raw['id_catalog'].">
                    ".$raw['name']."</a>-&gt;".$link;
        $link = navigation($raw['id_parent'],
                        $link,
                        $catalog);
    }
    return $link;
}
?>