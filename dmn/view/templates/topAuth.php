<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22.12.12
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */
namespace dmn\view\templates;
session_start();
$sid_add_message = session_id();
error_reporting(E_ALL & ~E_NOTICE);
//require_once("count.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES); ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta content="width=1024" name="viewport">
    <meta content="<? echo htmlspecialchars($description, ENT_QUOTES); ?>" name="Description">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <!--    <link href="dmn/view/css/home-style.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="dmn/view/css/animatedCSS.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="dmn/view/css/form.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="dmn/view/css/style.css" type="text/css" rel="stylesheet">-->

    <link rel="StyleSheet" type="text/css" href="dmn/view/css/cms.css">

    <!--    <link href="css/wysiwyg.css" type="text/css" rel="stylesheet">-->
    <!--    <link href="css/style_enhanced.css" type="text/css" rel="stylesheet">-->
    <script type="text/javascript" src="dmn/view/js/AlezhalModules.js"></script>
    <script type="text/javascript" src="dmn/view/js/helperFunctions.js"></script>
    <script type="text/javascript" src="dmn/view/js/wysiwyg.js"></script>
    <script type="text/javascript" src="dmn/view/js/imei_form.js"></script>
    <script type="text/javascript" src="dmn/view/js/utilities.js"></script>
    <script type="text/javascript" src="dmn/view/js/lib.js"></script>
    <script type="text/javascript" src="dmn/view/js/load.js"></script>
    <script type="text/javascript" src="dmn/view/js/currency.js"></script>
    <script type="text/javascript" src="dmn/view/js/dragMaster.js"></script>

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

                        <a href="?cmd=News"
                           title="Вернуться на страницу администрирования сайта">
                            Администрирование</a>&nbsp;&nbsp;

                        <a href="runner.php?cmd=News"
                           title="Вернуться на главную страницу сайта" >
                            Вернуться на сайт</a>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr valign="top">
        <td width="10%">
        </td>
        <td class='main' height=80% width="10%">

            <div class="headerform"><p class="nameaction"><?php echo htmlspecialchars($title, ENT_QUOTES) ?></p></div>
            <div class="bodyform">
            <div class='blockremark'><?php echo $pageinfo ?><br/></div>
            <div class="tableform">






