<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 12:27
 */

namespace dmn\view\utils;
 error_reporting(E_ALL & ~E_NOTICE);

  ////////////////////////////////////////////////////////////
  // Постраничная навигация
  ////////////////////////////////////////////////////////////
  // $page - текущая страница, передаётся через GET-параметр page
  // $total - общее число позиций в базе данных
  // $pnumber - число позиций на одной странице
  // $page_link - число ссылок слева и справа от выбранной страницы
  ///////////////////////////////////////////////////////////
function pager($page, $total, $pnumber, $page_link, $parameters){
    // Вычисляем число страниц в системе
    $number = (int)( $total/$pnumber );
    if( (float)( $total/$pnumber ) - $number != 0 ) $number++;



    // Проверяем есть ли ссылки слева
    if($page - $page_link > 1) {
        echo "<span class=main_txt><a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=1{$parameters}>[1-$pnumber]</a>&nbsp;&nbsp;...&nbsp;&nbsp;</span>";
        // Есть
        for( $i = $page - $page_link; $i < $page; $i++ ) {
            echo "<span class=main_txt>&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$i{$parameters}> [".
                ( ( $i - 1 )*$pnumber + 1 ). "-".$i*$pnumber."]</a>&nbsp;</span>";
        }
    } else {
        // Нет
        for($i = 1; $i < $page; $i++) {
            echo "<span class=main_txt>&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$i{$parameters}> [".
                ( ( $i - 1 )*$pnumber + 1 )."-".$i*$pnumber."]</a>&nbsp;></span>";
        }
    }



    // Проверяем есть ли ссылки справа
    if($page + $page_link < $number) {
        // Есть
        for( $i = $page; $i <= $page + $page_link; $i++ ) {
            if( $page == $i )
                echo "<span class=main_txt>&nbsp;[".(($i - 1)*$pnumber + 1)."-".
                    $i*$pnumber."]&nbsp;</span>";
            else
                echo "<span class=main_txt>&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$i{$parameters}>[".
                    ( ( $i - 1 )*$pnumber + 1 )."-".$i*$pnumber."]</a>&nbsp;</span>";
        }
        echo "<span class=main_txt>&nbsp;...&nbsp;&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$number{$parameters}> [".
            ( ( $number - 1 )*$pnumber + 1 )."-$total]</a>&nbsp;</span>";
    } else {
        // Нет
        for( $i = $page; $i <=$number; $i++ ) {
            if( $number == $i ) {
                if( $page == $i )
                    echo "<span class=main_txt>&nbsp;[".( ( $i - 1 ) * $pnumber + 1 ).
                        "-$total]&nbsp;</span>";
                else
                    echo "<span class=main_txt>&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$i{$parameters}>[".
                        ( ( $i - 1 ) * $pnumber + 1 )."-$total]</a>&nbsp;</span>";
            } else {
                if( $page == $i )
                    echo "<span class=main_txt>&nbsp;[".( ( $i - 1 ) * $pnumber + 1 )."-".
                        $i*$pnumber."]&nbsp;</span>";
                else
                    echo "<span class=main_txt>&nbsp;<a class=\"news_txt_lnk\" href=$_SERVER[PHP_SELF]?page=$i{$parameters}>[".
                        ( ( $i - 1 ) * $pnumber + 1 )."-".$i * $pnumber."]</a>&nbsp;</span>";
            }
        }
    }
}
?>