<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.12.12
 * Time: 22:00
 * To change this template use File | Settings | File Templates.
 */


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
<link rel="StyleSheet" type="text/css" href="../utils/cms.css">
</head>
<body leftmargin="0"
      marginheight="0"
      marginwidth="0"
      rightmargin="0"
      bottommargin="0"
      topmargin="0" >
<table width="100%"
       border="0"
       cellspacing="0"
       cellpadding="0"
       height="100%">
  <tr valign="top">
    <td colspan="3">
      <table class="topmenu" border="0">
        <tr>
          <td width="5%">&nbsp;</td>
          <td>
            <h1 class="title"><?php echo $title; ?></h1>
          </td>
          <td>

            <a href="../index.php"
               title="Вернуться на страницу администрирования сайта">
                 Администрирование</a>&nbsp;&nbsp;

            <a href="../../index.php"
               title="Вернуться на главную страницу сайта" >
                 Вернуться на сайт</a>

          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr valign="top">
    <td class="menu">



<!--      MENU.PHP-->

<?php
  // Form menu of CMS
//        include "menu.php";

  error_reporting(E_ALL & ~E_NOTICE);

  // Form menu of CMS

  // Open dir /dmn
  $dir = opendir("..");
  // Go through every files in dir in loop
  while (($file = readdir($dir)) !== false)
  {
    // Work only with subdirs
    // are ingoring files
    if(is_dir("../$file"))
    {
      // Exclude current ".", parent ".."
      // dirs, and utils
      if($file != "." && $file != ".." && $file != "utils")
      {
        // Looking for file with description of
        // block .htdir
        if(file_exists("../$file/.htdir"))
        {
          // File .htdir exists -
          // read name of block and
          // his description
          list($block_name,
               $block_description) = file("../$file/.htdir");
        }
        else
        {
          // File .htdir does not exist -
          // set title over his subdir's name
          $block_name        = "$file";
          $block_description = "";
        }

        // Set another style for this point
        if(strpos($_SERVER['PHP_SELF'], $file) !== false)
        {
          $style = 'class=\"active\"';
        }
        else $style = '';

        // Form point of menu
        echo "<div $style>
                <a class=\"menu\"
                   href='../$file'
                   title='$block_description'>
                   $block_name
                </a>
              </div>";
      }
    }
  }
  // Close dir
  closedir($dir);




?>
<!--      END MENU.PHP-->


    </td>
  <td class=main height=100%>
    <h1 class=namepage><?php echo htmlspecialchars($title, ENT_QUOTES) ?></h1>
    <?php echo $pageinfo ?><br>





<!-- bottom.php -->

  <br><br></td><td width=10%>&nbsp;</td></tr>
<tr class=authors>
  <td colspan="3">
      CMS выполнена и поддерживается "alezhal-studio"
     <a href="#">alezhal</a></td></tr>
</table>
</body>
</html>
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function delete_position(url, ask)
  {
    if(confirm(ask))
    {
      location.href=url;
    }
    return false;
  }
//-->
</script>