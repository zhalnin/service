<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 07/04/14
 * Time: 13:50
 * To change this template use File | Settings | File Templates.
 */
namespace imei_service\view\utils;

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


//require_once( "imei_service/" );
try {

    function selectRecursion($id2, $page ) {
        $value = array();

        if( ! isset( $value['db'] ) ) {
            $dsn = \imei_service\base\DBRegistry::getDB();
            if( is_null( $dsn ) ) {
                throw new \imei_service\base\AppException( "No DSN" );
            }
            $value['db'] = $dsn;
        }



        $dsn = $value['db'];
        $sth = $dsn->prepare("SELECT * FROM system_guestbook WHERE id_parent=:id_parent AND hide='show' ORDER BY putdate asc");
        $sth->bindValue(':id_parent', intval( $id2 ), \PDO::PARAM_INT );
        $sth->execute();
        while( $result = $sth->fetch() ) {
            ?>

            <div class='guestbook-all-body nested-reply'>
                <div class='guestbook-all-wrap  main-content'>
                    <div class='guestbook-all-title'>
                        <!--                            <h1 class="h2">-->
                        <!--                                <a href="http://imei-service.ru">Отвязка iPhone, проверка по IMEI, S/N и регистрация UDID</a>-->
                        <!--                            </h1>-->
                        <p class="ptdg"><b><?php echo $result['name']; ?></b>&nbsp;
                            <?php if( ! empty( $result['city'] ) ) print "($result[city])"; ?>&nbsp;
                            <?php echo $result['putdate']; ?></p>
                    </div>

                    <div class='guestbook-all-image'>
                        <img src="imei_service/view/images/guestbook/avatar_64x64.png" border="0" width="64" height="64" alt="<? echo $result['name']; ?>" >
                    </div>

                    <div class='guestbook-all-info'>
                        <p class='ptext'><?php echo html_entity_decode( $result['message'] ); ?></p>
                        <?php if( ! empty( $result['answer'] ) && $result['answer'] != '-' ) {
                            echo "<div class='panswer-wrap main-content-blue'>
                                            <p class='panswer ptdg'><b><i>Администратор</i></b></p>
                                            <div class='panswer-image'>
                                                <img src=\"images/guestbook/avatar_blue_64x64.png\" border=\"0\" width=\"64\" height=\"64\" alt=".$result['name']." >
                                            </div>
                                            <p class=\"panswer\">".nl2br($result['answer'])."</p>
                                          </div>";
                        }
                        ?>
                    </div>
                    <div class="guestbook-all-reply" id="<?php print $result['id']; ?>"><span><a href="?page=<?php echo $page; ?>&idp=<?php print $result['id']; ?>">Ответить</a></span></div>
                    <?php selectRecursion($result['id'], $page ); ?>

                </div><!-- End of guestboor-all-wrap -->
            </div><!-- End of guestbook-all-body -->
        <?php
        }
        return $result['id'];
    }
} catch(\imei_service\base\AppException $exc){
    require_once( "imei_service/base/Exceptions.php" );
} catch(\imei_service\base\DBException $exc) {
    require_once( "imei_service/base/Exceptions.php" );
}
?>