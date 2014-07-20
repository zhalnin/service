<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 20:14
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Root class for inheritance
 */
abstract class Field {
    // Name of control element
    protected $name;
    // Type of control element
    protected $type;
    // Title of control element on left
    protected $caption;
    // Value of control element
    protected $value;
    // Required control element for filling
    protected $is_required;
    // String for additional parameters
    protected $parameters;
    // Prompt
    protected $help;
    // Reference for prompt
    protected $help_url;

    // CSS class
    public $css_class;
    // CSS style
    public $css_style;



    /**
     * Construct of class.
     */
    public function __construct( $name,
                                $type,
                                $caption,
                                $is_required = false,
                                $value = "",
                                $parameters = "",
                                $help = "",
                                $help_url = "" ) {

        $this->name          = $this->encodestring( $name );
        $this->type          = $type;
        $this->caption       = $caption;
        $this->is_required   = $is_required;
        $this->value         = $value;
        $this->parameters    = $parameters;
        $this->help          = $help;
        $this->help_url      = $help_url;
    }


    /**
     * Check correct of typed info.
     * @return "" or error
     */
    abstract function check();

    /**
     * Public html form to page.
     * @return array
     */
    abstract function getHtml();


    /**
     * Admittance to protected and private control elements.
     * (only read)
     * @param $key
     * @return $key
     */
    public function __get( $key ) {
        if( property_exists( $this, $key ) ) {
            if(isset( $this->$key ) ) return $this->$key;
            else {
                throw new \dmn\base\AppException( $key,
                    "Instance ".__CLASS__."::$key does not exist!" );
            }
        } else {
            throw new \dmn\base\AppException( "Property ".__CLASS__."::$key did not found!" );
        }
    }

    /**
     * To translate russian text to translit
     * @param $st
     * @return $st
     */
    protected function mb_strtr($str, $from, $to) {
        return str_replace( $this->mb_str_split( $from ), $this->mb_str_split( $to ), $str );
    }
    protected function mb_str_split($str) {
        return preg_split( '~~u', $str, null, PREG_SPLIT_NO_EMPTY );
    }
    protected function encodestring( $st ) {
        // Replace single symbol.
        $st= $this->mb_strtr($st,"абвгдезийклмнопрстуфхъы",
            "abvgdezijklmnoprstufh#y");
        $st= $this->mb_strtr($st,"АБВГДEЗИЙКЛМНОПРСТУФХЪЫ",
            "ABVGDEZIJKLMNOPRSTUFH#Y");
        // Replace multiple symbol.
        $st=strtr( $st,
            array(
                "ж"=>"zh","ц"=>"ts","ч"=>"ch","ш"=>"sh",
                "щ"=>"shch","ь"=>"'","ю"=>"yu","я"=>"ya",
                "Ж"=>"ZH","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH",
                "Щ"=>"SHCH","Ь"=>"'","Ю"=>"YU","Я"=>"YA",
                "э"=>"je", "Э"=>"JE", "ё"=>"jo", "Ё"=>"JO"

            )
        );

        // Return result.
        return $st;

    }
}
?>