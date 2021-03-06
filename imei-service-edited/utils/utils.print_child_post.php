<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07/04/14
 * Time: 13:50
 * To change this template use File | Settings | File Templates.
 */

/**
 * Функция вызывается после PagerMysql, где предварительно делается
 * выборка полей с заданными параметрами: id_parent = 0, hide='show', LIMIT 0, 10
 * Передаем id БД, начиная с наименьшего и рекурсивно проходим
 * по таблице в поиске id_parent для каждого id
 * Если какой-то id имеет значение id_parent, то выводим запись сразу
 * за его родителем
 * @param $id2
 * @return mixed
 */


function selectRecursion($id2, $page ) {
    $value = array();
    if( ! isset( $value['db'] ) ) {
        $value['db'] = new PDO( 'mysql:host=localhost;dbname=imei-service','root','zhalnin5334',
            array(
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
            )
        );
    } else {
        return $value['dsn'];
    }
    $dsn = $value['db'];
    $sth2 = $dsn->prepare("SELECT * FROM system_guestbook WHERE id_parent=:id_parent AND hide='show'");
    $sth2->bindValue(':id_parent', intval( $id2 ), PDO::PARAM_INT );
    $sth2->execute();
    while( $result2 = $sth2->fetch() ) {
        ?>

        <div class='guestbook-all-body nested-reply'>
            <div class='guestbook-all-wrap  main-content'>
                <div class='guestbook-all-title'>
                    <!--                            <h1 class="h2">-->
                    <!--                                <a href="http://imei-service.ru">Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID</a>-->
                    <!--                            </h1>-->
                    <p class="ptdg"><b><?php echo $result2['name']; ?></b>&nbsp;
                        <?php if( ! empty( $result2['city'] ) ) print "($result2[city])"; ?>&nbsp;
                        <?php echo $result2['putdate']; ?></p>
                </div>

                <div class='guestbook-all-image'>
                    <img src="images/guestbook/avatar_64x64.png" border="0" width="64" height="64" alt="<? echo $result2['name']; ?>" >
                </div>

                <div class='guestbook-all-info'>
                    <p class='ptext'><?php echo html_entity_decode( $result2['message'] ); ?></p>
                    <?php if( ! empty( $result2['answer'] ) && $result2['answer'] != '-' ) {
                        echo "<div class='panswer-wrap main-content-blue'>
                                        <p class='panswer ptdg'><b><i>Администратор</i></b></p>
                                        <div class='panswer-image'>
                                            <img src=\"images/guestbook/avatar_blue_64x64.png\" border=\"0\" width=\"64\" height=\"64\" alt=".$result2['name']." >
                                        </div>
                                        <p class=\"panswer\">".nl2br($result2['answer'])."</p>
                                      </div>";
                    }
                    ?>
                </div>
                <div class="guestbook-all-reply" id="<?php print $result2['id']; ?>"><span><a href="?page=<?php echo $page; ?>&id_parent=<?php print $result2['id']; ?>">Ответить</a></span></div>
                <?php selectRecursion($result2['id'], $page ); ?>

            </div><!-- End of guestboor-all-wrap -->
        </div><!-- End of guestbook-all-body -->
    <?php
    }
    return $result2['id'];
}
?>