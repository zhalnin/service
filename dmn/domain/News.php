<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 17/07/14
 * Time: 00:03
 */

namespace dmn\domain;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/NewsIdentityObject.php" );

require_once( "dmn/base/Registry.php" );

class News extends DomainObject {
    private $name;
    private $preview;
    private $body;
    private $putdate;
    private $url;
    private $urltext;
    private $alt;
    private $urlpict;
    private $urlpict_s;
    private $pos;
    private $hide;
    private $hidepict;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $preview
     * @param null $body
     * @param null $putdate
     * @param string $hidedate
     * @param null $url
     * @param null $urltext
     * @param null $alt
     * @param null $urlpict
     * @param null $urlpict_s
     * @param null $pos
     * @param string $hide
     * @param string $hidepict
     */
    function __construct( $id=null,
                          $name=null,
                          $preview=null,
                          $body=null,
                          $putdate=null,
                          $hidedate='hide',
                          $url=null,
                          $urltext=null,
                          $alt=null,
                          $urlpict=null,
                          $urlpict_s=null,
                          $pos=null,
                          $hide='show',
                          $hidepict='show' ) {

        $this->name         = $name;
        $this->preview      = $preview;
        $this->body         = $body;
        $this->putdate      = $putdate;
        $this->hidedate     = $hidedate;
        $this->url          = $url;
        $this->urltext      = $urltext;
        $this->alt          = $alt;
        $this->urlpict      = $urlpict;
        $this->urlpict_s    = $urlpict_s;
        $this->pos          = $pos;
        $this->hide         = $hide;
        $this->hidepict     = $hidepict;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // NewsIdentityObject
        $newsIdobj = new \dmn\mapper\NewsIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($newsIdobj, true)."</pre></tt>";
        return $finder->find( $newsIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
//        echo "<tt><pre>".print_r(__CLASS__, true)."</pre></tt>";
        $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
        return $finder->findOne( $idobj->eq( $id ) );
    }


    static function showHide( $id, $value ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
        return $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( $value ));
    }


    static function upDown( $id, $direct ) {
        switch( $direct ) {
            case 'up':
//                $finder = self::getFinder(__CLASS__);
//                $idobj = self::getIdentityObject( __CLASS__ );
//                $current = $finder->findOne( $idobj->field('id_news')->eq( $id ), '' );
//
//
//                $previous = $finder->findOne( $idobj->field('pos')->lt( $current->getPos() ), ' ORDER BY pos DESC ' );
//
//                echo "<tt><pre>".print_r($previous, true)."</pre></tt>";
                $pdo = \dmn\base\DBRegistry::getDB();

                $sel = "SELECT pos FROM system_news
                                    WHERE id_news=?
                                    LIMIT 1";
                $sth = $pdo->prepare($sel);
                $sth->execute( array( $id ) );

                $current = $sth->fetch();



                $sel2 = "SELECT pos FROM system_news
                                    WHERE pos < ?
                                    ORDER BY pos DESC
                                    LIMIT 1";
                $sth2 = $pdo->prepare($sel2);
                $sth2->execute( array( $current[pos], ) );

                $previous = $sth2->fetch();

                $upd = "UPDATE system_news
                                SET pos = ? + ? - pos
                                WHERE pos IN( ?, ? )";
                $sth3 = $pdo->prepare($upd);
                $sth3->execute( array( $current[pos], $previous[pos], $current[pos], $previous[pos] ) );
                break;
            case 'down':
                $pdo = \dmn\base\DBRegistry::getDB();
                $sel = "SELECT pos FROM system_news
                            WHERE id_news=?
                            LIMIT 1";
                $sth = $pdo->prepare($sel);
                $sth->execute( array( $id ) );

                $current = $sth->fetch();

                $sel2 = "SELECT pos FROM system_news
                            WHERE pos > ?
                            ORDER BY pos
                            LIMIT 1";
                $sth2 = $pdo->prepare($sel2);
                $sth2->execute( array( $current[pos], ) );

                $next = $sth2->fetch();

                $upd = "UPDATE system_news
                        SET pos = ? + ? - pos
                        WHERE pos IN( ?, ? )";
                $sth3 = $pdo->prepare($upd);
                $sth3->execute( array( $next[pos], $current[pos], $next[pos], $current[pos] ) );
                break;
            case 'uppest':
                $pdo = \dmn\base\DBRegistry::getDB();
                $sel = "SELECT pos FROM system_news
                            WHERE id_news=?
                            LIMIT 1";
                $sth = $pdo->prepare($sel);
                $sth->execute( array( $id ) );

                $current = $sth->fetch();

                $sel2 = "SELECT MIN(pos) FROM system_news
                            WHERE pos < ?
                            AND hide='hide'
                            ORDER BY pos DESC
                            LIMIT 1";
                $sth2 = $pdo->prepare($sel2);
                $sth2->execute( array( $current[pos], ) );

                $prev = $sth2->fetch();

                $upd = "UPDATE system_news
                        SET pos = ? + ? - pos
                        WHERE pos IN( ?, ? )";
                $sth3 = $pdo->prepare($upd);
                $sth3->execute( array( $current[pos], $prev[pos], $current[pos],  $prev[pos] ) );
        }



    }




    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    function setPreview( $preview_s ) {
        $this->preview = $preview_s;
        $this->markDirty();
    }
    function setBody( $body_s ) {
        $this->body = $body_s;
        $this->markDirty();
    }
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    function setHidedate( $hidedate ) {
        $this->hidedate = $hidedate;
        $this->markDirty();
    }
    function setUrl( $url_s ) {
        $this->url = $url_s;
        $this->markDirty();
    }
    function setUrltext( $urltext_s ) {
        $this->urltext = $urltext_s;
        $this->markDirty();
    }
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    function setUrlpict( $urlpict_s ) {
        $this->urlpict = $urlpict_s;
        $this->markDirty();
    }
    function setUrlpict_s( $urlpict_s ) {
        $this->urlpict_s = $urlpict_s;
        $this->markDirty();
    }
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
        $this->markDirty();
    }
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    function setHidepict( $hidepict_s ) {
        $this->hidepict = $hidepict_s;
        $this->markDirty();
    }

    function getName() {
        return $this->name;
    }
    function getPreview() {
        return $this->preview;
    }
    function getBody() {
        return $this->body;
    }
    function getPutdate() {
        return $this->putdate;
    }
    function getHidedate() {
        return $this->hidedate;
    }
    function getUrl() {
        return $this->url;
    }
    function getUrltext() {
        return $this->urltext;
    }
    function getAlt() {
        return $this->alt;
    }
    function getUrlpict() {
        return $this->urlpict;
    }
    function getUrlpict_s() {
        return $this->urlpict_s;
    }
    function getPos() {
        return $this->pos;
    }
    function getHide() {
        return $this->hide;
    }
    function getHidepict() {
        return $this->hidepict;
    }
}

?>