<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 22:22
 */

namespace dmn\classes;
// Error handling
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Describe control element "hidden" for value integer
 * <input type="hidden">
 */
class FieldHiddenInt extends FieldHidden {
    /**
     * Check correct of date
     * @return string
     */
    function check() {
        $pattern = "|^[\d]+$|";
        // If field is required
        if( $this->is_required ) {
            // Check if it is integer
            if( ! preg_match( $pattern, $this->value ) ) {
                return "Скрытое поле должно быть целым числом!";
            }
        }
        // If field is not required
        $pattern = "|^[\d]*$|";
        if( ! preg_match( $pattern, $this->value ) ) {
            return "Скрытое поле должно быть целым числом!";
        }
        return "";
    }
}
?>