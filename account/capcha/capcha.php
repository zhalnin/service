<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/12/13
 * Time: 17:45
 * To change this template use File | Settings | File Templates.
 */

require_once('../base/Registry.php');



/**
 * Create seed
 * @return float
 */
function make_seed() {
    // explode microtime to 2 value
    list($usec, $sec) = explode(" ",microtime());
    return (float)$usec * 100000 + (float)$sec;
}


/**
 * Generate random symbols
 * @return string
 */
function gen_code() {
    // hours
    $hours = date( "H" );
    // from minutes last digit
    $minutes = substr( date( "i" ), 1, 1 );
    // month
    $month = date( "m" );
    // year
    $year = date( "Y" );
    // day of the year
    $day_year = date( "z" );
    // concatenate string
    $str = $hours.$minutes.$month.$year.$day_year;
    // double md5()
    $str = md5(md5( $str ) );
    // reverse string
    $str = strrev( $str );
    // cut from string 6 symbols from 3-rd position
    $str = substr( $str, 3, 6 );
    // split string by empty regular expression; find only no empty values
    $arr_mix = preg_split('//',$str,-1,PREG_SPLIT_NO_EMPTY);
    // change generator of random symbols
    srand(make_seed());
    // shuffle array
    shuffle($arr_mix);
    // return string from array
    return implode("",$arr_mix);
}



function gen_img(){
    header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
    header( "Last-Modified: ".gmdate("D, d M Y H:i:s", 10000) ." GMT" );
    header( "Cache-Control: no-store, no-cache, must-revalidate" );
    header( "Cache-Control: post-check=0, pre-check=0", false );
    header( "Pragma: no-cache" );
    header( "Content-type: image/png" );


    $code = gen_code();
    $linenum = 5;
    $img_arr = array( 'codegen.png','codegen0.png' );
    $img_rnd = rand( 0, sizeof($img_arr) - 1 );
    $font_arr = array();
    $font_arr[0]["fname"] = "verdana.ttf";
    $font_arr[0]["size"] = 16;
    $font_arr[1]["fname"] = "times.ttf";
    $font_arr[1]["size"] = 30;
    $im = imagecreatefrompng( "src/".$img_arr[$img_rnd] );
    $rnd_arr = rand( 0, sizeof( $font_arr) - 1);


    for( $i=0; $i<$linenum; $i++ ) {
        // size 150x50
        $color = imagecolorallocate( $im,rand( 0,150 ), rand( 0, 100 ), rand( 0, 150 ) );
        imageline( $im, rand( 0, 20 ), rand( 1, 50 ), rand( 150, 180 ), rand( 1, 50 ), $color );

    }

    $color = imagecolorallocate($im, rand( 0, 200 ), rand( 0, 100 ), rand( 0 , 200 ) );
    imagettftext( $im, $font_arr[$rnd_arr]['size'], rand( -4, 4 ), rand( 10, 45 ), rand( 20, 35 ),
        $color, "src/".$font_arr[$rnd_arr]["fname"] , $code);



    for( $i=0; $i<$linenum; $i++ ) {
        // size 150x50
        $color = imagecolorallocate( $im,rand( 0,150 ), rand( 0, 100 ), rand( 0, 150 ) );
        imageline( $im, rand( 0, 20 ), rand( 1, 50 ), rand( 150, 180 ), rand( 1, 50 ), $color );

    }

    \account\base\SessionRegistry::setSession('code', $code);
    ImagePNG( $im );
    ImageDestroy( $im );
}

gen_img();

?>