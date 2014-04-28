<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 19/04/14
 * Time: 13:10
 * To change this template use File | Settings | File Templates.
 */
ini_set('memory_limit', '-1');

//if( ! empty( $_FILES['filename']['name'] ) ) {
if( isset ( $_FILES['filename']['name'] ) ) {
    if( $_FILES['filename']['size'] < ( 1024 * 1024 * 2 ) ) {
        $width = 450;
        $height = 600;
        $dir = "files/guestbook";
        $path = resizeimg( $_FILES['filename']['tmp_name'], $_FILES['filename']['name'], $width, $height, $dir );
        $server_path = nameServer();
        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadSuccess("'.$server_path.$path.'"); </script>';
    } else {
        $error = 'error';
        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadSuccess("'.$error.'"); </script>';
//        echo "Размер файла не должен превышать 2.0 Mb";
    }
}

if( isset ( $_POST['url'] ) ) {
    $path = htmlspecialchars( stripslashes( $_POST['url'] ) );
    if( preg_match('|^https?://|', $path ) == 1 ) {
        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadUrlSuccess("'.$path.'"); </script>';
    } else {
        $error = 'error';
        echo '<script type="text/javascript" > var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadUrlSuccess("'.$error.'"); </script>';

    }
}

if( isset( $_POST['image'] ) ) {
    $image = htmlspecialchars( stripslashes( $_POST['image'] ) );
    $path = resizeimg2( $image, 450, 600 );
    if( preg_match('|^https?://|i', $image ) == 1 ) {
        $info = pathinfo($image);
        if( in_array( strtolower( $info['extension'] ), ['jpg','gif','png','jpeg'] ) ) {
            $height = $path['height'];
            $width = $path['width'];
            echo '<script type="text/javascript"> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess("'.$image.'","'.$width.'","'.$height.'"); </script>';
        } else {
            $error = 'error';
            echo '<script type="text/javascript"> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess("'.$error.'"); </script>';
        }
    } else {
        $error = 'error';
        echo '<script type="text/javascript"> var wysiwyg = new parent.WysiwygObject(); wysiwyg.uploadInsertImageSuccess("'.$error.'"); </script>';
    }
}


/**
 * Для создания тега img: <img src=.. width=.. height=.. />
 * @param $big - проверяемое изображение
 * @param $width - ширина
 * @param $height - высота
 * @return array - массив из высоты и ширины изображения
 */
function resizeimg2( $big, $width, $height ){
    // Определяем коэффициент сжатия
    // генерируемого изображения
    $ratio = $width / $height;
    // Получаем размеры исходного изображения 0-ширина;1-высота;2-;3-width="" height=""; mime-image/jpeg
    $size_img = getimagesize($big);
    list($width_src, $height_src) = getimagesize($big);
    // Если размеры меньше, то масштабирования не нужно
    if( ( $width_src < $width ) && ( $height_src < $height ) ){
        $arr = array('height'=>$height, 'width'=>$width);

        return $arr;
    }
    // Получаем коэффициент сжатия исходного изображения
    $src_ratio = $width_src/$height_src;
    // Вычисляем размеры уменьшенной копии, чтобы при
    // масштабировании сохранились пропорции исходного изображения
    if($ratio < $src_ratio) {
        $height = $width / $src_ratio;
    } else {
        $width = $height * $src_ratio;
    }

    $arr = array('height'=>$height, 'width'=>$width);

    return $arr;
}





function nameServer() {
    $name = $_SERVER['SERVER_NAME'];
    if( stripos( $name, 'www' ) === 0 ) {
        $name = substr_replace( $name, '', 0, 4 );
    }

    // Для дебаггинга
    if( ! empty( $_SERVER['SERVER_PORT'] ) ) {
        $port = ':'.$_SERVER['SERVER_PORT'];
    } else {
        $port = '';
    }
    $path = $_SERVER['PHP_SELF'];
    preg_match('|(.*)(?:\/.*\.php)|i', $path, $ar);
    return "http://".$name.$port.$ar[1]."/";
    // конец


//    return $name;
}

/**
 * Проверяем изображение на соответствие размерам
 * @param $big - $_FILES['<name>']['tmp_name'] - временный файл
 * @param $small - $_FILES['<name>']['name'] - актуальный файл
 * @param $width - требуемая ширина
 * @param $height - требуемая высота
 * * @param $dir - директория для сохранения
 * @return bool
 */
