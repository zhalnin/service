<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/07/14
 * Time: 17:37
 */
namespace dmn\view\utils;
error_reporting(E_ALL & ~E_NOTICE);

/**
 * Form menu of administration.
 */

$config = "dmn/data/dmn_options.xml";

ensure( file_exists( $config ), "File doesn't exist" );

$options = @SimpleXML_load_file( $config );

ensure( $options instanceof \SimpleXMLElement, "Could not resolve xml file" );

foreach ( $options->menu->point as $point ):
    $block_name = $point->title;
    $block_description = $point->description;

    // Set another style for this point
    if(strpos($_SERVER['PHP_SELF'], $point['name']) !== false)
    {
        $style = 'class=\"active\"';
    }
    else $style = '';
    // Form point of menu
    echo "<div $style>
                  <a class=\"menu\"
                     href='?cmd=$point[name]'
                     title='$block_description'>
                     $block_name
                  </a>
                </div>";
endforeach;





//echo "<tt><pre>".print_r( $options->menu, true )."</pre><tt>";


function ensure( $stmt, $msg ) {
    if( ! $stmt ) {
        throw new \dmn\base\AppException( $msg );
    }
}
?>