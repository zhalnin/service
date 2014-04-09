<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 17.06.12
 * Time: 18:39
 * To change this template use File | Settings | File Templates.
 */
 
error_reporting(E_ALL & ~E_NOTICE);
$name = basename($_SERVER['PHP_SELF']);
?>
    <div <?php if('index.php'               == $name) echo 'class=active'; ?>><a class=menu href=index.php >Главная страница счетчика</a></div>
    <div <?php if('send.php'                == $name) echo 'class=active'; ?>><a class=menu href=send.php >Почтовый отчет</a></div>
    <div <?php if('hits.php'                == $name) echo 'class=active'; ?>><a class=menu href=hits.php >Хосты&nbsp;и&nbsp;Хиты</a></div>
    <div <?php if('hits.daily.php'          == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="hits.daily.php">Посуточный отчет</a></div>
    <div <?php if('hits.weekly.php'         == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="hits.weekly.php">Понедельный отчет</a></div>
    <div <?php if('hits.monthly.php'        == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="hits.monthly.php">Помесячный отчет</a></div>
    <div <?php if('clients.php'             == $name) echo 'class=active'; ?>><a class=menu href=clients.php >Системы&nbsp;и&nbsp;браузеры</a></div>
    <div <?php if('clients.daily.php'       == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="clients.daily.php">Посуточный отчет</a></div>
    <div <?php if('clients.weekly.php'      == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="clients.weekly.php">Понедельный отчет</a></div>
    <div <?php if('clients.monthly.php'     == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="clients.monthly.php">Помесячный отчет</a></div>
    <div <?php if('addresses.php'           == $name) echo 'class=active'; ?>><a class=menu href=addresses.php?id_page=<?php echo $_GET['id_page']; ?>>IP-адреса</a></div>
    <div <?php if('addresses.daily.php'     == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="addresses.daily.php">Посуточный отчет</a></div>
    <div <?php if('addresses.weekly.php'    == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="addresses.weekly.php">Понедельный отчет</a></div>
    <div <?php if('addresses.monthly.php'   == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="addresses.monthly.php">Помесячный отчет</a></div>
    <div <?php if('robots.php'              == $name) echo 'class=active'; ?>><a class=menu href=robots.php?id_page=<?php echo $_GET['id_page']; ?>>Поисковые&nbsp;роботы</a></div>
    <div <?php if('robots.daily.php'        == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="robots.daily.php">Посуточный отчет</a></div>
    <div <?php if('robots.weekly.php'       == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="robots.weekly.php">Понедельный отчет</a></div>
    <div <?php if('robots.monthly.php'      == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="robots.monthly.php">Помесячный отчет</a></div>
    <div <?php if('searchquery.php'          == $name) echo 'class=active'; ?>><a class=menu href=searchquery.php?id_page=<?php echo $_GET['id_page']; ?>>Поисковые&nbsp;запросы</a></div>
    <div <?php if('searchquery.daily.php'    == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="searchquery.daily.php">Посуточный отчет</a></div>
    <div <?php if('searchquery.weekly.php'   == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="searchquery.weekly.php">Понедельный отчет</a></div>
    <div <?php if('searchquery.monthly.php'  == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="searchquery.monthly.php">Помесячный отчет</a></div>
    <div <?php if('quers.php'               == $name) echo 'class=active'; ?>><a class=menu href=quers.php?id_page=<?php echo $_GET['id_page']; ?>>Статистика&nbsp;поисковых&nbsp;запросов</a></div>
    <div <?php if('refferer.php'            == $name) echo 'class=active'; ?>><a class=menu href=refferer.php?id_page=<?php echo $_GET['id_page']; ?>>Рефереры</a></div>
    <div <?php if('refferer.daily.php'      == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="refferer.daily.php">Посуточный отчет</a></div>
    <div <?php if('refferer.weekly.php'     == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="refferer.weekly.php">Понедельный отчет</a></div>
    <div <?php if('refferer.monthly.php'    == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="refferer.monthly.php">Помесячный отчет</a></div>
    <div <?php if('enterpoint.php'          == $name) echo 'class=active'; ?>><a class=menu href=enterpoint.php?id_page=<?php echo $_GET['id_page']; ?>>Точки&nbsp;входа</a></div>
    <div <?php if('deep.php'                == $name) echo 'class=active'; ?>><a class=menu href=deep.php?id_page=<?php echo $_GET['id_page']; ?>>Глубина&nbsp;просмотра</a></div>
    <div <?php if('deep.daily.php'          == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="deep.daily.php">Посуточный отчет</a></div>
    <div <?php if('deep.weekly.php'         == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="deep.weekly.php">Понедельный отчет</a></div>
    <div <?php if('deep.monthly.php'        == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="deep.monthly.php">Помесячный отчет</a></div>
    <div <?php if('time.php'                == $name) echo 'class=active'; ?>><a class=menu href=time.php?id_page=<?php echo $_GET['id_page']; ?>>Время&nbsp;сеанса</a></div>
    <div <?php if('time.daily.php'          == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="time.daily.php">Посуточный отчет</a></div>
    <div <?php if('time.weekly.php'         == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="time.weekly.php">Понедельный отчет</a></div>
    <div <?php if('time.monthly.php'        == $name) echo 'class=active'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<a class=menu href="time.monthly.php">Помесячный отчет</a></div>