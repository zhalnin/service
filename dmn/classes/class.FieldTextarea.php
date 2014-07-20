<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 21:02
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

//require_once( "dmn/classes/class.Field.php" );
/**
 * Describe control element "textarea"
 * <textarea> ... </textarea>
 */
class FieldTextarea extends Field {
    // Size of text field
    protected $cols;
    // Max quantity for entering data
    protected $rows;
    // Block field
    protected $disabled;
    // Only for reading
    protected $readonly;
    // Prohibition of transfer line
    protected $wrap;
    protected $maxlength;

    // Class constructor
    public function __construct( $name,
                                $caption,
                                $is_required = false,
                                $value = "",
                                $cols = 35,
                                $rows = 7,
                                $disabled = false,
                                $readonly = false,
                                $wrap = false,
                                $parameters = "",
                                $help = "",
                                $help_url = "" ) {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
            "textarea",
            $caption,
            $is_required,
            $value,
            $parameters,
            $help,
            $help_url );

        // Initiate class member
        $this->cols      = $cols;
        $this->rows      = $rows;
        $this->disabled  = $disabled;
        $this->readonly  = $readonly;
        $this->wrap      = $wrap;
    }

    /**
     * Public html form to page.
     * @return array
     */
    public function getHtml() {
        // Check if not empty style
        if( ! empty( $this->css_style ) ) {
            $style = "style=\"".$this->css_style."\"";
        }
        $style = "";
        // Check if not empty class
        if( !empty( $this->css_class ) ) {
            $class = "class=\"".$this->css_class."\"";
        }
        $class = "";
        // Check if not empty cols
        if( !empty( $this->cols ) ) {
            $cols = "cols=\"".$this->cols."\"";
        } else {
            $cols = "";
        }

        // Check if not empty rows
        if( !empty( $this->rows ) ) {
            $rows = "rows=\"".$this->rows."\"";
        } else {
            $rows = "";
        }

        if( !empty( $this->maxlength ) ) {
            $maxlength = "maxlength=\"".$this->maxlength."\"";
        } else {
            $maxlength = "";
        }

        // Check if disabled is set
        if(  $this->disabled ) $disabled = "disabled";
        else $disabled = "";
        // Check if readonly is set
        if(  $this->readonly ) $readonly = "readonly";
        else $readonly = "";
        // Check if wrap is set
        if(  $this->wrap ) $wrap = "wrap";
        else $wrap = "";
        //////////////////////////////////////////////////////// VALUE
        // Check if $this->value is array
        if(  is_array( $this->value) )   {
            // Implode by transfering string
            $this->value = implode(  "\r\n", $this->value );
        }
        $output = $this->value;
        // Form tag
        $tag = "<textarea $style $class
              name=\"".$this->name."\"
              $rows $cols $disabled $readonly $wrap>".
            htmlspecialchars(  stripslashes( $output), ENT_QUOTES   )."</textarea>";
        // Check if field is required
        if(  $this->is_required ) $this->caption .= " *";
        // Form prompt
        $help = "";
        if( !empty( $this->help ) ) {
            $help .= "<span style='color: blue'>".
                nl2br(  $this->help )."</span>";
        }
        if( !empty( $help ) ) $help .= "<br/>";
        if( !empty( $this->help_url ) ) {
            $help .= "<span style='color: blue'>
                  <a href=".$this->help_url.">помощь</a>
                </span>";
        }
        // Return array for class Form
        return array(  $this->caption ,$tag, $help );
    }

    /**
     * Check correct of date
     * @return string
     */
    public function check()  {
        // If field is required
        if(  $this->is_required ) {
            // Check if it empty
            if( empty( $this->value ) ) {
                return "Поле \"".$this->caption."\" не заполнено";
            }
        }
        return "";
    }
}
?>
