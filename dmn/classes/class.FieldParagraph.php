<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.01.12
 * Time: 23:19
 * To change this template use File | Settings | File Templates.
 */

namespace dmn\classes;
error_reporting(E_ALL & ~E_NOTICE);
/////////////////////////////////////////
// Параграф (текст)
////////////////////////////////////////
class FieldParagraph extends Field{
    // Конструктор класса
    function __construct( $value = "",
                         $parameters = "" ) {
        // Вызываем конструктор базового класса field
        // для инициализации его данных
        parent::__construct( "",
                            "paragraph",
                            "",
                            false,
                            $value,
                            $parameters,
                            "",
                            "" );
    }

    // Метод для возврата имени поля
    // и самого тега элемента управления
    function getHtml() {
        // Формируем тег
        $tag = htmlspecialchars( $this->value, ENT_QUOTES );
        $pattern = "#\[b\](.+)\[\/b\]#isU";
        $tag = preg_replace( $pattern,'<b>\\1</b>',$tag );
        $pattern = "#\[i\](.+)\[\/i\]#isU";
        $tag = preg_replace( $pattern,'<i>\\1</i>',$tag );
        $pattern = "#\[url\][\s]*((?=http:)[\S]*)[\s]*\[\/url\]#si";
        $tag = preg_replace( $pattern,'<a href="\\1" target=_blank>\\1</a>',$tag );
        $pattern = "#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU";
        $tag = preg_replace( $pattern,'<a href="\\1" target="_blank">\\2</a>', $tag );
        $tag = stripslashes( $tag );
        return array( $this->caption, nl2br( $tag ) );
    }

    // Метод, проверяющий корректность переданных данных
    function check()
    {
        return "";
    }
}
?>
