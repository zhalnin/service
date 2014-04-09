<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15.12.12
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
 
class FieldTitle extends Field {
  // Header's size 1, 2, 3, 4, 5, 6 for
  // h1, h2, h3, h4, h5, h6, in according with
  protected $_h_type;
  // Class constructor
  public function __construct($value = "",
                              $h_type = 3,
                              $parameters = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct("",
                        "title",
                        "",
                        false,
                        $value,
                        $parameters,
                        "",
                        "");
    if($h_type > 0 && $h_type < 7) $this->_h_type = $h_type;
    // Assign the value of the variable
    else $this->_h_type = 3;
  }

  /**
  * Function for name of field
  * and tag of control element
  * @return array
  */
  public function get_html()
  {
    // Form tag
    $tag = htmlspecialchars($this->_value, ENT_QUOTES);
    $pattern = "#\[b\](.+)\[\/b\]#isU";
    $tag = preg_replace($pattern, '<b>\\1</b>', $tag);
    $pattern = "#\[i\](.+)\[\/i\]#isU";
    $tag = preg_replace($pattern,'<i>\\1</i>', $tag);
    $pattern = "#\[url\][/s]*((?=http:)[\S]*)[\s]*\[\/url\]#isU";
    $tag = preg_replace($pattern,
      '<a href="\\1" target="_blank">\\1</a>', $tag);
    $pattern =
        "#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[\/url\]#isU";

    $tag = preg_replace($pattern, '<a href="\\1" target="_blank">\\2</a>',
        $tag);
    if(get_magic_quotes_gpc()) $tag = stripslashes($tag);
    $tag = "<h".$this->_h_type.">".$this->_value."</h".$this->_h_type.">";

    return array($this->_caption, $tag);
  }

  /**
   * Check correct of date
   * @return string
   */
  function check()
  {
    return "";
  }
}
?>