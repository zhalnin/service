<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/01/14
 * Time: 16:35
 * To change this template use File | Settings | File Templates.
 */

namespace woo\command;

//require_once( "woo/mapper/VenueMapper.php" );
//require_once( "woo/command/Venue.php" );
require_once( "woo/domain/Venue.php" );

class AddSpace extends Command {
    function doExecute( \woo\controller\Request $request ) {
        // Получаем объект "venue" из сохраненного ранее в woo\command\AddVenue
        $venue = $request->getObject("venue");
//        echo "<tt><pre>".print_r($venue, true)."</pre></tt>";
//        echo "<tt><pre>".print_r($request, true)."</pre></tt>";
        // Если нет сохраненного ранее объекта woo\domain\Venue, т.е. вызвана
        // сразу команда cmd=AddSpace
        if( ! isset( $venue ) ) {
//            echo "<tt><pre>".print_r($request->getProperty('venue_id'), true)."</pre></tt>";
            // 'venue_id' - это поле hidden в форме addspace.php $venue->getId()
            $venue = \woo\domain\Venue::find($request->getProperty( 'venue_id' ) );
//            echo "<tt><pre>".print_r($venue, true)."</pre></tt>";
        }
        // Если вообще нет объекта woo\domain\Venue
        if( is_null( $venue ) ) {
            // Добавляем сообщение в массив addFeedback
            $request->addFeedback( "unable to find venue" );
            // Возвращаем код ошибки 'CMD_ERROR' - 2
            return self::statuses('CMD_ERROR');
        }
        $request->setObject( "venue", $venue );

        // Получаем имя Space
        $name = $request->getProperty( "space_name" );

//        echo "<tt><pre>".print_r($request->getProperty("space_name"), true)."</pre></tt>";

        if( ! isset( $name ) ) {
//        if( empty( $name ) ) {
            $request->addFeedback( "please add name for space" );
            return self::statuses('CMD_INSUFFICIENT_DATA' );
        } else {
            // Если передан параметр space_name, создаем экземпляр класса woo/domain/Space
            // и передаем его как параметр в метод addSpace
            $venue->addSpace( $space = new \woo\domain\Space(null, $name ) );
//            echo "<tt><pre>".print_r($venue, true)."</pre></tt>";
            $request->addFeedback( "space '$name' added ({$space->getId()})" );
            return self::statuses('CMD_OK');
        }
    }
}
?>