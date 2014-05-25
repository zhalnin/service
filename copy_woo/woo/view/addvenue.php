<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/01/14
 * Time: 19:59
 * To change this template use File | Settings | File Templates.
 */

require_once( "woo/view/ViewHelper.php" );
$request = \woo\view\VH::getRequest();
?>

<html>
<head>
    <title>Add a Venue</title>
</head>
<body>
    <h1>Add a Venue</h1>
    <table>
        <tr>
            <td>
                <?php print $request->getFeedbackString("</td></tr><tr><td>"); ?>
            </td>
        </tr>
    </table>

    <form method="post">
        <input type="text" value="<?php echo $request->getProperty( 'space_name' ) ?>" name="venue_name" />
        <input type="submit" value="submit" />
    </form>
</body>
</html>