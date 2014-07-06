<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 15/06/14
 * Time: 17:02
 */

namespace imei_service\command;
ini_set('memory_limit', '-1');
error_reporting( E_ALL & ~E_NOTICE );

require_once( "imei_service/view/utils/getNameServer.php" );
require_once( "imei_service/view/utils/resizeImage.php" );

class Upload extends Command {

    function doExecute( \imei_service\controller\Request $request ) {

        $item = $request->getProperty( 'item' );

        switch( $item ) {
            case 'img':
                if( isset ( $_FILES['filename']['name'] ) ) {
                    if( $_FILES['filename']['size'] < ( 1024 * 1024 * 2 ) ) {
                        $width = 450;
                        $height = 600;
                        $dir = "imei_service/view/files/guestbook";
                        $path = \imei_service\view\utils\resizeImg( $_FILES['filename']['tmp_name'], $_FILES['filename']['name'], $width, $height, $dir );
                        $server_path = \imei_service\view\utils\getNameServer();
                        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadSuccess("'.$server_path.$path.'"); </script>';
                    } else {
                        $error = 'error';
                        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadSuccess("'.$error.'"); </script>';
        //        echo "Размер файла не должен превышать 2.0 Mb";
                    }
                }
            break;
            case 'imgLink':
                if( isset( $_POST['image'] ) ) {
                    $image =  $_POST['image'];
                    $path = \imei_service\view\utils\resizeimg2( $image, 450, 600 );
                    $arrExt = array('jpg','gif','png','jpeg');
//    ['jpg','gif','png','jpeg'];
                    if( preg_match('|^https?://|i', $image ) == 1 ) {
                        $info = pathinfo($image);
                        if( in_array( strtolower( $info['extension'] ),$arrExt ) ) {
                            $height = $path['height'];
                            $width = $path['width'];
                            echo "<script type='text/javascript'> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess('".$image."','".$width."','".$height."'); </script>";
                        } else {
                            $error = 'error';
                            echo '<script type="text/javascript"> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess("'.$error.'"); </script>';
                        }
                    } else {
                        $error = 'error';
                        echo '<script type="text/javascript"> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess("'.$error.'"); </script>';
                    }
                }
                break;
            case 'link':
                if( isset ( $_POST['url'] ) ) {
                    $path = htmlspecialchars( stripslashes( $_POST['url'] ) );
                    if( preg_match('|^https?://|', $path ) == 1 ) {
                        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadUrlSuccess("'.$path.'"); </script>';
                    } else {
                        $error = 'error';
                        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadUrlSuccess("'.$error.'"); </script>';

                    }
                }
                break;

        }

//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        $request->addFeedback( "Welcome to Upload" );
        return self::statuses( "CMD_OK" );
    }
}
?>