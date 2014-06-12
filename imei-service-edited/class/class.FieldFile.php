<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15.12.12
 * Time: 15:43
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "file"
 * From $_files['filename']['name']
 */
class FieldFile extends Field
{
  // Destination directory
  protected $_dir;
  // Prefix in front of filename
  protected $_prefix;

  protected $time;

  // Class constructor
  public function __construct($name,
                              $caption,
                              $is_required,
                              $value,
                              $dir,
                              $prefix = "",
                              $help = "",
                              $help_url = "")
  {
//      echo "<tt><pre>".print_r($value, true)."</pre></tt>";
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "file",
                        $caption,
                        $is_required,
                        $value,
                        "",
                        $help,
                        $help_url);
    $this->_dir = $dir;
    $this->_prefix = $prefix;
    $this->time = time();

    if(!empty($this->_value))
    {
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
      $file_name = preg_replace( "|[ ']|","", $this->_value[$this->_name]['name']);
      // Change russian symbol for translit
      $file_name =
          $this->_encodestring($file_name);
//      $tm = preg_replace( "|\s*|","_", $this->_value[$this->_name]['name']);
      // Insert extension from file's name
      $path_parts = pathinfo($file_name);
//        $path_parts = pathinfo($tm);
      $ext = ".".$path_parts['extension'];
      $path = basename($file_name, $ext);
//      $path = preg_replace( "| |","", $path);
      $add = $ext;

      foreach($extentions as $exten)
      {
        if(preg_match($exten, $ext)) $add = ".txt";
      }
      $path .= $add;
      $path = str_replace("//","/",$dir."/".$prefix.$this->time.$path);
      // Transfer file from temp directory of sever
      // to directory /files of Web-application
//      if(copy($this->_value[$this->_name]['tmp_name'], $path))
//      {
//        // Destroy file in temp directory
//        @unlink($this->_value[$this->_name]['tmp_name']);
//        // Change the access rights
//        @chmod($path, 0644);
//      }

      // Altrenative kind to save uploaded file
      if( move_uploaded_file( $this->_value[$this->_name]['tmp_name'], $path ) ) {
          @unlink( $this->_value[$this->_name]['tmp_name'] );
          @chmod( $path, 0644 );
      }
    }
  }

   /**
   * Function for name of field
   * and tag of control element
   * @return array
   */
  public function get_html()
  {
    // Check if not empty style
    if(!empty($this->css_style))
    {
      $style = "style=\"".$this->css_style."\"";
    }
    else $style = "";
    // Check if not empty class
    if(!empty($this->css_class))
    {
      $class = "class=\"".$this->css_class."\"";
    }
    else $class = "";

    // Form tag
    $tag = "<input $style $class
                    type=\"".$this->_type."\"
                    name=\"".$this->_name."\">\n";

    if($this->_is_required) $this->_caption .= " *";
   // Form prompt
    $help = "";
    if(!empty($this->_help))
    {
      $help .= "<span style='color:blue'>".
                  nl2br($this->_help)."</span>";
    }
    if(!empty($help)) $help .= "<br/>";
    if(!empty($this->_help_url))
    {
      $help .= "<span style='color: blue'><a href='".
                $this->_help_url.">помощь</a></span>";
    }

    // Return array for class Form
    return array($this->_caption, $tag, $help);
  }

  /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    if($this->_is_required)
    {
      if(empty($this->_value[$this->_name]))
      {
        return "Поле \"".$this->_caption."\" не заполнено";
      }
    }
    return "";
  }

  /**
   * Return recoded name of file
   * @return void
   */
  public function get_filename()
  {
    if(!empty($this->_value))
    {
      if(!empty($this->_value[$this->_name]['name']))
      {
          $tmp_name = preg_replace( "|[ ']|","", $this->_value[$this->_name]['name']);
//        echo "<tt><pre>". print_r($this->_value[$this->_name]['name'], TRUE) . "</pre></tt>";
          return mysql_real_escape_string($this->_encodestring(
                                          $this->_prefix.$this->time.$tmp_name));
      }
      else return "";
    }
    else return "";
  }
}
?>
