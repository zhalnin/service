<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 22:02
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Describe control element "<input>"
 * type="checkbox"
 */
class FieldCheckbox extends Field {
    // Class constructor
    public function __construct( $name,
                                $caption,
                                $value = "",
                                $parameters = "",
                                $help = "",
                                $help_url = "" ) {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
            "checkbox",
            $caption,
            false,
            $value,
            $parameters,
            $help,
            $help_url );
        if( $value == "on" ) $this->value = true;
        else if( $value === true ) $this->value = true;
        else $this->value = false;
    }

    /**
     * Function for name of field
     * and tag of control element
     * @return array
     */
    public function getHtml() {
        // Check if not empty style
        if(!empty( $this->css_style ) ) {
            $style = "style=\"".$this->css_style."\"";
        }
        else $style = "";
        // Check if not empty class
        if(  !empty( $this->css_class ) ) {
            $class = "class=\"".$this->css_class."\"";
        }
        else $class = "";

        if( $this->value ) $checked = "checked";
        else $checked = "";

        // Form tag
        $tag = "<input $style $class
              type=\"".$this->type."\"
              name=\"".$this->name."\"
              $checked>\n";
        // Form prompt
        $help = "";
        if( !empty( $this->help ) ) {
            $help .= "<span style='color:blue'>".
                nl2br($this->help)."</span>";
        }
        if( ! empty( $help ) ) $help .= "<br/>";
        if( ! empty( $this->help_url ) ) {
            $help .= "<span style='color: blue'><a href='".
                $this->help_url.">помощь</a></span>";
        }
        // Return array for class Form
        return array( $this->caption, $tag, $help );
    }

    /**
     * Check correct of date
     * @return string
     */
    public function check() {
        return "";
    }
}
?>
