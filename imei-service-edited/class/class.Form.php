<?php
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 08.12.12
 * Time: 0:24
 * To change this template use File | Settings | File Templates.
 */
/**
 * This Class construct html form
 */
class Form
{
  // Array of control elements
  public $fields;
  // Name of button
  protected $_button_name;
  // Class
  protected $_css_td_class;
  // Style
  protected $_css_td_style;
  // Class of control element
  protected $_css_fld_class;
  // Style of control element
  protected $_css_fld_style;

  // Class constructor
  public function __construct($flds,
                              $button_name,
                              $css_td_class="",
                              $css_td_style="",
                              $css_fld_class="",
                              $css_fld_style="")
  {
    $this->fields         = $flds;
    $this->_button_name   = $button_name;
    $this->_css_td_class  = $css_td_class;
    $this->_css_td_style  = $css_td_style;
    $this->_css_fld_class = $css_fld_class;
    $this->_css_fld_style = $css_fld_style;
    // Check if member of array to be
    // child of Field's class
    foreach($flds as $key => $obj)
    {
      if(!is_subclass_of($obj,"Field"))
      {
        throw new ExceptionObject($key,
                                  "\"$key\" не является элементом управления!");
      }
    }
  }


  /**
   * Print HTML-form in window of browser
   * @return HTML-form
   */
  public function print_form()
  {
    $enctype = "";
    if(!empty($this->fields))
    {
      foreach($this->fields as $obj)
      {
//        echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
        // Give all control elements style
        if(!empty($this->_css_fld_class))
        {
          $obj->css_class = $this->_css_fld_class;
//          echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
        }
        if(!empty($this->_css_fld_style))
        {
          $obj->css_style = $this->_css_fld_style;
        }
        // Check for field "file"
        // If is exist include header
        // enctype="multipart/form-data"
        if($obj->type == "file")
        {
          $enctype = "enctype=\"multipart/form-data\"";
        }
      }
    }
    // If control elements not empty take this
    if(!empty($this->_css_td_style))
    {
      $style = "style=\"".$this->_css_td_style."\"";
    }
    else $style = "";

    if(!empty($this->_css_td_class))
    {
      $class = "class=\"".$this->_css_td_class."\"";
    }
    else $class = "";

    // Print form
    echo "<form name=\"form\" $enctype method=\"post\">";
    echo "<table>";
    if(!empty($this->fields))
    {
      foreach($this->fields as $obj)
      {
        // Take name of field and his HTML representation
        list($caption, $tag, $help, $alternative) = $obj->get_html();

        if(is_array($tag)) $tag = implode("<br/>", $tag);

        switch($obj->type)
        {
          case "hidden":
            // Hidden field
            echo $tag;
            break;
          case "paragraph":
          case "title":
            // Header
            echo "<tr>
                    <td $style $class colspan=\"2\" valign=\"top\">$tag</td>
                 </tr>\n";
            break;
          default:
            // Elements of control by default
            echo "<tr>
                    <td width=\"100\"
                        $style $class valign=\"top\">$caption:</td>
                    <td $style $class valign=\"top\">$tag</td>
                  </tr>\n";
            if(!empty($help))
            {
              echo "<tr>
                      <td>&nbsp;</td>
                      <td $style $class valign=\"top\">$help</td>
                    </tr>";
            }
            break;
        }
      }
      // Print confirm button
      echo "<tr>
              <td $style $class></td>
              <td $style $class>
                <input class=\"button\" type=\"submit\"
                  value=\"".htmlspecialchars($this->_button_name, ENT_QUOTES)."\"</td>
            </tr>\n";
      echo "</table>";
      echo "</form>";
    }
  }

  /**
   * Reload of __toString().
   * return form
   */
  public function __toString(){
    $this->print_form();
  }

  /**
   * Check correct entering data into form
   * @return array
   */
  public function check()
  {
    // Subsequently invoke method check() for
    // every object from Field
    $arr = array();
    if(!empty($this->fields))
    {
      foreach($this->fields as $obj)
      {
//        echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
        $str = $obj->check();
        if(!empty($str)) $arr[] = $str;
      }
    }
    return $arr;
  }
}
?>