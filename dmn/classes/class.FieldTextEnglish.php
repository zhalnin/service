<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 12:34
 */
namespace dmn\classes;
// Error handling
error_reporting( E_ALL & ~E_NOTICE );

class FieldTextEnglish extends FieldText {

    /**
     * Check correct data
     * @return string
     */
    public function check() {
        // If field is required
        if( $this->is_required ) $pattern = "|^[a-z]+$|i";
        else $pattern = "|^[a-z]*$|i";

        // Check symbols in field "value"
        // for english alphabet only
        if(!preg_match( $pattern, $this->value ) )  {
            return "Поле \"{$this->caption}\"
          должно содержать только символы латинского алфавита.";
        }
        return "";
    }
}
?>