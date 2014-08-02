<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.01.12
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
namespace dmn\classes;

////////////////////////////////////////
// Заголовок (текст)
////////////////////////////////////////

class FieldTitle extends Field{
    // Размер заголовка 1,2,3,4,5,6 для
    // h1, h2, h3, h4, h5, h6, соответственно
    protected $h_type;
    // Конструктор класса
    function __construct( $value = "",
                         $h_type = 3,
                         $parameters = "" ) {

        // Вызываем конструктор базового класса field
        // для инициализации его данных

        parent::__construct( "",
            "title",
            "",
            false,
            $value,
            $parameters,
            "",
            "" );
        if($h_type > 0 && $h_type < 7) $this->h_type = $h_type;
        // По умолчанию присваивается значение 3
        else $this->h_type = 3;
    }


    // Метод для возврата имени поля
    // и самого тега элемента управления
    function getHtml() {

        // Формируе тег
        $tag = htmlspecialchars( $this->value, ENT_QUOTES );
        $pattern = "#\[b\](.+)\[\/b\]#isU";
        $tag = preg_replace( $pattern, '<b>\\1</b>',$tag );
        $pattern = "#\[i\](.+)\[\/i\]#isU";
        $tag = preg_replace( $pattern,'<i>\\1</i>',$tag );
        $pattern = "#\[url\][\s]*((?=http:)[\S]*)[\s]*\[\/url\]#si";
        $tag = preg_replace( $pattern,
            '<a href="\\1" target=_blank>\\1</a>',
            $tag );
        $pattern = "#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[\/url\]#isU";
        $tag = preg_replace( $pattern,
            '<a href="\\1" target=_blank>\\2</a>',
            $tag );
        $tag = stripslashes( $tag );
        $tag = "<h".$this->h_type.">".$this->value."</h".$this->h_type.">";
        return array( $this->caption, $tag );
    }

    // Метод, проверяющий корректность переданных данных
    function check() {
        return "";
    }

}

?>