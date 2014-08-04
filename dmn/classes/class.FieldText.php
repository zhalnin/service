<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 20:25
 */

namespace dmn\classes;
// Error handling
error_reporting( E_ALL & ~E_NOTICE );
/**
 * Describe control element "text"
 * <input type="text">
 */
class FieldText extends Field {
    // Size of text field
    public $size;
    // Max length of field
    public $maxlength;

    // Construct of class
    public function __construct(  $name,
                                $caption,
                                $is_required = false,
                                $value = "",
                                $maxlength = 255,
                                $size = 41,
                                $parameters = "",
                                $help = "",
                                $help_url = "" ) {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
            "text",
            $caption,
            $is_required,
            $value,
            $parameters,
            $help,
            $help_url );
        // Initiate class member
        $this->size       = $size;
        $this->maxlength  = $maxlength;
    }

    /**
     * Function for name of field
     * and tag of control element
     * @return array
     */
    public function getHtml() {
        // Check if not empty style
        if( ! empty($this->css_style ) ) {
//            echo "<tt><pre>". print_r($this->css_style,    TRUE) . "</pre></tt>";
            $style = "style=\"".$this->css_style."\"";
        }
        else $style = "";
        // Check if not empty class
        if( ! empty( $this->css_class ) ) {
            $class = "class=\"".$this->css_class."\"";
        }
        else $class = "";
        // Check if not empty size
        if( ! empty( $this->size ) ) $size = "size=".$this->size;
        else $size = "";
        // Check if not empty maxlength
        if( ! empty( $this->maxlength ) ) {
            $maxlength = "maxlength=".$this->maxlength;
        }
        else $maxlength = "";
        // Form tag
        $tag = "<input $style $class
              type=\"".$this->type."\"
              name=\"".$this->name."\"
              value=\"".htmlspecialchars( $this->value, ENT_QUOTES )."\"
              $size $maxlength>\n";
        // Check if field is required
        if( $this->is_required ) $this->caption .= " *";
        // Form prompt
        $help = "";
        if( ! empty( $this->help ) ) {
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
        // If field is required
        if( $this->is_required )  {
            // Check if it empty
            if( empty($this->value ) ) {
                return "Поле \"".$this->caption."\" не заполнено!";
            }
        }
        return "";
    }
}
?>