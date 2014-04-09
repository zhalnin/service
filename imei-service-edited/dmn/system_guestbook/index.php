<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 31/03/14
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
//require_once ( "guestbook_example_based_on_mysql/add/class.PagerMysql.php" );
//require_once( "../config/class.config.dmn.php" );
?>

<html>
<head>
    <title>Административная часть гостевой книги</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="../utils/guestbook_admin.css">
    <link rel="stylesheet" type="text/css" href="../../css/site.css">
</head>
<body>


<div id="maket">
    <div id="header">
        <p><a href="../index.php" class="link" title="Вернуться в гостевую книгу">Гостевая книга</a></p>
        <p><a href="../../index.php" class="link" title="Вернуться на главную страницу сайта">Вернуться на главную страницу сайта</a></p>
    </div>
    <div id="content">

        <table width="100%">
            <tr>
                <td width="1%">&nbsp;</td>
                <td>





        <?php
        try {
            if( isset( $_GET['page'] ) ) {
                $page = htmlspecialchars( stripslashes( $_GET['page'] ), ENT_QUOTES );
            } else {
                $page = '';
            }
            if( isset( $_GET['order'] ) ) {
                $orderBy = htmlspecialchars( stripslashes( $_GET['order'] ), ENT_QUOTES );
            } else {
                $orderBy = '';
            }
            if( isset( $_GET['sort'] ) ) {
                $sort = htmlspecialchars( stripslashes( $_GET['sort'] ), ENT_QUOTES );
            } else {
                $sort = '';
            }
            if( isset( $_GET['start'] ) ) {
                $start = htmlspecialchars( stripslashes( $_GET['start'] ), ENT_QUOTES );
            } else {
                $start = 0;
            }

            if( $start < 0 ) $start = 0;

            $pnumber = 5;

            if( $sort == 'desc' ) {
                $sort = 'asc';
            } else {
                $sort = 'desc';
            }

            switch( $orderBy ) {
                case 'name':
                    $orderBy = 'name';
                    break;
                case 'city':
                    $orderBy = 'city';
                    break;
                case 'email':
                    $orderBy = 'email';
                    break;
                case 'url':
                    $orderBy = 'url';
                    break;
                default:
                    $orderBy = 'putdate';
                    break;
            }

//            $pagerMysql = new \guestbook_test_on_mysql\add\PagerMysql('guest', "", "ORDER BY putdate", 10, 3, "");

            $PDO = new PDO("mysql:host=localhost;dbname=talking",'root', 'zhalnin5334', array(
               PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
            ));



            $selectStmt = "SELECT * FROM guest ORDER BY $orderBy $sort LIMIT :start, :end";
            $sth = $PDO->prepare( $selectStmt );
            $sth->bindValue(':start',intval( trim( $start ) ), PDO::PARAM_INT );
            $sth->bindValue(':end', intval( trim( $pnumber ) ), PDO::PARAM_INT );
            $result = $sth->execute();
            if( ! $result ) {
                throw new Exception( "Ошибка в запросе при выборке значений для админки" );
            }
                while( $guest = $sth->fetch() ) {

                    if( $guest['hide'] == 'show' ) {
                        $showhide = "<a class=\"menu\" href=\"hide.php?id=".$guest['id']."&start=$start\" title=\"Скрыть сообщение из списка выводимых на сайте\">Скрыть сообщение</a>";
                        $tableheader = "class=\"tableheader\"";
                    } else {
                        $shohide = "<a class=\"menu\" href=\"show.php?id=".$guest['id']."&start=$start\" title=\"Включить отображение сообщения на сайте\">Отобразить сообщение</a>";
                        $tableheader = "class=\"tableheaderhide\"";
                    }
?>
                    <table class="bodytable"
                       width="100%"
                       border="1"
                       cellpadding="5"
                       cellspacing="0"
                       bordercolorligth="gray"
                       bordercolordark="white">
                    <tr <?php echo $tableheader; ?> >
                        <td><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$page."&order=name&sort=$sort"; ?>"><p class="help">Автор сообщения</p></a></td>
                        <td width="100"><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$page."&order=putdate&sort=$sort"; ?>"><p class="help">Дата отправки</p></a></td>
                        <td><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$page."&order=city&sort=$sort"; ?>"><p class="help">Город</p></a></td>
                        <td><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$page."&order=email&sort=$sort"; ?>"><p class="help">E-mail</p></a></td>
                        <td><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$page."&order=url&sort=$sort"; ?>"><p class="help">Url</p></a></td>
                    </tr>
                    <tr>
                        <td><p class="help"><?php echo $guest['name'] ?></p></td>
                        <td><p class="help"><?php echo $guest['putdate'] ?></p></td>
                        <td><p class="help"><?php echo $guest['city'] ?></p></td>
                        <td><p class="help"><?php echo $guest['email'] ?></p></td>
                        <td><p class="help"><?php echo $guest['url'] ?></p></td>
                    </tr>
                    <tr>
                        <td valign="top"><p class="zag2">Сообщение:</p></td>
                        <td  colspan="5"><?php echo $guest['message'] ?></td>
                    </tr>
                    <tr>
                        <td><p class="zag2">Администратор:</p></td>
                        <td colspan="5"><p><?php echo $guest['answer'] ?></p></td>
                    </tr>
                </table>
<?php
                echo "<p class=\"menu\"><a class=\"menu\" href=\"editcommentform.php?id=".$guest['id']."&start=$start\" title=\"Редактировать сообщение\">Редактировать</a>";
                echo "&nbsp;&nbsp;".$showhide;
                echo "&nbsp;&nbsp;<a class=\"menu\" href=\"delpost.php?id=".$guest['id']."&start=$start\" title=\"Удалить сообщение\">Удалить сообщение</a></p>";

                }
                $sth->closeCursor();



        } catch ( PDOException $ex ) {
            file_put_contents( 'error_pdo_admin.txt', $ex->getMessage(), -1, FILE_APPEND );
            print $ex->getMessage();
        } catch ( Exception $ex ) {
            file_put_contents( 'error_admin.txt', $ex->getMessage(), -1, FILE_APPEND );
            print $ex->getMessage();
        }


?>


                </td>
                <td width="1%">&nbsp;</td>
            </tr>
        </table>




    </div>
    <div id="rasporka"></div>
</div>
<div id="footer">Здесь Футер</div>

</body>
</html>