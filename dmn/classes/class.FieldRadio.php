<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 27/07/14
 * Time: 12:48
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );
/**
 * Describe control element "<input>"
 * <select> ... </select>
 * <options> ... </options>
 */
class FieldRadio extends Field {
    // Type of answers
    protected $radio;

    // Class constructor
    public function __construct($name,
                                $caption,
                                $radio = array(),
                                $value,
                                $parameters = "",
                                $help = "",
                                $help_url = "")
    {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
                            "radio",
                            $caption,
                            false,
                            $value,
                            $parameters,
                            $help,
                            $help_url );

        if( $this->radio != "radio_rate" ) $this->radio = $radio;
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

        $this->type = "radio";

        // Form tag
        $tag = "";
        if( ! empty( $this->radio ) ) {
            foreach( $this->radio as $key => $value ) {
//        echo "<tt><pre>". print_r($key ." - ".$value." - ".$this->value, TRUE) . "</pre></tt>";
                if( $key == trim( $this->value ) ) $checked = "checked";
                else $checked = "";
                if( strpos( $this->parameters, "horizontal" ) !== false ) {
                    $tag .= "<input $style $class
                    type=".$this->type."
                    name=".$this->name."
                    $checked value='$key'>$value";
                } else {
                    $tag[] = "<input $style $class
                      type=".$this->type."
                      name=".$this->name."
                      $checked value='$key'>$value\n";
                }
            }
        }
        // Form prompt
        $help = "";
        if( ! empty( $this->help ) ) {
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
        if( ! @in_array( $this->value, array_keys( $this->radio ) ) ) {
            if( empty( $this->value ) ) {
                return "Поле \"".$this->caption."\" содержит
                неодпустимое значение!";
            }
        }
        return "";
    }
}
?>