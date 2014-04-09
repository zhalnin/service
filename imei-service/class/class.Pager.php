<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.12.12
 * Time: 22:49
 * To change this template use File | Settings | File Templates.
 */

/**
 * Root class for inheritance
 */
class Pager
{
  /**
   * Construct of class.
   */
  protected function __construct()
  {

  }

  protected function _get_total()
  {
    // Total quantity of records
  }

  protected function _get_pnumber()
  {
    // Quantity of position on page
  }

  protected function _get_page_link()
  {
    // Quantity of reference from left and right
    // from current page
  }

  protected function _get_parameters()
  {
    // Additional parameters, which
    // is necessarely to take along by reference
  }

  // References for another pages
  public function __toString()
  {
    // String for returning of result
    $return_page = "";

    // By GET-parameters page
    // number of current page sent
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;

    // Calculate number of page in system
    $number = (int)($this->get_total()/$this->get_pnumber());
    if((float)($this->get_total()/$this->get_pnumber()) - $number != 0)
    {
      $number++;
    }
    // Check if exists reference on the left
    if($page - $this->get_page_link() > 1)
    {
      // Start pages form left
      $return_page .= "<a href=$_SERVER[PHP_SELF]".
                      "?page=1{$this->get_parameters()}>
                      [1-{$this->get_pnumber()}]
                      </a>&nbsp;&nbsp;...&nbsp;&nbsp;";
      // Exist
      for($i = $page - $this->get_page_link(); $i < $page; $i++)
      {
        // Three pages after ...
        $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                        "?page=$i{$this->get_parameters()}>
                        [".(($i - 1) * $this->get_pnumber() + 1).
                        "-".$i * $this->get_pnumber()."]
                        </a>&nbsp;";
      }
    }
    else
    {
      // Not exist
      for($i = 1; $i < $page; $i++)
      {
        // Pages until first page
        $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                        "?page=$i{$this->get_parameters()}>
                        [".(($i - 1) * $this->get_pnumber() + 1).
                        "-".$i * $this->get_pnumber()."]
                        </a>&nbsp;";
      }
    }

    // Check if exists reference on the right
    if($page + $this->get_page_link() < $number)
    {
      // Exist
      for($i = $page; $i <= $page + $this->get_page_link(); $i++)
      {
        if($page == $i)
          // Current page
          $return_page .= "&nbsp;[".
              (($i - 1) * $this->get_pnumber() + 1).
              "-".$i*$this->get_pnumber()."]&nbsp;";
        else
          // Three pages before ...
          $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                          "?page=$i{$this->get_parameters()}>
                          [".(($i - 1) * $this->get_pnumber() + 1).
                          "-".$i * $this->get_pnumber()."]
                          </a>&nbsp;";
      }
      // End pages form right
      $return_page .= "&nbsp;...&nbsp;&nbsp;".
          "<a href=$_SERVER[PHP_SELF]".
          "?page=$number{$this->get_parameters()}>
          [".(($number - 1)*$this->get_pnumber() + 1).
          "-{$this->get_total()}]
          </a>&nbsp;";
    }
    else
    {
      // Not exist
      for($i = $page; $i <= $number; $i++)
      {
        if($number == $i)
        {
          if($page == $i)
            // Current last page
            $return_page .= "&nbsp;[".
                (($i - 1) * $this->get_pnumber() + 1).
                "-{$this->get_total()}]&nbsp;";
          else
            // Last page
            $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                            "?page=$i{$this->get_parameters()}>
                            [".(($i - 1) * $this->get_pnumber() + 1).
                            "-{$this->get_total()}]
                            </a>&nbsp;";

        }
        else
        {
          if($page == $i)
            // Current page from right
            $return_page .= "&nbsp;[".
                (($i - 1) * $this->get_pnumber() + 1).
                "-".$i * $this->get_pnumber()."]&nbsp;";
          else
            // Another page until last page
            $return_page .= "&nbsp;<a href=$_SERVER[PHP_SELF]".
                            "?page=$i{$this->get_parameters()}>
                            [".(($i - 1) * $this->get_pnumber() + 1).
                            "-".$i * $this->get_pnumber()."]
                            </a>&nbsp;";
        }
      }
    }
    return $return_page;
  }

  /**
   * Alternative kind of pager navigation
   * @return array
   */
  public function print_page()
  {
    // String for return result
    $return_page = "";

    // Page transfer by GET-parameter
    // of current page
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    // Calculate number of pages in system
    $number = (int)($this->get_total()/$this->get_pnumber());
//    echo "<tt><pre>".print_r($number, TRUE)."</pre></tt>";
//    echo "<tt><pre>".print_r($this->get_total(), TRUE)."</pre></tt>";
//    echo "<tt><pre>".print_r($this->get_pnumber(), TRUE)."</pre></tt>";
    if((float)($this->get_total()/$this->get_pnumber()) - $number != 0) $number++;
//    echo "<tt><pre>".print_r($number, TRUE)."</pre></tt>";
    // Reference to first page
    $return_page .= "<a href='$_SERVER[PHP_SELF]".
                    "?page=1{$this->get_parameters()}'>".
                    "&lt;&lt;</a> ... ";
//    echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Print reference "Back", if this is not first page
    if($page != 1) $return_page .= " <a href='$_SERVER[PHP_SELF]".
                                    "?page=".($page - 1)."{$this->get_parameters()}'>".
                                    "&lt;</a> ... ";
//    echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Print previous elements
    if($page > $this->get_page_link() + 1)
    {
      for($i = $page - $this->get_page_link(); $i < $page; $i++)
      {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i'>$i</a> ";
      }
//      echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }
    else
    {
      for($i = 1; $i < $page; $i++)
      {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i'>$i</a> ";
      }
//      echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }

    // Print current element
    $return_page .= "$i ";
    // Print next element
    if($page + $this->get_page_link() < $number)
    {
      for($i = $page + 1; $i <= $page + $this->get_page_link(); $i++)
      {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i'>$i</a> ";
      }

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }
    else
    {
      for($i = $page + 1; $i <= $number; $i++)
      {
        $return_page .= "<a href='$_SERVER[PHP_SELF]?page=$i'>$i</a> ";
      }

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    }

    // Print reference "Forward", if it is not last page
    if($page != $number) $return_page .= " ... <a href='".
                        "$_SERVER[PHP_SELF]?page=".
                        ($page + 1)."{$this->get_parameters()}'>".
                        "&gt;</a>";

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    // Reference for last page
    $return_page .= " ... <a href='$_SERVER[PHP_SELF]".
                    "?page=$number{$this->get_parameters()}'>".
                    "&gt;&gt;</a>";

//        echo "<tt><pre>".print_r($return_page, TRUE)."</pre></tt>";
    return $return_page;
  }
}
?>