<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 09.12.12
 * Time: 22:00
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL & ~E_NOTICE);
/**
 * Form top of administration.
 */
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
<link rel="StyleSheet" type="text/css" href="../utils/cms.css">
<script type="text/javascript" src="../../js/AlezhalModules.js"></script>
<script type="text/javascript">
    AM.Event.addEvent(window, 'load', function(){
        // Находим все textarea для использования тегов
        if( AM.DOM.tag('textarea') != null ) {
            var tags = AM.DOM.tag('textarea'),
                tagLength = tags.length,
                t;
            for( t = 0; t < tagLength; t++ ) {
                AM.Event.addEvent(tags[t], 'focus', function(e) {
                    var event = AM.Event.getEvent(e);
                    tagTextarea.tag = AM.Event.getTarget(event);
                });
            }
        }
    });

    // Для сохранения textarea на странице для вставки тегов
    var tagTextarea = { tag: '' };
    /**
     * Для вставки тегов в textarea
     * @param st1
     * @param st2
     */
    function tagIns( st1, st2 ) {
        AM.DOM.tagInsert(tagTextarea.tag, st1, st2 );
        tagTextarea.tag.focus();
    }

</script>
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
        include "menu.php";
?>

    </td>
  <td class=main height=100%>
    <h1 class=namepage><?php echo htmlspecialchars($title, ENT_QUOTES) ?></h1>
    <?php echo $pageinfo ?><br/>





