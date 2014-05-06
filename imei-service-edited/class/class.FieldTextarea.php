<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 10.12.12
 * Time: 12:30
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "textarea"
 * <textarea> ... </textarea>
 */
class FieldTextarea extends Field {
  // Size of text field
  protected $_cols;
  // Max quantity for entering data
  protected $_rows;
  // Block field
  protected $_disabled;
  // Only for reading
  protected $_readonly;
  // Prohibition of transfer line
  protected $_wrap;
  protected $maxlength;

  // Class constructor
  public function __construct($name,
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
                              $help_url = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct($name,
                        "textarea",
                        $caption,
                        $is_required,
                        $value,
                        $parameters,
                        $help,
                        $help_url);

    // Initiate class member
    $this->_cols      = $cols;
    $this->_rows      = $rows;
    $this->_disabled  = $disabled;
    $this->_readonly  = $readonly;
    $this->_wrap      = $wrap;
  }

    /**
   * Public html form to page.
   * @return array
   */
  public function get_html()
  {
    // Check if not empty style
    if(!empty($this->css_style))
    {
      $style = "style=\"".$this->css_style."\"";
    }
    $style = "";
    // Check if not empty class
    if(!empty($this->css_class))
    {
      $class = "class=\"".$this->css_class."\"";
    }
    $class = "";
    // Check if not empty cols
    if(!empty($this->_cols))
    {
      $cols = "cols=\"".$this->_cols."\"";
    }else{
        $cols = "";
    }

    // Check if not empty rows
    if(!empty($this->_rows))
    {
      $rows = "rows=\"".$this->_rows."\"";
    } else {
        $rows = "";
    }

      if( !empty( $this->maxlength ) ) {
          $maxlength = "maxlength=\"".$this->maxlength."\"";
      } else {
          $maxlength = "";
      }

    // Check if disabled is set
    if($this->_disabled) $disabled = "disabled";
    else $disabled = "";
    // Check if readonly is set
    if($this->_readonly) $readonly = "readonly";
    else $readonly = "";
    // Check if wrap is set
    if($this->_wrap) $wrap = "wrap";
    else $wrap = "";
    //////////////////////////////////////////////////////// VALUE
    // Check if $this->value is array
    if(is_array($this->_value))
    {
      // Implode by transfering string
      $this->_value = implode("\r\n", $this->_value);
    }
    if(!get_magic_quotes_gpc())
    {
      // Make replace ' to "
      $output = str_replace('\r\n', "\r\n", $this->_value);
    }
    else $output = $this->_value;
    // Form tag
    $tag = "<textarea $style $class
              name=\"".$this->_name."\"
              $rows $cols $disabled $readonly $wrap>".
        htmlspecialchars(stripslashes( $output), ENT_QUOTES  )."</textarea>";
    // Check if field is required
    if($this->_is_required) $this->_caption .= " *";
    // Form prompt
    $help = "";
    if(!empty($this->_help))
    {
      $help .= "<span style='color: blue'>".
                nl2br($this->_help)."</span>";
    }
    if(!empty($help)) $help .= "<br/>";
    if(!empty($this->_help_url))
    {
      $help .= "<span style='color: blue'>
                  <a href=".$this->_help_url.">помощь</a>
                </span>";
    }
    // Return array for class Form
    return array($this->_caption ,$tag, $help);
  }

   /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    // Make safe text before insert text into DataBase
    if(!get_magic_quotes_gpc())
    {
      $this->_value = mysql_real_escape_string($this->_value);
    }
    // If field is required
    if($this->_is_required)
    {
      // Check if it empty
      if(empty($this->_value))
      {
        return "Поле \"".$this->_caption."\" не заполнено";
      }
    }
    return "";
  }
}
?>
