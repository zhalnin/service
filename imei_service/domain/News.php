<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/05/14
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */

namespace imei_service\domain;

require_once( "imei_service/domain/DomainObject.php" );
require_once( "imei_service/mapper/NewsIdentityObject.php" );

class News extends DomainObject {
    private $name;
    private $preview;
    private $body;
    private $putdate;
    private $alt;
    private $urlpict_s;
    private $url;
    private $urltext;
    private $hidepict;

    /**
     * Поля из БД - сохраняем из в переменнвн
     * @param null $id
     * @param null $name
     * @param null $preview
     * @param null $body
     * @param null $putdate
     * @param null $alt
     * @param null $urlpict_s
     * @param null $url
     * @param null $urltext
     * @param null $hidepict
     */
    function __construct( $id=null,
                          $name=null,
                          $preview=null,
                          $body=null,
                          $putdate=null,
                          $alt=null,
                          $urlpict_s=null,
                          $url=null,
                          $urltext=null,
                          $hidepict=null ) {

        $this->name         = $name;
        $this->preview      = $preview;
        $this->body         = $body;
        $this->putdate      = $putdate;
        $this->alt          = $alt;
        $this->urlpict_s    = $urlpict_s;
        $this->url          = $url;
        $this->urltext      = $urltext;

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
        $news_idobj = new \imei_service\mapper\NewsIdentityObject( 'hide' ); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
        $news_idobj->eq('show');
//        echo "<tt><pre>".print_r($news_idobj, true)."</pre></tt>";
        return $finder->find( $news_idobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \imei_service\mapper\NewsIdentityObject( 'id' );
        return $finder->findOne( $idobj->eq( $id ) );
    }

    function setName( $name_s ) {
//        echo $name_s;
        $this->name = $name_s;
        $this->markDirty();
    }
    function setPreview( $preview_s ) {
        $this->preview = $preview_s;
    }
    function setBody( $body_s ) {
        $this->body = $body_s;
    }
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
    }
    function setUrlpict_s( $urlpict_s ) {
        $this->urlpict_s = $urlpict_s;
    }
    function setAlt( $alt ) {
        $this->alt = $alt;
    }
    function setUrl( $url ) {
        $this->url = $url;
    }
    function setUrltext( $urltext ) {
        $this->urltext = $urltext;
    }
    function setHidepict( $hidepict ) {
        $this->hidepict = $hidepict;
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
    function getUrlpict_s() {
        return $this->urlpict_s;
    }
    function getAlt() {
        return $this->alt;
    }
    function getUrl() {
        return $this->url;
    }
    function getUrltext() {
        return $this->urltext;
    }
    function getHidepict() {
        return $this->hidepict;
    }
}

?>