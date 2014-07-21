<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 21/07/14
 * Time: 20:06
 */
namespace dmn\view\utils;
ini_set( 'memory_limit', -1 );

try {


    function resizeImg($big, $small, $width, $height) {
        // Имя файла с масштабируемой копией
        $big = "$big";
        // Имя файла с уменьшенной копией
        $small = "$small";
        // Определяем коэффициент сжатия
        // генерируемого изображения
        $ratio = $width / $height;
        // Получаем размеры исходного изображения
        $size_img = getimagesize($big);
//        echo "<tt><pre>".print_r( $size_img, true )."</pre></tt>";
        list($width_src, $height_src) = getimagesize($big);
        // Если размеры меньше, то масштабирования не нужно
        if(($width_src < $width) && ($height_src < $height)) {
            copy($big, $small);
            return true;
        }
        // Получаем коэффициент сжатия исходного изображения
        $src_ratio = $width_src/$height_src;

        // Вычисляем размеры уменьшенной копии, чтобы при
        // масштабировании сохранились пропорции исходного изображения
        if($ratio<$src_ratio) {
            $height = $width/$src_ratio;
        } else {
            $width = $height*$src_ratio;
        }

        if($size_img[2] == 2) $res_img = imagecreatefromjpeg($big);
        else if($size_img[2] == 1) $res_img = imagecreatefromgif($big);
        else if($size_img[2] == 3){
            //        $res_img = imagecreatefrompng($big);
            //       $res_img = LoadPNG( $big, $width, $height );
            LoadPNG( $big, $width, $height, $small );
        }

        // Создаем пустое изображение по заданным размерам
        $dest_img = imagecreatetruecolor($width,$height);
        $white = imagecolorallocate($dest_img, 255,255,255);
        imagefilledrectangle ($dest_img, 0, 0, 150, 30, $white);
        // Масштабируем изображение функцией imagecopyresampled()
        // $dest_img - новый ресурс с нужной шириной и высотой
        // $res_img - уменьшенное изображение
        // $width - ширина уменьшенной копии
        // $height - высота уменьшенной копии
        // $size_img[0] - ширина исходного изображения
        // $size_img[1] - высота исходного изображения
        imagecopyresampled(
            $dest_img,
            $res_img,


            0,
            0,
            0,
            0,
            $width,
            $height,
            $width_src,
            $height_src);
        // Сохраняем уменьшенную копию в файл
        if($size_img[2]==2) imagejpeg($dest_img, $small);
        elseif($size_img[2]==1) imagegif($dest_img, $small);
        // Очишаем память от созданных изображений
        imagedestroy($dest_img);
        imagedestroy($res_img);
        return true;
    }

    //function LoadPNG ($imgname) {
    //    $im = @imagecreatefrompng ($imgname); /* попытка открыть */
    //    if (!$im) { /* проверить, удачно ли */
    //        $im= imagecreate (150, 30); /* создать пустое изображение */
    //        $bgc = imagecolorallocate ($im, 255, 255, 255);
    //        $tc= imagecolorallocate ($im, 0, 0, 0);
    //        imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
    //        /* вывести errmsg */
    //        imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc);
    //    }
    //    return $im;
    //}



    /**
     * Проверяем изображение на соответствие размерам
     * @param $big - $_FILES['<name>']['tmp_name'] - временный файл
     * @param $small - $_FILES['<name>']['name'] - актуальный файл
     * @param $width - требуемая ширина
     * @param $height - требуемая высота
     * @param $dir - директория для сохранения
     * @return bool
     */
    function resizeImgWithDir( $big, $small, $width, $height, $dir ){
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
            LoadPNG( $big, $width, $height, $small );
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
        //    elseif( $size_img[2]==3 ) {
        //        $path = renameImg( $small, $dir );
        //        imagepng( $dest_img, $path );
        //    }
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
    function LoadPNG( $imgname, $width, $height, $small ) {
        // Создаем ресурс из исходного изображения
        $res_img = imagecreatefrompng( $imgname );
        // Получаем информацию о изображении
        $prop = getimagesize( $imgname );
        // это для деббагинга
        if( ! $res_img ) {
            $res_img = imagecreate ( $width, $height ); // создать пустое изображение
            $bgc = imagecolorallocate ( $res_img, 255, 255, 255 );
            $tc= imagecolorallocate ( $res_img, 0, 0, 0 );
            imagefilledrectangle ( $res_img, 0, 0, $width, $height, $bgc );
            imagestring ( $res_img, 1, 5, 5, "Error loading $imgname", $tc ); // вывести errmsg
        }
        // Создаем новый ресурс с нужной шириной и высотой
        $dst = imagecreatetruecolor( $width, $height );
        // Режим смешивания по умолчанию для truecolor изображений - true, для изображений
        // с палитрой - false; true/false - включен/выключен
        // true - при накладывании одного изображения на другое цвета пикселей нижележащего и накладываемого изображения смешиваются,
        // параметры смешивания определяются прозрачностью пикселя. false - накладываемый пиксель заменяет исходный
        imagealphablending($dst, false);
        // Сохраняем информацию о прозрачности
        imagesavealpha($dst, true);
        // Копируем исходное изображение в новый ресурс
        imagecopyresampled( $dst, $res_img, 0,0,0,0, $width, $height, $prop[0], $prop[1] );
        // Сохраняем изображение в переменную(это путь к файлу для БД)
        imagepng( $dst, $small );
        // Уничтожаем временные файлы
        imagedestroy( $dst );
        imagedestroy( $res_img );
        return true;
    }

} catch( \dmn\base\AppException $exc ) {
    print $exc->getErrorObject();
} catch( \dmn\base\DBException $exc ) {
    print $exc->getErrorObject();
}