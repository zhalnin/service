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
    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
        return $finder->findOne( $idobj->eq( $id ) );
    }


    /**
     * Метод для смены,
     * сокрытия или отображения позиции в блоке новостей
     * @param $id - id новости
     * @param $direct - направление перемещения блока
     */
    static function position( $id, $action ) {
        $result = array();
        $finder = self::getFinder( __CLASS__ );
        $curobj = self::getIdentityObject( __CLASS__ );
        $prevobj = self::getIdentityObject( __CLASS__ );
        switch( $action ) {
            case 'up': // движение позиции вверх
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_news' )->eq( $id ), '' )->getPos();
                $tmp = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' );
                if( !empty( $tmp ) ) { // если предыдущая позиция существует
                    // получаем предыдущую позицию относительно целевой строки в БД
                    $result['previous'] = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' )->getPos();
                    // меняем позицию местами
                    $finder->upDownUpdate( $result, $action );
                }
                break;
            case 'down': // движение позиции вниз
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_news' )->eq( $id ), '' )->getPos();
                $tmp = $finder->upDownSelect( $prevobj->field('pos')->gt( $result['current']), ' ORDER BY pos ' );
                if( !empty( $tmp ) ) { // если существует следующая позиция
                    // получаем предыдущую позицию относительно целевой строки в БД
                    $result['next'] = $finder->upDownSelect( $prevobj->field('pos')->gt( $result['current']), ' ORDER BY pos ' )->getPos();
                    // меняем позицию местами
                    $finder->upDownUpdate( $result, $action );
                }
                break;
            case 'show': // отображение позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'hide' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;

            case 'hide': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\NewsIdentityObject( 'id_news' );
//                echo "<tt><pre>". print_r($id, TRUE) . "</pre></tt>";
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'show' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;
        }
    }

    static function findMaxPos() {
        $finder = self::getFinder( __CLASS__ );
        return $finder->findMaxPos();
    }

    static function findPhotoSetting() {
        $finder = self::getFinder( __CLASS__ );
        return $finder->findPhotoSetting();
    }

    /**
     * устанавливем имя
     * @param $name_s
     */
    function setName( $name_s ) {
        $this->name = $name_s;
        $this->markDirty();
    }
    /**
     * устанавливаем превьюшку
     * @param $preview_s
     */
    function setPreview( $preview_s ) {
        $this->preview = $preview_s;
        $this->markDirty();
    }
    /**
     * устанавливаем тело новости
     * @param $body_s
     */
    function setBody( $body_s ) {
        $this->body = $body_s;
        $this->markDirty();
    }
    /**
     * устанавливаем дату
     * @param $putdate_s
     */
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    /**
     * устанавливаем сокрытие/отображение даты добавления новости
     * @param $hidedate
     */
    function setHidedate( $hidedate ) {
        $this->hidedate = $hidedate;
        $this->markDirty();
    }
    /**
     * устанавливаем ссылку
     * @param $url_s
     */
    function setUrl( $url_s ) {
        $this->url = $url_s;
        $this->markDirty();
    }
    /**
     * устанавливаем текст ссылки
     * @param $urltext_s
     */
    function setUrltext( $urltext_s ) {
        $this->urltext = $urltext_s;
        $this->markDirty();
    }
    /**
     * устанавливаем текст изображения
     * @param $alt_s
     */
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    /**
     * устанавливаем путь до изображения
     * @param $urlpict_s
     */
    function setUrlpict( $urlpict_s ) {
        $this->urlpict = $urlpict_s;
        $this->markDirty();
    }
    /**
     * устанавливаем путь до изображения
     * @param $urlpict_s
     */
    function setUrlpict_s( $urlpict_s ) {
        $this->urlpict_s = $urlpict_s;
        $this->markDirty();
    }
    /**
     * устанавливаем позицию
     * @param $pos_s
     */
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
        $this->markDirty();
    }
    /**
     * устанавливаем сокрытие/отображение новости
     * @param $hide_s
     */
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
        $this->markDirty();
    }
    /**
     * устанавливаем сокрытие/отображение изображения
     * @param $hidepict_s
     */
    function setHidepict( $hidepict_s ) {
        $this->hidepict = $hidepict_s;
        $this->markDirty();
    }

    /**
     * получаем имя
     * @return null
     */
    function getName() {
        return $this->name;
    }
    /**
     * получаем превьюшку
     * @return null
     */
    function getPreview() {
        return $this->preview;
    }
    /**
     * получаем тело новости
     * @return null
     */
    function getBody() {
        return $this->body;
    }
    /**
     * получаем дату добавления новости
     * @return null
     */
    function getPutdate() {
        return $this->putdate;
    }
    /**
     * получаем флаг сокрытия/отображения даты
     * @return null
     */
    function getHidedate() {
        return $this->hidedate;
    }
    /**
     * получаем ссылку
     * @return null
     */
    function getUrl() {
        return $this->url;
    }
    /**
     * получаем текст ссылки
     * @return null
     */
    function getUrltext() {
        return $this->urltext;
    }
    /**
     * получаем текст изображения
     * @return null
     */
    function getAlt() {
        return $this->alt;
    }
    /**
     * получаем изображение
     * @return null
     */
    function getUrlpict() {
        return $this->urlpict;
    }
    /**
     * получаем изображение
     * @return null
     */
    function getUrlpict_s() {
        return $this->urlpict_s;
    }
    /**
     * получаем позицию
     * @return null
     */
    function getPos() {
        return $this->pos;
    }
    /**
     * получаем флаг сокрытия/отображения новости
     * @return null
     */
    function getHide() {
        return $this->hide;
    }
    /**
     * получаем флаг сокрытия/отображения изображения
     * @return null
     */
    function getHidepict() {
        return $this->hidepict;
    }
}
?>