<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 23:09
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/command/Command.php" );
require_once( "dmn/classes/class.PagerMysql.php" );

class ArtParagraph  extends Command {

    function doExecute( \dmn\controller\Request $request ) {



    }
}
?>