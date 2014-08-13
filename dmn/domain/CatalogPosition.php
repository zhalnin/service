<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 26/07/14
 * Time: 18:56
 */

namespace dmn\domain;

error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/CatalogPositionIdentityObject.php" );
require_once( "dmn/mapper/CatalogPositionUpDownFactory.php" );

require_once( "dmn/base/Registry.php" );

class CatalogPosition extends DomainObject {
    private $operator;
    private $cost;
    private $timeconsume;
    private $compatible;
    private $status;
    private $currency;
    private $hide;
    private $pos;
    private $putdate;
    private $idCatalog;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $operator
     * @param null $cost
     * @param null $timeconsume
     * @param null $compatible
     * @param null $status
     * @param null $currency
     * @param string $hide
     * @param null $pos
     * @param null $putdate
     * @param null $idCatalog
     */
    function __construct( $id=null,
                          $operator=null,
                          $cost=null,
                          $timeconsume=null,
                          $compatible=null,
                          $status=null,
                          $currency=null,
                          $hide='hide',
                          $pos=null,
                          $putdate=null,
                          $idCatalog=null ) {

        $this->operator     = $operator;
        $this->cost         = $cost;
        $this->timeconsume  = $timeconsume;
        $this->compatible   = $compatible;
        $this->status       = $status;
        $this->currency     = $currency;
        $this->hide         = $hide;
        $this->pos          = $pos;
        $this->putdate      = $putdate;
        $this->idCatalog    = $idCatalog;


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
        $catalogPositionIdobj = new \dmn\mapper\CatalogPositionIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $catalogPositionIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id
     * @param null $idp
     * @return mixed
     */
    static function find( $id, $idp=null ) {
        $finder = self::getFinder( __CLASS__ );
        if (is_null($idp)) {
            $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_catalog' );
            return $finder->findOne( $idobj->eq( $id ) );
        } else {
            $idobj = new \dmn\mapper\CatalogPositionIdentityObject('id_catalog');
            return $finder->findOne( $idobj->eq( $id )->field('id_position')->eq($idp));
        }
    }

    /**
     * Метод для поиска
     * @param $id
     * @return mixed
     */
    static function findDetail( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_position' );
        return $finder->findOne( $idobj->eq( $id ) );
    }

    /**
     * Метод для поиска всех позиций
     * @param $id
     * @return mixed
     */
    static function findAllPosition( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_catalog' );
        return $finder->find( $idobj->eq( $id ) );
    }


    /**
     * Метод для смены,
     * сокрытия или отображения позиции в блоке каталога
     * @param $id
     * @param $action
     */
    static function position( $id, $action ) {
        $result = array();
        $finder = self::getFinder( __CLASS__ );
        $curobj = self::getIdentityObject( __CLASS__ );
        $prevobj = self::getIdentityObject( __CLASS__ );
        switch( $action ) {
            case 'up': // движение позиции вверх
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_position' )->eq( $id ), '' )->getPos();
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
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_position' )->eq( $id ), '' )->getPos();
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
                $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_position' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'hide' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;

            case 'hide': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_position' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'show' ));
//                echo "<tt><pre>". print_r($obj, TRUE) . "</pre></tt>";
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;
        }
    }

    /**
     * Метод для получения максимальной позиции
     * @param $id
     * @return mixed
     */
    static function findMaxPos( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_catalog' );
        return $finder->findMaxPos( $idobj->eq( $id ) );
    }

    /**
     * Метод для получения настроек параметров изображений
     * @return mixed
     */
    static function findPhotoSetting() {
        $finder = self::getFinder( __CLASS__ );
        return $finder->findPhotoSetting();
    }

    /**
     * Метод для получения количество позиций по id каталога
     * @param $id
     * @return mixed
     */
    static function findCountPos( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\CatalogPositionIdentityObject( 'id_catalog' );
        return $finder->findCountPos( $idobj->eq( $id ) );
    }


    /**
     * устанавливаем название
     * @param $operator_s
     */
    function setOperator( $operator_s ) {
        $this->operator = $operator_s;
        $this->markDirty();
    }
    /**
     * устанавливаем стоимость
     * @param $cost_s
     */
    function setCost( $cost_s ) {
        $this->cost = $cost_s;
        $this->markDirty();
    }
    /**
     * устанавливаем время выполнения
     * @param $timeconsume_s
     */
    function setTimeconsume( $timeconsume_s ) {
        $this->timeconsume = $timeconsume_s;
        $this->markDirty();
    }
    /**
     * устанавливаем совместимость
     * @param $compatible_s
     */
    function setCompatible( $compatible_s ) {
        $this->compatible = $compatible_s;
        $this->markDirty();
    }
    /**
     * устанавливаем статус
     * @param $status_s
     */
    function setStatus( $status_s ) {
        $this->status = $status_s;
        $this->markDirty();
    }
    /**
     * устанавливаем валюту
     * @param $currency_s
     */
    function setCurrency( $currency_s ) {
        $this->currency = $currency_s;
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
     * устанавливаем позицию
     * @param $pos_s
     */
    function setPos( $pos_s ) {
        $this->pos = $pos_s;
        $this->markDirty();
    }
    /**
     * устанавливаем время создания
     * @param $putdate_s
     */
    function setPutdate( $putdate_s ) {
        $this->putdate = $putdate_s;
        $this->markDirty();
    }
    /**
     * устанавливаем id каталога
     * @param $idCatalog_s
     */
    function setIdCatalog( $idCatalog_s ) {
        $this->idCatalog = $idCatalog_s;
        $this->markDirty();
    }


    /**
     * получаем название
     * @return null
     */
    function getOperator() {
        return $this->operator;
    }
    /**
     * получаем стоимость
     * @return null
     */
    function getCost() {
        return $this->cost;
    }
    /**
     * получаем время выполнения
     * @return null
     */
    function getTimeconsume() {
        return $this->timeconsume;
    }
    /**
     * получаем совместимость
     * @return null
     */
    function getCompatible() {
        return $this->compatible;
    }
    /**
     * получаем статус
     * @return null
     */
    function getStatus() {
        return $this->status;
    }
    /**
     * получаем валюту
     * @return null
     */
    function getCurrency() {
        return $this->currency;
    }
    /**
     * получаем сокрытие/отображение
     * @return null
     */
    function getHide() {
        return $this->hide;
    }
    /**
     * получаем позицию
     * @return null
     */
    function getPos() {
        return $this->pos;
    }
    /**
     * получаем время создания
     * @return null
     */
    function getPutdate() {
        return $this->putdate;
    }
    /**
     * получаем id каталога
     * @return null
     */
    function getIdCatalog() {
        return $this->idCatalog;
    }
}
?>