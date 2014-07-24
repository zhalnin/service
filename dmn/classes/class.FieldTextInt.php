<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 24/07/14
 * Time: 11:33
 */

namespace dmn\classes;
// Error handling
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Describe control element with only integer
 */
class FieldTextInt extends FieldText {
    // Min value of field
    protected $min_value;
    // Max value of field
    protected $max_value;

    // Construct of class
    public function __construct($name,
                                $caption,
                                $is_required = false,
                                $value = "",
                                $min_value = 0,
                                $max_value = 0,
                                $maxlength = 255,
                                $size = 41,
                                $parameters = "",
                                $help = "",
                                $help_url = "")
    {
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct($name,
                            $caption,
                            $is_required,
                            $value,
                            $maxlength,
                            $size,
                            $parameters,
                            $help,
                            $help_url);
        // Initiate class member
        $this->min_value = intval($min_value);
        $this->max_value = intval($max_value);

        // Min value should be more than max value
        if( $this->min_value > $this->max_value ) {
            throw new \dmn\base\AppException("Минимальное значение должно
                      быть больше максимального
                      значения. Поле \"{$this->caption}\".");
        }
    }

    /**
     * Check correct of date
     * @return string
     */
    public function check() {
        $pattern = "|^[-\d]*$|i";
        if( $this->is_required ) {
            if($this->min_value != $this->max_value) {
                if($this->value < $this->min_value ||
                    $this->value > $this->max_value) {
                    return "Поле \"".$this->caption."\"
                  должно быть больше ".$this->min_value."
                  и меньше ".$this->max_value."";
                }
            }
            $pattern = "|^[-\d]+$|i";
        }
        if( ! preg_match( $pattern, $this->value ) ) {
            return "Поле \"".$this->caption."\"
              должно содержать только цифры";
        }
        return "";
    }
}
?>