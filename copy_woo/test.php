<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 22/01/14
 * Time: 16:54
 * To change this template use File | Settings | File Templates.
 */






try {

    header('Content-type: text/html; charset=utf-8');
    if( empty( $_POST) ) {
        echo "<form method=POST enctype='multipart/form-data'>
            <input type='file' name=\"userfile\">
            <input type='submit' name='send' value='send'>
        </form>";
    } else {

        $allowed_filetypes = array('.jpg','.gif','.bmp','.png','.exe','.psd','.doc','.txt','.zip','.rar','.avi','.apk');

        $max_filesize = 10052400;

        $upload_path = './files/';

        $filename = $_FILES['userfile']['name'];
        $file_size = $_FILES['userfile']['size'];

        function measure( $file, $zero_limit=2 ) {
            $extension = ['byte','Kb','Mb','Gb','Tb'];
            $size = floor( ( strlen( $file ) - 1 ) / 3 );
            return sprintf( "%.{$zero_limit}f", $file / pow(1024, $size))." ".$extension[$size];
        }


        print measure( 101 )."<br />";


        $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1);

        if(!in_array($ext,$allowed_filetypes))
            die('Данный тип файла не поддерживается.');

        if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
            die('Фаил слишком большой.');


        if(!is_writable($upload_path))
            die('Невозможно загрузить фаил в папку. Установите права доступа - 777.');

        if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))
        {
            echo 'Ваш фаил успешно загружен <a href="' . $upload_path . $filename . '" >Просмотреть файл - размер='.$file_size.'</a>';

            echo "<form method=POST enctype='multipart/form-data'>
                    <fieldset>
                        <legend>Форма загрузки файлов</legend>
                            <label>Файл</label>
                                <input type='file' name=\"userfile\"><br />
                            <label>Отправить</label>
                                <input type='submit' name='send' value='send'>
                    </fieldset>
                  </form>";
        }
        else
        {
            echo 'При загрузке возникли ошибки. Попробуйте ещё раз.';

        }

    }

} catch (PDOException $e ) {
    print $e->getMessage();
} catch ( Exception $e ) {
    print $e->getMessage();
}
?>