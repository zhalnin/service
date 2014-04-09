<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07.12.12
 * Time: 15:20
 * To change this template use File | Settings | File Templates.
 */
/**
 * Root class for inheritance
 */
abstract class Field
{
  // Name of control element
  protected $_name;
  // Type of control element
  protected $_type;
  // Title of control element on left
  protected $_caption;
  // Value of control element
  protected $_value;
  // Required control element for filling
  protected $_is_required;
  // String for additional parameters
  protected $_parameters;
  // Prompt
  protected $_help;
  // Reference for prompt
  protected $_help_url;


  // CSS class
  public $css_class;
  // CSS style
  public $css_style;


  /**
   * Construct of class.
   */
  public function __construct($name,
                              $type,
                              $caption,
                              $is_required = false,
                              $value = "",
                              $parameters = "",
                              $help = "",
                              $help_url = "")
  {
    $this->_name          = $this->_encodestring($name);
    $this->_type          = $type;
    $this->_caption       = $caption;
    $this->_is_required   = $is_required;
    $this->_value         = $value;
    $this->_parameters    = $parameters;
    $this->_help          = $help;
    $this->_help_url      = $help_url;
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
  abstract function get_html();

  /**
   * Admittance to protected and private control elements.
   * (only read)
   * @param $key
   * @return $key
   */
  public function __get($key)
  {
    $protected_property = "_".$key;
    if(property_exists($this,$protected_property))
    {
      if(isset($this->$protected_property)) return $this->$protected_property;
      else
      {
        throw new ExceptionMember($key,
          "Экземпляр ".__CLASS__."::$key не существует!");
      }
    }
    else
    {
      throw new Exception("Свойство ".__CLASS__."::$protected_property не найдено!");
    }
  }

  /**
   * To translate russian text to translit
   * @param $st
   * @return $st
   */
  protected function _mb_strtr($str, $from, $to)
  {
    return str_replace($this->_mb_str_split($from), $this->_mb_str_split($to), $str);
  }
  protected function _mb_str_split($str) {
      return preg_split('~~u', $str, null, PREG_SPLIT_NO_EMPTY);
  }
  protected function _encodestring($st)
  {
    // Replace single symbol.
    $st= $this->_mb_strtr($st,"абвгдезийклмнопрстуфхъы",
    "abvgdezijklmnoprstufh#y");
    $st= $this->_mb_strtr($st,"АБВГДEЗИЙКЛМНОПРСТУФХЪЫ",
    "ABVGDEZIJKLMNOPRSTUFH#Y");
    // Replace multiple symbol.
    $st=strtr($st,
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