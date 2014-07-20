<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 22:19
 */

namespace dmn\classes;
// Error handling
error_reporting(E_ALL & ~E_NOTICE);

/**
 * Describe control element "hidden"
 * <input type="hidden">
 */
class FieldHidden extends Field {
    /**
     * Construct of class.
     */
    public function __construct( $name,
                                $is_required = false,
                                $value = "" ) {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
                            "hidden",
                            "-",
                            $is_required,
                            $value,
                            $parameters,
                            "",
                            "" );
    }

    /**
     * Function for name of field
     * and tag of control element
     * @return array
     */
    public function getHtml() {
        // Form tag
        $tag = "<input type=\"".$this->type."\"
                    name=\"".$this->name."\"
                    value=\"".
            htmlspecialchars( $this->value, ENT_QUOTES )."\">\n";
        // Return array for class Form
        return array( "",$tag );
    }

    /**
     * Check correct of date
     * @return string
     */
    function check() {
        // If field is required
        if( $this->is_required ) {
            // Check if it empty
            if( empty($this->value )  ) return "Скрытое поле не заполнено!";
        }
        return "";
    }
}
?>