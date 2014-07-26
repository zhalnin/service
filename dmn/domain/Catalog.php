<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 25/07/14
 * Time: 21:12
 */
namespace dmn\domain;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/CatalogIdentityObject.php" );
require_once( "dmn/mapper/CatalogUpDownFactory.php" );

require_once( "dmn/base/Registry.php" );

class Catalog extends DomainObject {
    private $name;
    private $orderTitle;
    private $description;
    private $keywords;
    private $abbreviatura;
    private $modrewrite;
    private $pos;
    private $hide;
    private $urlpict;
    private $alt;
    private $roundedFlag;
    private $titleFlag;
    private $altFlag;
    private $idParent;

    /**
     *  Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $orderTitle
     * @param null $description
     * @param null $keywords
     * @param null $abbreviatura
     * @param null $modrewrite
     * @param null $pos
     * @param string $hide
     * @param null $urlpict
     * @param null $alt
     * @param null $roundedFlag
     * @param null $titleFlag
     * @param null $altFlag
     * @param null $idParent
     */
    function __construct( $id=null,
                          $name=null,
                          $orderTitle=null,
                          $description=null,
                          $keywords=null,
                          $abbreviatura=null,
                          $modrewrite=null,
                          $pos=null,
                          $hide='hide',
                          $urlpict=null,
                          $alt=null,
                          $roundedFlag=null,
                          $titleFlag=null,
                          $altFlag=null,
                          $idParent=null ) {

        $this->name         = $name;
        $this->orderTitle   = $orderTitle;
        $this->description  = $description;
        $this->keywords     = $keywords;
        $this->abbreviatura = $abbreviatura;
        $this->modrewrite   = $modrewrite;
        $this->pos          = $pos;
        $this->hide         = $hide;
        $this->urlpict      = $urlpict;
        $this->alt          = $alt;
        $this->roundedFlag  = $roundedFlag;
        $this->titleFlag    = $titleFlag;
        $this->altFlag      = $altFlag;
        $this->idParent     = $idParent;

        parent::__construct( $id ); // вызываем конструктор родительского класса
    }

    /**
     * Здесь в родительском классе DomainObject вызываем метод getFinder,
     *
     * @return mixed
     */
    static function findAll() {
        $finder = self::getFinder( __CLASS__ ); // из родительского класса вызываем, получаем DomainObjectAssembler( PersistenceFactory )
        $idobj = self::getIdentityObject( __CLASS__ ); // catalogIdentityObject
        $catalogIdobj = new \dmn\mapper\CatalogIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $catalogIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
    static function find( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogIdentityObject( 'id_catalog' );
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
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_catalog' )->eq( $id ), '' )->getPos();
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
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_catalog' )->eq( $id ), '' )->getPos();
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
                $idobj = new \dmn\mapper\CatalogIdentityObject( 'id_catalog' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'hide' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;

            case 'hide': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\CatalogIdentityObject( 'id_catalog' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'show' ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;
        }
    }

    static function findMaxPos( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogIdentityObject( 'id_parent' );
        return $finder->findMaxPos( $idobj->eq( $id ) );
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
     * устанавливаем название для заказа
     * @param $orderTitle_s
     */
    function setOrderTitle( $orderTitle_s ) {
        $this->orderTitle = $orderTitle_s;
        $this->markDirty();
    }
    /**
     * устанавливаем описание
     * @param $description_s
     */
    function setDescription( $description_s ) {
        $this->description = $description_s;
        $this->markDirty();
    }
    /**
     * устанавливаем ключевые слова
     * @param $keywords_s
     */
    function setKeywords( $keywords_s ) {
        $this->keywords = $keywords_s;
        $this->markDirty();
    }
    /**
     * устанавливаем аббревиатуру
     * @param $abbreviatura_s
     */
    function setAbbreviatura( $abbreviatura_s ) {
        $this->abbreviatura = $abbreviatura_s;
        $this->markDirty();
    }
    /**
     * устанавливаем флаг услуги
     * @param $modrewrite_s
     */
    function setModrewrite( $modrewrite_s ) {
        $this->modrewrite = $modrewrite_s;
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
     * устанавливаем сокрытие/отображение
     * @param $hide_s
     */
    function setHide( $hide_s ) {
        $this->hide = $hide_s;
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
     * устанавливаем название основного изображения
     * @param $alt_s
     */
    function setAlt( $alt_s ) {
        $this->alt = $alt_s;
        $this->markDirty();
    }
    /**
     * устанавливаем изображение для услуги
     * @param $roundedFlag_s
     */
    function setRoundedFlag( $roundedFlag_s ) {
        $this->roundedFlag = $roundedFlag_s;
        $this->markDirty();
    }
    /**
     * устанавливаем название для услуги
     * @param $titleFlag_s
     */
    function setTitleFlag( $titleFlag_s ) {
        $this->titleFlag = $titleFlag_s;
        $this->markDirty();
    }
    /**
     * устанавливаем название изображения услуги
     * @param $altFlag_s
     */
    function setAltFlag( $altFlag_s ) {
        $this->altFlag = $altFlag_s;
        $this->markDirty();
    }
    /**
     * устанавливаем родительский id
     * @param $idParent_s
     */
    function setIdParent( $idParent_s ) {
        $this->idParent = $idParent_s;
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
     * получаем название заказа
     * @return null
     */
    function getOrderTitle() {
        return $this->orderTitle;
    }
    /**
     * получаем описание
     * @return null
     */
    function getDescription() {
        return $this->description;
    }
    /**
     * получаем ключевые слова
     * @return null
     */
    function getKeyWords() {
        return $this->keywords;
    }
    /**
     * получаем аббревиатуру
     * @return null
     */
    function getAbbreviatura() {
        return $this->abbreviatura;
    }
    /**
     * получаем флаг для определения услуги
     * @return null
     */
    function getModrewrite() {
        return $this->modrewrite;
    }
    /**
     * получаем позицию
     * @return null
     */
    function getPos() {
        return $this->pos;
    }
    /**
     * получаем сокрытие/отображение
     * @return null
     */
    function getHide() {
        return $this->hide;
    }
    /**
     * получаем изображение
     * @return null
     */
    function getUrlpict() {
        return $this->urlpict;
    }
    /**
     * получаем название основного изображение
     * @return null
     */
    function getAlt() {
        return $this->alt;
    }
    /**
     * получаем изображение услуги
     * @return null
     */
    function getRoundedFlag() {
        return $this->roundedFlag;
    }
    /**
     * получаем название для услуги
     * @return null
     */
    function getTitleFlag() {
        return $this->titleFlag;
    }
    /**
     * получаем название изображения услуги
     * @return null
     */
    function getAltFlag() {
        return $this->altFlag;
    }
    /**
     * Получаем родительский id
     * @return null
     */
    function getIdParent() {
        return $this->idParent;
    }
}
?>