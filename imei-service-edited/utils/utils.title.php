<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:15
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

function title($pagename)
{
  return '<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="20" bgcolor="#82A6DE" class="rightpanel_ttl">
            <img src="dataimg/dot_ttl.gif" align="absmiddle">
            '.htmlspecialchars($pagename).'
          </td>
        </tr>
        <tr>
          <td height="3" nowrap bgcolor="#004BBC"></td>
        </tr>
        </table>';
}
?>