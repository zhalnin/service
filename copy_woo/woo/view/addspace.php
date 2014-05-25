<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 16:30
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();
$venue = $request->getObject('venue');

?>

<html>
<head>
    <title>Add a Space for venue '<?php print $venue->getName() ?>'</title>
</head>
<body>
    <h1>Add a Space for venue '<?php print $venue->getName() ?>'</h1>
    <table>
        <tr>
            <td>
                <?php print $request->getFeedbackString("</td></tr><tr><td>"); ?>
            </td>
        </tr>
    </table>
    [add space]
    <form method="post">
        <input type="text" value="<?php echo $request->getProperty( 'space_name' ) ?>" name="space_name" />
        <input type="hidden" name="cmd" value="AddSpace" />
        <input type="hidden" name="venue_id" value="<?php echo $venue->getId() ?>" />
        <input type="submit" value="submit" />
    </form>
</body>
</html>