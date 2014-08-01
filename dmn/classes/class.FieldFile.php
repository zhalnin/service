<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 22:10
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );

/**
 * Describe control element "file"
 * From $_files['filename']['name']
 */
class FieldFile extends Field {
    // Destination directory
    protected $dir;
    // Prefix in front of filename
    protected $prefix;

    protected $time;

    // Class constructor
    public function __construct( $name,
                                $caption,
                                $is_required,
                                $value,
                                $dir,
                                $prefix = "",
                                $help = "",
                                $help_url = "" ) {
//      echo "<tt><pre>".print_r($value, true)."</pre></tt>";
        // Invoke construct of parent class Field
        // for initiation of params
        parent::__construct( $name,
            "file",
            $caption,
            $is_required,
            $value,
            "",
            $help,
            $help_url );

        $this->dir      = $dir;
        $this->prefix   = $prefix;
        $this->time     = time();

        if( ! empty( $this->value ) ) {
            // If file is PHP's script
            // or Perl or HTML-page
            // transform it into format .txt
            $extentions = array("#\.php#is",
                "#\.phtml#is",
                "#\.php3#is",
                "#\.html#is",
                "#\.htm#is",
                "#\.hta#is",
                "#\.pl#is",
                "#\.xml#is",
                "#\.inc#is",
                "#\.shtml#is",
                "#\.xht#is",
                "#\.xhtml#is");
            $file_name = preg_replace( "|[ ']|","", $this->value[$this->name]['name'] );
            // Change russian symbol for translit
            $file_name = $this->encodestring( $file_name );
//      $tm = preg_replace( "|\s*|","_", $this->value[$this->name]['name']);
            // Insert extension from file's name
            $path_parts = pathinfo( $file_name );
//        $path_parts = pathinfo($tm);
            $ext = ".".$path_parts['extension'];
//            $path = basename( $file_name, $ext );
//            $path = preg_replace( "| |","", $path);
            $add = $ext;

            foreach( $extentions as $exten ) {
                if( preg_match( $exten, $ext ) ) $add = ".txt";
            }
            $path = str_replace( "//","/",$dir."/".$prefix.$this->time.$add );
//            echo "<tt><pre>". print_r($path, TRUE) . "</pre></tt>";
            // Transfer file from temp directory of sever
            // to directory /files of Web-application
//      if(copy($this->value[$this->name]['tmp_name'], $path))
//      {
//        // Destroy file in temp directory
//        @unlink($this->value[$this->name]['tmp_name']);
//        // Change the access rights
//        @chmod($path, 0644);
//      }

            // Altrenative kind to save uploaded file
            if( move_uploaded_file( $this->value[$this->name]['tmp_name'], $path ) ) {
                @unlink( $this->value[$this->name]['tmp_name'] );
                @chmod( $path, 0644 );
            }
        }
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

        // Form tag
        $tag = "<input $style $class
                    type=\"".$this->type."\"
                    name=\"".$this->name."\">\n";

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
        if( $this->is_required ) {
            if( empty( $this->value[$this->name] ) ) {
                return "Поле \"".$this->caption."\" не заполнено";
            }
        }
        return "";
    }

    /**
     * Return recoded name of file
     * @return void
     */
    public function getFilename() {
        if( ! empty( $this->value ) ) {
            if( ! empty( $this->value[$this->name]['name'] ) ) {
                preg_match("|\.[a-zA-Z]{2,6}$|",  $this->value[$this->name]['name'], $ext );
                $len = strlen( $ext[0] );
//                $tmp_name = preg_replace( "|[ '_-]|","", $this->value[$this->name]['name'] );
                $tmp_name = substr( $this->value[$this->name]['name'], -$len );
//        echo "<tt><pre>". print_r( $ext[0], TRUE) . "</pre></tt>";
                return $this->encodestring( $this->prefix.$this->time.$tmp_name);
            } else return "";
        } else return "";
    }
}
?>
