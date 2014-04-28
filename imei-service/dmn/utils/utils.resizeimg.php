<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 18.05.12
 * Time: 19:18
 * To change this template use File | Settings | File Templates.
 */
 
function resizeimg($big, $small, $width, $height) {
    // Имя файла с масштабируемой копией
    $big = "../../$big";
    // Имя файла с уменьшенной копией
    $small = "../../$small";
    // Определяем коэффициент сжатия
    // генерируемого изображения
    $ratio = $width / $height;
    // Получаем размеры исходного изображения
    $size_img = getimagesize($big);
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
    // Создаем пустое изображение по заданным размерам
    $dest_img = imagecreatetruecolor($width,$height);
    $white = imagecolorallocate($dest_img, 255,255,255);
//    imagefilledrectangle ($dest_img, 0, 0, 150, 30, $white);
    if($size_img[2] == 2) $src_img = imagecreatefromjpeg($big);
    else if($size_img[2] == 1) $src_img = imagecreatefromgif($big);
    else if($size_img[2] == 3){
//        $src_img = imagecreatefrompng($big);
       $src_img = LoadPNG($big);
    }

    // Масштабируем изображение функцией imagecopyresampled()
    // $dest_img - уменьшенная копия
    // $src_img - исходное изображени
    // $width - ширина уменьшенной копии
    // $height - высота уменьшенной копии
    // $size_img[0] - ширина исходного изображения
    // $size_img[1] - высота исходного изображения
    imagecopyresampled($dest_img,
                       $src_img,
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
    elseif($size_img[2]==3) imagepng($dest_img, $small);
    // Очишаем память от созданных изображений
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;
}

function LoadPNG ($imgname) {
    $im = @imagecreatefrompng ($imgname); /* попытка открыть */
    if (!$im) { /* проверить, удачно ли */
        $im= imagecreate (150, 30); /* создать пустое изображение */
        $bgc = imagecolorallocate ($im, 255, 255, 255);
        $tc= imagecolorallocate ($im, 0, 0, 0);
        imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
        /* вывести errmsg */
        imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc);
    }
    return $im;
}

?>