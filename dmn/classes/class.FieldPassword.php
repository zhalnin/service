<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 02/08/14
 * Time: 11:27
 */

namespace dmn\classes;
error_reporting(E_ALL & ~E_NOTICE);

require_once( 'dmn/classes/class.FieldText.php' );
///////////////////////////////////////
// Текстовое поле для пароля password
//////////////////////////////////////


class FieldPassword extends FieldText {
    /**
     * Construct of class.
     */
    function __construct( $name,
                         $caption,
                         $is_required = false,
                         $value = "",
                         $maxlength = 255,
                         $size = 41,
                         $parameters = "",
                         $help = "",
                         $help_url = "" ) {
        // Вызываем конструктор базового класса FieldText
        // для инициализации его данных
        parent::__construct( $name,
            $caption,
            $is_required,
            $value,
            $maxlength,
            $size,
            $parameters,
            $help,
            $help_url );
        // Класс FieldText присваивает члену type
        // значение text, для пароля этому члену
        // следует присвоить значение password
        $this->type = "password";
    }
}
?>