<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 16/06/14
 * Time: 20:21
 */

namespace dmn\mapper;

/**
 * Class Field
 * @package dmn\mapper
 */
class Field {
    protected $name = null;
    protected $operator = null;
    protected $comps = array();
    protected $incomplete = false;

    /**
     * Вызывается при создании экземпляра в классе IdentityOjbect->field($fieldname)
     * @param $name - имя поля для условного оператора
     */
    function __construct( $name ) {
        $this->name = $name;
    }

    /**
     * Вызывается из метода operator() класса IdentityObject
     * @param $operator - оператор сравнения
     * @param $value - поле для сравнения с $this->name
     */
    function addTest( $operator, $value ) {
        $this->comps[] = array( 'name'      => $this->name,
            'operator'  => $operator,
            'value'     => $value);
    }


    function getComps() {
        return $this->comps;
    }

    /**
     * Проверка, пустой ли массив comps[]
     * @return bool
     */
    function isIncomplete() {
        return empty( $this->comps );
    }
}


class IdentityObject {
    protected $currentfield = null;
    protected $fields = array();
    private $and = null;
    private $enforce = array();

    /**
     * Получаем параметры из дочернего класса
     * @param null $field - имя поля для условного оператора
     * @param array $enforce - массив полей БД
     */
    function __construct( $field=null, array $enforce=null ) {
        if( ! is_null( $enforce ) ) { // если присутствует массив полей
            $this->enforce = $enforce; // сохраняем в переменную
        }
        if( ! is_null( $field ) ) { // если присутствует имя поля
            $this->field( $field ); // вызываем метод
        }
    }

    function getObjectFields() {
        return $this->enforce;
    }

    /**
     * Вызываем из конструктора
     * @param $fieldname - имя поля для условного оператора
     * @return $this
     * @throws \dmn\base\AppException
     */
    function field( $fieldname ) {
        if( ! $this->isVoid() && $this->currentfield->isIncomplete() ) { // если $this->fields не пустой и
            throw new \dmn\base\AppException( "Incomplete field" );
        }
        $this->enforceField( $fieldname ); // проверка на вхождение поля в массив полей для запроса
        if( isset( $this->fields[$fieldname] ) ) { // если массив fields[] уже инициализирован классом Field
            $this->currentfield = $this->fields[$fieldname]; // то $this->currentfield инициализируем этим полем
        } else { // если не было
            $this->currentfield = new \dmn\mapper\Field( $fieldname ); // то $this->currentfield инициализируем экземпляром класса Field с параметром этого поля
            $this->fields[$fieldname] = $this->currentfield; // и тогда fields[] инициализируем объектом Field
        }
        return $this; // возвращаем объект (удобно, можно вызывать другие методы для условного оператора без создания экземпляра)
    }

    /**
     * Проверяем, пустой массив или нет
     * @return bool
     */
    function isVoid() {
        return empty( $this->fields );
    }

    /**
     * Проверяем, чтобы переданное поле было в массиве полей для запроса к БД
     * при условии, что сам массив не пустой
     * @param $fieldname - имя поля для условного оператора
     * @throws \dmn\base\AppException
     */
    function enforceField( $fieldname ) {
        if( ! in_array( $fieldname, $this->enforce ) && ! empty( $this->enforce ) ) { // если поля для условного оператора нет в массиве полей при запросе к БД и собственно этот массив полей не пустой
            $forcelist = implode( ', ', $this->enforce ); // разбиваем массив полей запроса к БД запятыми в массив
            throw new \dmn\base\AppException( "{$fieldname} not a legal field ($forcelist)" );
        }
    }

    function add( $fieldname ) {
        if( ! $this->isVoid() && $this->currentfield->isIncomplete() ) {
            throw new \dmn\base\AppException( "Incomplete field" );
        }
        return $this->field( $fieldname );
    }

    /**
     * Метод "="
     * @param $value - поле для сравнения
     * @return $this
     */
    function eq( $value ) {
        return $this->operator( "=", $value );
    }

    /**
     * Метод "<"
     * @param $value - поле для сравнения
     * @return $this
     */
    function lt( $value ) {
        return $this->operator( "<", $value );
    }

    /**
     * Метод ">"
     * @param $value - поле для сравнения
     * @return $this
     */
    function gt( $value ) {
        return $this->operator( ">", $value );
    }


    /**
     * Вызываем из методов операторов сравнения
     * @param $symbol - знак сравнения
     * @param $value - поле для сравнения
     * @return $this
     * @throws \dmn\base\AppException
     */
    private function operator( $symbol, $value ) {
        if( $this->isVoid() ) { // если $fields[] пустой (значит в конструктор не передавали поле, с которым будем сравнивать $value
            throw new \dmn\base\AppException( "No object field defined" );
        }
        $this->currentfield->addTest( $symbol, $value ); // в классе Field вызываем метод addTest и получаем массив comps[] = [name, знак сравнения, value]
        return $this;
    }

    /**
     * Проходит по массиву fields['здесь поле для сравнения'], в котором $this->currentfield = Field($fieldname) и, если были условные операторы через метод addTest()
     * @return array
     */
    function getComps() {
        $ret = array();
        foreach ( $this->fields as $key => $field ) { // ищем поле и его массив comps[]
//            echo "<tt><pre>".print_r($this->fields, true)."</pre></tt>";
            $ret = array_merge( $ret, $field->getComps() ); // к массиву добавляем  условные операторы, к примеру: name=>'hide', operator=>'=', value=>'show'
        }
        return $ret;
    }

    /**
     * Альтернативный способ получения массива условных операторов
     * @return string
     */
    function __toString() {
        $ret = array();
        foreach ( $this->getComps() as $compdata ) {
            $ret[] = "{$compdata['name']} {$compdata['operator']} {$compdata['value']}";
        }
        return implode( " AND ", $ret );
    }
}
?>