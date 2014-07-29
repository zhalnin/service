<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 29/07/14
 * Time: 22:11
 */

namespace dmn\domain;
error_reporting( E_ALL & ~E_NOTICE );

require_once( "dmn/domain/DomainObject.php" );
require_once( "dmn/mapper/ArtArtIdentityObject.php" );
require_once( "dmn/mapper/ArtArtUpDownFactory.php" );
//require_once( "dmn/domain/ArtArtPosition.php" );
require_once( "dmn/base/Registry.php" );

class ArtArt extends DomainObject {
    private $name;
    private $description;
    private $url;
    private $keywords;
    private $modrewrite;
    private $pos;
    private $hide;
    private $idCatalog;

    /**
     *  Поля из БД - сохраняем из в переменные
     * @param null $id
     * @param null $name
     * @param null $description;
     * @param null $url
     * @param null $keywords
     * @param null $modrewrite
     * @param null $pos
     * @param string $hide
     * @param null $idParent
     */
    function __construct( $id=null,
                          $name=null,
                          $description=null,
                          $url=null,
                          $keywords=null,
                          $modrewrite=null,
                          $pos=null,
                          $hide='hide',
                          $idCatalog=null ) {

        $this->name         = $name;
        $this->description  = $description;
        $this->url          = $url;
        $this->keywords     = $keywords;
        $this->modrewrite   = $modrewrite;
        $this->pos          = $pos;
        $this->hide         = $hide;
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
        $catalogIdobj = new \dmn\mapper\ArtArtIdentityObject(); // здесь без фабрики создаем экземпляр, чтобы передать имя поля для класса IdentityObect
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
        $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_position' );
        return $finder->findOne( $idobj->eq( $id ) );
    }

    /**
     * Метод для поиска родительского каталога
     * @param $id
     * @return mixed
     */
    static function findParent( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_catalog' );
        return $finder->find( $idobj->eq( $id ) );
    }

    /**
     * Метод для поиска родительского каталога
     * @param $id
     * @return mixed
     */
    static function findCatalog( $id ) {
        $finder = self::getFinder( __CLASS__ );
        $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_position' );
        return $finder->find( $idobj->eq( $id ) );
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
                $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_position' );
                $obj = $finder->findOne( $idobj->eq( $id )->field( 'hide' )->eq( 'hide' ));
                // обновляем значение поля (в контроллере будет выполнена команда UPDATE)
                $obj->setHide( $action );
                break;

            case 'hide': // сокрытие позиции
                // создаем условный оператор для запроса в БД
                $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_position' );
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
        $idobj = new \dmn\mapper\ArtArtIdentityObject( 'id_catalog' );
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
     * Метод для рекурсивного удаления подкаталогов и позиций
     * заданного каталога
     * @param $idc - id каталога
     */
    static function delete( $idc ) {
        // находим каталог, включая родительский
        // с которого началось удаление и при рекурсивном вызове
        // будем находить каждый следующий каталог
        $catParent = \dmn\domain\ArtArt::find( $idc );
        if( is_object( $catParent ) ) {

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

            // ставим каталог в очередь на удаление
            $catParent->markDeleted();
            // по id_catalog каталога находим все его позиции
            $posParent = \dmn\domain\ArtArtPosition::findAllPosition( $catParent->getId() );
            if( is_object( $posParent ) ) {
                // проходим по ним в цикле
                foreach ( $posParent as $pos ) {
//                    echo "<tt><pre> 1 - ".print_r($pos, true)."</pre></tt>";
                    // и добавляем позиции в очередь на удаление
                    $pos->markDeleted();
                }
                // находим у заданного каталога его подкаталоги по его id_catalog
                $catChild = \dmn\domain\ArtArt::findParent( $catParent->getId() );
                // проходим в цикле по полученным подкаталогам
                foreach ( $catChild as $cat) {
                    // и вызываем метод рекурсивно
                    self::delete( $cat->getId() );
                }
            }
        }
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
     * устанавливаем описание
     * @param $description_s
     */
    function setDescription( $description_s ) {
        $this->description = $description_s;
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
     * устанавливаем ключевые слова
     * @param $keywords_s
     */
    function setKeywords( $keywords_s ) {
        $this->keywords = $keywords_s;
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
     * устанавливаем родительский id
     * @param $idParent_s
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
     * получаем описание
     * @return null
     */
    function getDescription() {
        return $this->description;
    }
    /**
     * получаем ссылку
     * @return null
     */
    function getUrl() {
        return $this->url;
    }
    /**
     * получаем ключевые слова
     * @return null
     */
    function getKeyWords() {
        return $this->keywords;
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
     * Получаем родительский id
     * @return null
     */
    function getIdCatalog() {
        return $this->idCatalog;
    }
}
?>