function resizeimg( $big, $small, $width, $height, $dir ){
    // Определяем коэффициент сжатия
    // генерируемого изображения
    $ratio = $width / $height;
    // Получаем размеры исходного изображения 0-ширина;1-высота;2-;3-width="" height=""; mime-image/jpeg
    $size_img = getimagesize($big);
    list($width_src, $height_src) = getimagesize($big);
    // Если размеры меньше, то масштабирования не нужно
    if( ( $width_src < $width ) && ( $height_src < $height ) ){
        $path = renameImg( $small, $dir );
        if (move_uploaded_file( $big, $path ) ) {
            @unlink( $big );
            return $path;
        }
    }
    // Получаем коэффициент сжатия исходного изображения
    $src_ratio = $width_src/$height_src;
    // Вычисляем размеры уменьшенной копии, чтобы при
    // масштабировании сохранились пропорции исходного изображения
    if($ratio < $src_ratio) {
        $height = $width / $src_ratio;
    } else {
        $width = $height * $src_ratio;
    }
    // Создаем пустое изображение по заданным размерам
    $dest_img = imagecreatetruecolor( $width, $height );
    $white = imagecolorallocate( $dest_img, 255,255,255 );
//    imagefilledrectangle ($dest_img, 0, 0, 150, 30, $white);
    if( $size_img[2] == 2 ) $src_img = imagecreatefromjpeg( $big );
    else if( $size_img[2] == 1 ) $src_img = imagecreatefromgif( $big );
    else if( $size_img[2] == 3 ){
//        $src_img = imagecreatefrompng($big);
        $src_img = LoadPNG( $big );
    }

    // Масштабируем изображение функцией imagecopyresampled()
    imagecopyresampled( $dest_img, // уменьшенная копия
                        $src_img,  // исходное изображение
                        0,
                        0,
                        0,
                        0,
                        $width,     // ширина уменьшенной копии
                        $height,    // высота уменьшенной копии
                        $width_src, // ширина исходного изображения
                        $height_src // высота исходного изображения
    );
    // Сохраняем уменьшенную копию в файл
    if( $size_img[2]==2 ) {
        $path = renameImg( $small, $dir );
        imagejpeg( $dest_img, $path );
    }
    elseif( $size_img[2]==1 ) {
        $path = renameImg( $small, $dir );
        imagegif( $dest_img, $path );
    }
    elseif( $size_img[2]==3 ) {
      $path = renameImg( $small, $dir );
      imagepng( $dest_img, $path );
    }
    // Очишаем память от созданных изображений
    imagedestroy( $dest_img );
    imagedestroy( $src_img );
    return $path;
}

/**
 * Меняем название файла: заменяем пробелы нижним подчеркиванием,
 * добавляем время в секундах и создаем полный путь для сохранения
 * @param $name - $_FILES['<name>']['name']
 * @param $dir - директория для сохранения
 * @return mixed - путь к файлу с измененным названием
 */
function renameImg( $name, $dir ) {
//    $dir = pathOnServer( $dir );
    $path_parts = pathinfo( $name );  // получаем массив с метаданными изображения
    $ext = ".".$path_parts['extension'];  // получаем точку с расширением, к примеру: ".png"
    $path = basename( $name, $ext ); // получаем имя файла без расширения
    $path = preg_replace(  "| |","_", $path ); // заменяем пробелы нижним подчеркиванием
    $path .= $ext; // добавляем в конец расширение
    $path = str_replace( "//","/", $dir."/".time()."_".$path ); // заменяем двойной слеш одинарным и собираем весь путь
    return $path;
}

/**
 * Для создания файла с расширением png
 * @param $imgname
 * @return resource
 */
function LoadPNG( $imgname ) {
    $im = imagecreatefrompng ( $imgname ); // попытка открыть
    if ( !$im ) { // проверить, удачно ли
        $im= imagecreate ( 150, 30 ); // создать пустое изображение
        $bgc = imagecolorallocate ( $im, 255, 255, 255 );
        $tc= imagecolorallocate ( $im, 0, 0, 0 );
        imagefilledrectangle ( $im, 0, 0, 150, 30, $bgc );
        imagestring ( $im, 1, 5, 5, "Error loading $imgname", $tc ); // вывести errmsg
    }
    return $im;
}


?>