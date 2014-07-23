<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 23/07/14
 * Time: 16:06
 */

namespace dmn\command;
error_reporting( E_ALL & ~E_NOTICE );

// Подключаем родительский класс
require_once( 'dmn/command/Command.php' );

class NewsDelete extends Command {

    function doExecute( \dmn\controller\Request $request ) {
        $id = $request->getProperty( 'idn' );
        if( $id ) { // если передан id_news
            $news = \dmn\domain\News::find( $id ); // находим элементы по заданному id_news

            $path = str_replace( "//", "/","imei_service/view/".$news->getUrlpict_s() ); // путь до большого изображения
            $path_small = str_replace( "//", "/","imei_service/view/".$news->getUrlpict() ); // путь до малого изображения
            if( file_exists( $path ) ) { // если большое изображение существует
//                @unlink( $path ); // удаляем
                print "Del big";
            }
            if( file_exists( $path_small ) ) { // если малое изображение существует
                print "Del small";
//                @unlink( $path_small ); // удаляем
            }

//            Здесь удаление блока новостей и передаресация



        }
    }
} 