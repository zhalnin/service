<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 23.05.12
 * Time: 8:38
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);

// Проверяем имеется ли в директории $dir
// файл .htpaccess
function is_htaccess($ftp_handle, $dir)
{
    $is_htaccess_dir = false;
    $file_list = @ftp_rawlist($ftp_handle, $dir);
    if(!empty($file_list))
    {
        foreach($file_list as $file_single)
        {
        // Разбиваем строку по пробельным символам
        list($acc,
             $bloks,
             $group,
             $user,
             $size,
             $month,
             $day,
             $year,
             $file) = preg_split("/[\s]+/", $file_single);
        if($file == ".htaccess") $is_htaccess_dir = true;
        }
    }
    return $is_htaccess_dir;
}

// Проверяем имеется ли в директории $dir
// файл .htpasswd
function is_htpasswd($ftp_handle, $dir)
{
    $is_htpasswd_dir = false;
    $file_list = @ftp_rawlist($ftp_handle, $dir);
    if(!empty($file_list))
    {
        foreach($file_list as $file_single)
        {
        // Разбиваем строку по пробельным символам
        list($acc,
             $bloks,
             $group,
             $user,
             $size,
             $month,
             $day,
             $year,
             $file) = preg_split("/[\s]+/", $file_single);
        if($file == ".htpasswd") $is_htpasswd_dir = true;
        }
    }
    return $is_htpasswd_dir;
}

// Запись $content в файл .htpasswd
function put_htpasswd($ftp_handle, $dir, $content)
{
    $local_htpasswd = tempnam("files", "down");
    $ftp_htpasswd = str_replace("//","/",$dir.'/.htpasswd');
    $fd = @fopen($local_htpasswd,"w");
    if($fd)
    {
        @fwrite($fd, $content);
        @fclose($fd);
    }
    @chmod($local_htpasswd, 0644);
    // Загружаем файл .htaccess на сервер
    $ret = @ftp_nb_put($ftp_handle,
                       $ftp_htpasswd,
                       $local_htpasswd,
                       FTP_BINARY);
    while($ret == FTP_MOREDATA)
    {
        // Продолжаем загрузку
        $ret = @ftp_nb_continue($ftp_handle);
    }
    if($ret == FTP_FINISHED)
    {
        // Изменяем права доступа
        // к созданной директории
        @ftp_chmod($ftp_handle, 0644, $ftp_htpasswd);
    }
    @unlink($local_htpasswd);
}

// Чтение содержимого файла .htpasswd
function get_htpasswd($ftp_handle, $dir)
{
    $local_htpasswd = tempnam("files", "down");
    $ftp_htpasswd = str_replace("//","/",$dir.'/.htpasswd');
    $ret = @ftp_nb_get($ftp_handle,
                       $local_htpasswd,
                       $ftp_htpasswd,
                       FTP_BINARY);
    while($ret == FTP_MOREDATA)
    {
        // Продолжаем загрузку
        $ret = @ftp_nb_continue($ftp_handle);
    }
    @chmod($local_htpasswd, 0644);
    $content = @file_get_contents($local_htpasswd);
    @unlink($local_htpasswd);
    return $content;
}

// Запись $content в файл .htaccess
function put_htaccess($ftp_handle, $dir, $content)
{
    $local_htaccess = tempnam("files", "down");
    $fd = @fopen($local_htaccess,"w");
    if($fd)
    {
        @fwrite($fd, $content);
        @fclose($fd);
    }
    @chmod($local_htaccess, 0644);
    // Загружаем файл .htaccess на сервер
    $ftp_name = str_replace("//","/",$dir.'/.htaccess');
    $ret = @ftp_nb_put($ftp_handle,
                       $ftp_name,
                       $local_htaccess,
                       FTP_BINARY);
    while($ret == FTP_MOREDATA)
    {
        // Продолжаем загрузку
        $ret = @ftp_nb_continue($ftp_handle);
    }
    if($ret == FTP_FINISHED)
    {
        // Изменяем права доступа
        // к созданной директории
        @ftp_chmod($ftp_handle, 0644, $ftp_name);
    }
    @unlink($local_htaccess);
}

// Чтение содержимого файла .htaccess
function get_htaccess($ftp_handle, $dir)
{
    $local_htaccess = tempnam("files", "down");
    $ftp_htaccess = str_replace("//","/",$dir.'/.htaccess');
    $ret = @ftp_nb_get($ftp_handle,
                       $local_htaccess,
                       $ftp_htaccess,
                       FTP_BINARY);
    while($ret == FTP_MOREDATA)
    {
        // Продолжаем загрузку
        $ret = @ftp_nb_continue($ftp_handle);
    }
    @chmod($local_htaccess, 0644);
    $content = @file_get_contents($local_htaccess);
    @unlink($local_htaccess);
    return $content;
}
?>