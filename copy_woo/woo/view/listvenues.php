<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 17:58
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();
$venues = $request->getObject( 'venues' );
//echo "<tt><pre> listvenues - ".print_r($venues, true)."</pre></tt>";
?>

<html>
<head>
    <title>Here are the venues</title>
</head>
<body>
<table>
    <tr>
        <td><?php print $request->getFeedbackString("</td></tr><tr><td>"); ?></td>
    </tr>
</table>
<?php
foreach ( $venues as $venue ) {
//    echo "<tt><pre> listvenue:venue - ".print_r($venue, true)."</pre></tt>";
    print "Этот Venue: {$venue->getName()}<br />\n";
//    echo "<tt><pre> listvenue:getSpaces() - ".print_r($venue->getSpaces(), true)."</pre></tt>";
    // В это месте мы используем итератор - woo/mapper/Collection
    // для вызова woo/mapper/DomainObjectFactory->createObject()
    foreach( $venue->getSpaces() as $space ) {
//        echo "<tt><pre> listvenue:space - ".print_r($space, true)."</pre></tt>";
        print "&nbsp; Содержит следующие Space: {$space->getName()}<br />\n";
        foreach ( $space->getEvents() as $event ) {
//            echo "<tt><pre> listvenue:event - ".print_r($event, true)."</pre></tt>";
            print "&nbsp;&nbsp; Который включает следующие Event: {$event->getName()}\n";
        }

    }
}

?>
</body>
</html>