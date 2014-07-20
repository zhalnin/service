<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 04/07/14
 * Time: 15:47
 */

namespace dmn\view;

error_reporting(E_ALL & ~E_NOTICE);

// Include parent class
//require_once( "dmn/classes/class.PagerMysqlTwoTables.php" );
//require_once( "dmn/classes/class.Field.php" );
//require_once( "dmn/classes/class.FieldText.php" );
//require_once( "dmn/classes/class.FieldTextarea.php" );
//require_once( "dmn/classes/class.FieldDateTime.php" );
//require_once( "dmn/classes/class.FieldCheckbox.php" );
//require_once( "dmn/classes/class.FieldFile.php" );
//require_once( "dmn/classes/class.FieldHidden.php" );
//require_once( "dmn/classes/class.FieldHiddenInt.php" );
//require_once( "dmn/classes/class.Form.php" );

require_once( "dmn/classes.php" );
// Include exception for error handling
require_once( "dmn/base/Exceptions.php" );


try {

    $name           = new \dmn\classes\FieldText("name",
                                                "Название",
                                                true,
                                                $_POST['name']);
    $preview       = new \dmn\classes\FieldTextarea("preview",
                                                "Превью",
                                                true,
                                                $_POST['preview'],
                                                50,
                                                10,
                                                false);
    $body           = new \dmn\classes\FieldTextarea("body",
                                                "Содержимое",
                                                true,
                                                $_POST['body'],
                                                60,
                                                20,
                                                false);
    $date           = new \dmn\classes\FieldDatetime("date",
                                                "Дата новости",
                                                $_POST['date']);
    $hidedate       = new \dmn\classes\FieldCheckbox("hidedate",
                                                "Отображать дату",
                                                $_POST['hidedate']);
    $urltext        = new \dmn\classes\FieldText("urltext",
                                                "Текст ссылки",
                                                false,
                                                $_POST['urltext']);
    $url            = new \dmn\classes\FieldText("url",
                                                "Ссылка",
                                                false,
                                                $_POST['url']);

    $alt            = new \dmn\classes\FieldText("alt",
                                                "ALT-тег",
                                                false,
                                                $_POST['alt']);

    $filename       = new \dmn\classes\FieldFile("filename",
                                                "Изображение",
                                                false,
                                                $_FILES,
                                                "../../files/news/",
                                                "news_");
    $hide           = new \dmn\classes\FieldCheckbox("hide",
                                                "Отображать",
                                                $_REQUEST['hide']);

    $hidepict       = new \dmn\classes\FieldCheckbox("hidepict",
                                                "Отображать фото",
                                                $_REQUEST['hidepict']);
    $page           = new \dmn\classes\FieldHiddenInt("page",
                                                false,
                                                $_REQUEST['page']);

    $form = new \dmn\classes\Form( array( "name"     => $name,
                                          "preview"  => $preview,
                                          "body"     => $body,
                                          "date"     => $date,
                                          "hidedate" => $hidedate,
                                          "urltext"  => $urltext,
                                          "url"      => $url,
                                          "alt"      => $alt,
                                          "filename" => $filename,
                                          "hide"     => $hide,
                                          "hidepict" => $hidepict,
                                          "page"     => $page ),
                                          "Add" ,
                                          "field" );

    // Выводим HTML-форму
    $form->printForm();

} catch ( \dmn\base\AppException $ex ) {
    echo $ex->getErrorObject();
} catch ( \dmn\base\DBException $ex ) {
    echo $ex->getMessage();
} catch ( \PDOException $ex ) {
    echo $ex->getMessage() . " AND " . $ex->getCode();
}

?>