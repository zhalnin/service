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
require_once( "dmn/mapper/NewsUpDownFactory.php" );

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

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
//    static function find( $id ) {
//        $finder = self::getFinder( __CLASS__ );
////        echo "<tt><pre>".print_r(__CLASS__, true)."</pre></tt>";
//        $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
//        return $finder->findOne( $idobj->eq( $id ) );
//    }


    /**
     * Метод для сокрытия или отображения
     * позиции в блоке новостей
     * @param $id - id новости
     * @param $value - значение поля hide ('show' или 'hide')
     * @return mixed
     */
    static function showHide( $id, $value ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
        return $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( $value ));
    }


    /**
     * Метод для смены позиции в блоке новостей
     * @param $id - id новости
     * @param $direct - направление перемещения блока
     */
    static function upDown( $id, $direct ) {
        $result = array();
        switch( $direct ) {
            case 'up':
                $finder = self::getFinder( __CLASS__ );
                $curobj = self::getIdentityObject( __CLASS__ );
                $prevobj = self::getIdentityObject( __CLASS__ );
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_news' )->eq( $id ), '' )->getPos();
                $tmp = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' );
                if( !empty( $tmp ) ) { // если предыдущая позиция существует
                    // получаем предыдущую позицию относительно целевой строки в БД
                    $result['previous'] = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' )->getPos();
                    // меняем позицию местами
                    $finder->upDownUpdate( $result, $direct );
                }
                break;
            case 'down':
                $finder = self::getFinder( __CLASS__ );
                $curobj = self::getIdentityObject( __CLASS__ );
                $prevobj = self::getIdentityObject( __CLASS__ );
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_news' )->eq( $id ), '' )->getPos();
                $tmp = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' );
                if( !empty( $tmp ) ) { // если существует следующая позиция
                    // получаем предыдущую позицию относительно целевой строки в БД
                    $result['next'] = $finder->upDownSelect( $prevobj->field('pos')->gt( $result['current']), ' ORDER BY pos ' )->getPos();
                    // меняем позицию местами
                    $finder->upDownUpdate( $result, $direct );
                }
                break;

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