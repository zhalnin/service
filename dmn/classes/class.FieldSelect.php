<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 12:35
 */

namespace dmn\classes;
// Error handling
error_reporting(E_ALL & ~E_NOTICE);

class FieldSelect extends Field {

    // Array with value of <options> into <select>
    protected $options;
    // Boolean to admit selection of several point in <select>
    protected $multi;
    // Height of multiple select
    protected $select_size;

    // Class constructor
    public function __construct( $name,
                                $caption,
                                $options = array(),
                                $value,
                                $multi = false,
                                $select_size = 4,
                                $parameters = "" )
    {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
                            "select",
                            $caption,
                            false,
                            $value,
                            $parameters );

        $this->options     = $options;
        $this->multi       = $multi;
        $this->select_size = $select_size;
    }

    /**
     * Function for name of field
     * and tag of control element
     * @return array
     */
    public function getHtml() {
        // Check if not empty style
        if( ! empty( $this->css_style ) ) {
            $style = "style=\"".$this->css_style."\"";
        } else $style = "";
        // Check if not empty class
        if( ! empty( $this->css_class ) ) {
            $class = "class=\"".$this->css_class."\"";
        } else $class = "";
        // If multiple select and height of this select is set
        if( $this->multi && $this->select_size ) {
            $multi = "multiple size=".$this->select_size;
            $this->name = $this->name."[]";
        } else $multi = "";

        // Form tag
        $tag = "<select $style $class name=\"".$this->name."\" $multi>\n";
        if( ! empty( $this->options ) ) {
            foreach( $this->options as $key => $value ) {
//echo "<tt><pre>". print_r($key ." - ".$value." - ".$this->value, TRUE) . "</pre></tt>";
                if( is_array( $this->value ) ) {
                    if( in_array( $key, $this->value ) ) $selected = "selected";
                    else $selected = "";
                }
                else if( $key == trim( $this->value ) ) $selected = "selected";
                else $selected = "";
                $tag .= "<option value=\"".htmlspecialchars( $key, ENT_QUOTES )."\" $selected>".
                    htmlspecialchars( $value, ENT_QUOTES )."</option>\n";

            }
        }
        $tag .= "</select>\n";

        // Form prompt
        $help = "";
        if( ! empty( $this->help ) )  {
            $help .= "<span style='color:blue'>".
                nl2br( $this->help )."</span>";
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
        if( ! in_array( $this->value, array_keys( $this->options ) ) ) {
            if( empty( $this->value ) ) {
                return "Поле \"".$this->caption."\"
                содержит недопустимое значение.";
            }
        }
        return "";
    }

    /**
     * It is for selected element
     * @return
     */
    public function selected() {
        return $this->value[0];
    }

    /**
     * For save into DB array with multiple data
     * @return serialize string
     *
     * For taking result back you should to unserialize result
     */
    public function getSelectValue() {
        if( is_array( $this->value ) ) {
            for( $i = 0; $i < count( $this->value ); $i++ ) {
                $arr[] = $this->options[$this->value[$i]];

            }
            return serialize( $arr );
        }
    }
}
?>