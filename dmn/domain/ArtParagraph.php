<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 30/07/14
 * Time: 13:40
 */

namespace dmn\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/ArtArtIdentityObject.php" );
require_once( "dmn/mapper/ArtArtUpDownFactory.php" );
//require_once( "dmn/domain/ArtArtPosition.php" );
require_once( "dmn/base/Registry.php" );

class ArtParagraph extends DomainObject {
    private $name;
    private $type;
    private $align;
    private $hide;
    private $pos;
    private $idPosition;
    private $idCatalog;

    /**
     * Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $type
     * @param null $align
     * @param string $hide
     * @param null $pos
     * @param null $idPosition
     * @param null $idCatalog
     */
    function __construct( $id=null,
                          $name=null,
                          $type=null,
                          $align=null,
                          $hide='hide',
                          $pos=null,
                          $idPosition=null,
                          $idCatalog=null ) {

        $this->name         = $name;
        $this->type         = $type;
        $this->align        = $align;
        $this->hide         = $hide;
        $this->pos          = $pos;
        $this->idPosition   = $idPosition;
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
        $catalogIdobj = new \dmn\mapper\ArtParagraphIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
//        echo "<tt><pre>".print_r($catalogIdobj, true)."</pre></tt>";
        return $finder->find( $catalogIdobj ); // из DomainObjectAssembler возвращаем Коллекцию с итератором
    }

    /**
     * Метод для поиска
     * @param $id - id параграфа
     * @param $idc - id каталога
     * @param $idp - id позиции
     * @return mixed
     */
    static function find( $id, $idc, $idp=null ) {
        $finder = self::getFinder( __CLASS__ );
        if( ! is_null( $idp ) ) {
            $idobj = new \dmn\mapper\ArtParagraphIdentityObject( 'id_paragraph' );
            return $finder->findOne( $idobj->eq( $id )->field( 'id_catalog' )->eq( $idc )->field( 'id_position' )->eq( $idp ) );
        } else {
            $idobj = new \dmn\mapper\ArtParagraphIdentityObject( 'id_position' );
            return $finder->find( $idobj->eq( $id )->field( 'id_catalog' )->eq( $idc ) );
        }
    }


    /**
     * Метод для смены,
     * сокрытия или отображения позиции в блоке новостей
     * @param $id - id новости
     * @param $action - направление перемещения блока
     */
    static function position( $id, $action ) {
        $result = array();
        $finder = self::getFinder( __CLASS__ );
        $curobj = self::getIdentityObject( __CLASS__ );
        $prevobj = self::getIdentityObject( __CLASS__ );
        switch( $action ) {
            case 'up': // движение позиции вверх
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_paragraph' )->eq( $id ), '' )->getPos();
                $tmp = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' );
//                echo "<tt><pre>".print_r( $tmp, true)."</pre></tt>";
                if( !empty( $tmp ) ) { // если предыдущая позиция существует
                    // получаем предыдущую позицию относительно целевой строки в БД
                    $result['previous'] = $finder->upDownSelect( $prevobj->field('pos')->lt( $result['current']), ' ORDER BY pos DESC ' )->getPos();
                    // меняем позицию местами
                    $finder->upDownUpdate( $result, $action );
                }
                break;
            case 'down': // движение позиции вниз
                // получаем текущую позицию целевой строки в БД
                $result['current'] = $finder->upDownSelect( $curobj->field( 'id_paragraph' )->eq( $id ), '' )->getPos();
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
                $idobj = new \dmn\mapper\ArtParagraphIdentityObject( 'id_paragraph' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'hide' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;

            case 'hide': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\ArtParagraphIdentityObject( 'id_paragraph' );
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
     * @param $idp
     * @return mixed
     */
    static function findMaxPos( $id, $idp ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtParagraphIdentityObject( 'id_catalog' );
        return $finder->findMaxPos( $idobj->eq( $id )->field('id_position')->eq( $idp ) );
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
     * Метод для рекурсивного удаления подкаталогов и позиций
     * заданного каталога
     * @param $idc - id каталога
     * @param $idp - id параграфа
     */
    static function delete( $idc, $idp ) {
        // находим каталог, включая родительский
        // с которого началось удаление и при рекурсивном вызове
        // будем находить каждый следующий каталог
//        $catParent = \dmn\domain\ArtParagraph::find( $idc, $idp );
//        if( is_object( $catParent ) ) {
//
//            $roundedFlag = $catParent->getRoundedFlag();
//            if( ! empty( $roundedFlag ) ) { // если поле не пустое
//                // путь до большого изображения
//                $path_rounded = str_replace( "//", "/","imei_service/view/".$catParent->getRoundedFlag() );
//                if( file_exists( $path_rounded ) ) { // если большое изображение существует
////                    print $path_rounded;
//                    @unlink( $path_rounded ); // удаляем
//                }
//            }
//            $countryFlag = $catParent->getUrlpict();
//            if( ! empty( $countryFlag ) ) { // если поле не пустое
//                $path_country = str_replace( "//", "/","imei_service/view/".$catParent->getUrlpict() ); // путь до малого изображения
//                if( file_exists( $path_country ) ) { // если малое изображение существует
////                    print $path_country;
//                    @unlink( $path_country ); // удаляем
//                }
//            }
//
//            // ставим каталог в очередь на удаление
//            $catParent->markDeleted();
//            // по id_catalog каталога находим все его позиции
//            $posParent = \dmn\domain\ArtParagraphPosition::findAllPosition( $catParent->getId() );
//            if( is_object( $posParent ) ) {
//                // проходим по ним в цикле
//                foreach ( $posParent as $pos ) {
////                    echo "<tt><pre> 1 - ".print_r($pos, true)."</pre></tt>";
//                    // и добавляем позиции в очередь на удаление
//                    $pos->markDeleted();
//                }
//                // находим у заданного каталога его подкаталоги по его id_catalog
//                $catChild = \dmn\domain\ArtParagraph::findParent( $catParent->getId() );
//                // проходим в цикле по полученным подкаталогам
//                foreach ( $catChild as $cat) {
//                    // и вызываем метод рекурсивно
//                    self::delete( $cat->getId(), $cat->getIdPositio() );
//                }
//            }
//        }
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
     * устанавливаем тип параграфа
     * @param $type_s
     */
    function setType( $type_s ) {
        $this->type = $type_s;
        $this->markDirty();
    }
    /**
     * устанавливаем выравнивание
     * @param $align_s
     */
    function setAlign( $align_s ) {
        $this->align = $align_s;
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
     * устанавливаем id позиции
     * @param $idPosition_s
     */
    function setIdPosition( $idPosition_s ) {
        $this->idPosition = $idPosition_s;
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
     * получаем имя
     * @return null
     */
    function getName() {
        return $this->name;
    }
    /**
     * получаем тип
     * @return null
     */
    function getType() {
        return $this->type;
    }
    /**
     * получаем выравнивание
     * @return null
     */
    function getAlign() {
        return $this->align;
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
     * Получаем id позиции
     * @return null
     */
    function getIdPosition() {
        return $this->idPosition;
    }
    /**
     * Получаем id каталога
     * @return null
     */
    function getIdCatalog() {
        return $this->idCatalog;
    }
}
?>