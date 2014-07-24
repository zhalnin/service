<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 11:06
 */

namespace dmn\classes;
// Error handling
error_reporting( E_ALL & ~E_NOTICE );

class FieldTextEmail extends FieldText {

    /**
     * Check correct of email
     * @return string
     */
    public function check() {
        // If field is required
        if($this->is_required || !empty($this->value))  {
            $pattern = "|^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,6}$|i";
            // Check if it empty
            if(!preg_match( $pattern, $this->value ) )  {
                return "Введите email в виде <i>mysite@mydomain.ru</i>";
            }
        }
        return "";
    }
}
?>