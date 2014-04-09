<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.12.12
 * Time: 22:21
 * To change this template use File | Settings | File Templates.
 */
 

error_reporting(E_ALL & ~E_NOTICE);

/**
 * Form menu of administration.
 */

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