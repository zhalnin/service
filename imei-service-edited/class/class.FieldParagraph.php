<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15.12.12
 * Time: 23:33
 * To change this template use File | Settings | File Templates.
 */

/**
 * Describe control element "file"
 * From $_files['filename']['name']
 */
class FieldParagraph extends Field {
  // Class constructor
  public function __construct($value = "",
                              $parameters = "")
  {
    // Invoke construct of parent class Field
    // for initiation of params
    parent::__construct("",
                        "paragraph",
                        "",
                        false,
                        $value,
                        $parameters,
                        "",
                        "");
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
    $tag = preg_replace($pattern, '<i>\\1</i>', $tag);
    $pattern = "#\[url\][\s]*((?=http:)[\S]*)[\s]*\[\/url\]#isU";
    $tag = preg_replace($pattern,'<a href="\\1" target="_blank">\\1</a>', $tag);
    $pattern = "#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[\/url\]#isU";
    $tag = preg_replace($pattern, '<a href="\\1" target="_blank">\\2</a>', $tag);
    if(get_magic_quotes_gpc()) $tag = stripslashes($tag);
    return array($this->_caption, nl2br($tag));
  }

  /**
   * Check correct of date
   * @return string
   */
  public function check()
  {
    return "";
  }
}
?>