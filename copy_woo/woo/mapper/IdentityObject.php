<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 16/03/14
 * Time: 17:31
 * To change this template use File | Settings | File Templates.
 */



namespace woo\mapper;

class Field {
    protected $name=null;
    protected $operator=null;
    protected $comps=array();
    protected $incomplete=false;

    // Устанавливаем Имя поля, например, Age
    function __construct( $name ) {
        $this->name = $name;
    }

    // Добавляем оператор и значение для проверки
    // (> 40, например) и помещает его в свойство $comps
    function addTest( $operator, $value ) {
        $this->comps[] = array( 'name' => $this->name, 'operator' => $operator, 'value' => $value );
    }

    // $comps - это массив, поэтому мы сможем срванить одно поле с другим
    // несколькими способами
    function getComps() { return $this->comps; }

    // Если массив $comps не содержит элементов, значит, у нас есть
    // данные для сравнения и это поле не готово для использования
    // в запросе
    function isIncomplete() {
//        print "isIncomplete()";
        return empty( $this->comps );
    }
}





class IdentityObject {
    protected $currentfield = null;
    protected $fields        = array();
    private $and            = null;
    private $enforce        = array();

    // Конструктор identity object может запускаться
    // без параметров или с именем поля
    function __construct( $field=null, array $enforce=null ){
        if( ! is_null( $enforce ) ) {
            $this->enforce = $enforce;
        }
        if( ! is_null( $field ) ) {
            $this->field( $field );
        }
    }

    // Имена полей, на которые наложено это ограничение
    function getObjectFields() {
        return $this->enforce;
    }

    // Вводим новое поле
    // Генерируется ошибка, если текущее поле неполное
    // (т.е. age, а не age > 40)
    // Этот метод возвращает ссылку на текущий объект
    // и тем самым разрешает свободный синтаксис
    function field( $fieldname ) {
        // ( если не пустой this->fields[] ) или ( пустой this->coms[] ) в классе Field
        if( ! $this->isVoid() && $this->currentfield->isIncomplete() ) {
            throw new \Exception( "Неполное имя");
        }
        $this->enforceField( $fieldname );
        if( isset( $this->fields[$fieldname] ) ) {
//            print "isset";
            $this->currentfield = $this->fields[$fieldname];
        } else {
//            print "not isset";
            $this->currentfield = new Field( $fieldname );
            $this->fields[$fieldname] = $this->currentfield;
        }
        return $this;
    }

    // Есть ли уже поля у identity object
    function isVoid() {
//        print empty( $this->fields );
        return empty( $this->fields );
    }

    // Заданное имя допустимо?
    function enforceField( $fieldname ) {
        if( ! in_array( $fieldname, $this->enforce ) && ! empty( $this->enforce ) ) {
            $forcelist = implode( ', ', $this->enforce );
            throw new \Exception("{$fieldname} не является корректным полем {$forcelist}");
        }
//        if( ! in_array( $fieldname, $this->enforce ) ){
//            print $fieldname;
//        }
//        if( ! empty( $this->enforce ) ) {
//            print "kdjfkdsjf";
//        }
    }

//    function add( $fieldname ) {
//        if( ! $this->isVoid() && $this->currentfield->isIncomplete() ) {
//            throw new \Exception("Поле не полное");
//        }
//        return $this->field( $fieldname );
//    }

    // Добавим оператор равенства к текущему полю
    // т.е. 'age' становится age=40
    // Возвращаем ссылку на текущий объект ( с помощью operator() )
    function eq( $value ) {
        return $this->operator( "=", $value );
    }

    // Меньше, чем
    function lt( $value ) {
        return $this->operator( "<", $value );
    }

    // Больше, чем
    function gt( $value ) {
        return $this->operator( ">", $value );
    }

    // Выполняет работу для методов operator
    // Получает текущее поле и добавляет значение оператора
    // и результаты проверки к нему
    private function operator( $symbol, $value ) {
        if( $this->isVoid() ) {
            throw new \Exception("Поле не определено");
        }
        $this->currentfield->addTest( $symbol, $value );
        return $this;
    }

    // Возвращает все сравнения, созданные до сих пор в ассоциативном массиве
    function getComps() {
        $ret = array();
        foreach( $this->fields as $key=>$field ) {
            $ret = array_merge( $ret, $field->getComps() );
        }
        return $ret;
    }

    function __toString() {
        $ret = array();
        foreach ( $this->getComps() as $compdata ) {
            $ret[] = "{$compdata['name']} {$compdata['operator']} {$compdata['value']}";
        }
        return implode( " AND ", $ret );
    }

}
?>