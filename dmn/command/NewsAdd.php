<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 20:07
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

require_once( 'dmn/command/Command.php' );

class NewsAdd extends Command {

    function doExecute( \dmn\controller\Request $request ) {

        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
    }

} 