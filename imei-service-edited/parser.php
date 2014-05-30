<?php
//Ставим время выполнения 10 минут
ini_set('max_execution_time', 600);
//устанавливаем переменные...
$url		=	$_POST['url'];
$urlParser  =   $_POST['urlParser'];
$ime_i      =   $_POST['ime_i'];
$types		=	$_POST['types'];
$maxPages	=	(int)$_POST['max'];
//    $host		=	explode('/', substr($url, 7));
//    $host		=	substr($url, 0, 7).$host[0].'/';
$hostPost = $url.$urlParser;
$hostGet = $url;
//echo $hostGet;
$user_agents = array(
    "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)",
    "Mozilla/5.0 (compatible; Mail.RU/2.0)",
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22",
    "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
    "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)",
    "Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
    "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0",
    "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5",
    "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)",
    "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_2 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B146 Safari/8536.25"
);
$id         = rand(0,count($user_agents)-1);
$user_agent = $user_agents[$id];
//про последние 2 строчки подробней: таким вот образом получаем Адрес сайта (на случай, если ввели
//адрес странички сайта) - разбиваем на массив по слешу и берём первую часть...

//для удобства работы с КУРЛом, напишем простенькую функцию
//параметры: $host - адрес, $referer - откуда пришли (можно подделать, в статистике парсимого сайта будет отображаться, что мы пришли, например, с Яндекса :))
//$file	- идентификатор файла (если мы хотим скачать файл, то передаём его идентификатор)
//function curl_get($hostGet,$user_agent){
function curl_get($hostGet,$user_agent){
    //инициализация curl и задание основных параметров
    $curl = curl_init($hostGet);
    curl_setopt($curl, CURLOPT_USERAGENT, $user_agent );
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // вывести на экран, если нет - то в переменную
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookieGet.txt');  // Записываем cookie
    curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookieGet.txt'); // Читаем cookies
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: iphoneimei.info'));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);     // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
    //если дали ссылку на файл


//  если CURLOPT_RETURNTRANSFER - false
    //если же ссылку на файл не дали, то возвращаем страничку
    ob_start();
    curl_exec($curl);
    print(curl_error($curl));
    curl_close($curl);
    return ob_get_clean();

//  если CURLOPT_RETURNTRANSFER - true
//    $out = curl_exec($curl);
//        echo $out;
//    curl_close($curl);
//    return $out;
}

function curl_post($host, $user_agent, $ime_i ) {
    if( $curl = curl_init() ) {
        curl_setopt($curl, CURLOPT_URL, $host); // Инициализируем соединение, можно и сразу в curl_init передать
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent ); // Содержимое заголовка user_agent в HTTP-запросе
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true); // Возвращаем результат в виде строки, вместо вывода в окно браузера - TRUE
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookiePost.txt');  // Записываем cookie
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookiePost.txt'); // Читаем cookies
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FALSE - остановка от проверки сертификата узла
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Проверка существования общего имени в сертификате
        curl_setopt($curl, CURLOPT_POST, true); // TRUE - для использования POST (applications/x-www-form-urlencoded)
        curl_setopt($curl, CURLOPT_POSTFIELDS, "ime_i="+$ime_i); // Данные в POST (при передачи файлов, в начале - @ - значит массив)
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);     // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
        $out = curl_exec($curl);
//        echo $out;
        curl_close($curl);
        return $out;


//        ob_start();
//        curl_exec($curl);
//        print(curl_error($curl));
//        curl_close($curl);
//        return ob_get_clean();
    }
}
//получаем html-код исходной страницы
$page	=	curl_get($hostGet,$user_agent);
//получаем html-код исходной страницы
//$page	=	curl_post($hostPost,$user_agent,$ime_i);
//echo $page;
//регулярным выражением ищем вхождение ссылок
//    preg_match_all('#href="([A-z0-9.-]+)"#', $page, $matchModel);

//// Модель аппарата
preg_match('|<div class="error">(.+?)</div>|is',$page,$matchErrors);
preg_match('|<h2 class="model">(.+?)</h2>|is',$page,$matchModel);
//// Объем памяти и цвет аппарата
preg_match('|<h3 class="capacity color">(.+?)</h3>|is', $page,$matchCap);
preg_match_all('|<span class="name">(.+?)</span>|is', $page,$matchName);
preg_match_all('|<span class="value">(.+?)</span>|is', $page,$matchValue);
preg_match('|<a id="special_info">(.+?)</a>|is', $page, $matchRef);



//получаем массив всех-всех ссылок с этой старницы
$error      =   $matchErrors[1];
$model		=	$matchModel[1];
$cap        =   $matchCap[1];
$name       =   $matchName[1];
$value      =   $matchValue[1];
$ref        =   strval($matchRef[1]);
?>



<div id="pb-ipad" class="productbrowser content pb-dynamic" style="height: 400px;">
    <div class="pb-slider" style="height: 400px;">
        <div id="pb-slider" class="pb-slide" style="width: 970px;">
            <?php
            if(!$error){
            ?>
            <ul id="ul-slider" class="ul-slider box" page="1" style="width: 450px; margin: 0 250px; margin-top: 10px">
                <li style="width: 425px; margin: 0; height: 30px" class="check_imei">
                    <div>
                        <p class="check_imei" style="float: left;">Model: </p><p class="check_imei" style="display: block; float: right;"><?php echo $model; echo $cap; ?></p>
                    </div>
                </li>
                <?php
                for($i = 0; $i < sizeof($name);$i++){
                    echo "<li style=\"width: 425px; margin: 0; height: 30px\" class=\"check_imei\">
                                <div>
                                     <p class=\"check_imei\" style=\"float: left;\">$name[$i] </p>";
                    switch(strtolower($value[$i])){
                        case('<a id="special_info"> check carrier </a>'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"carrier_check.php\" class=\"special_info\">Уточнить оператора</a></p>";
                            break;
                        case('<a class="show_email_info"> check carrier </a>'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"carrier_check.php\" class=\"special_info\">Уточнить оператора</a></p>";
                            break;
                        case('<a class="show_email_info"> check sim lock </a>'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right;\"><a href=\"carrier_check.php\" class=\"special_info\">Уточнить статус</a></p>";
                            break;
                        case('unlocked'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: #039103;\">$value[$i]</p>";
                            break;
                        case('yes'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: #039103;\">$value[$i]</p>";
                            break;
                        case('unknown'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                            break;
                        case('locked'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                            break;
                        case('no'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                            break;
                        case('expired'):
                            echo "<p class=\"check_imei\" style=\"display: block; float: right; color: red;\">$value[$i]</p>";
                            break;
                        default:
                            echo "<p class=\"check_imei\" style=\"display: block; float: right;\">$value[$i]</p>";

                    }
                    ?>

                    <?php
                    echo "</div>
                            </li>";
                }
                ?>

                <?php
                } else if($error){

                    echo "<li style=\"width: 325px; margin: 0; height: 30px\" class=\"check_imei\">
                        <div style=\"left: 330px; display: inline-block; margin: 100px auto; position: absolute\">
                                <p class=\"check_imei\" ></p><p class=\"check_imei\" >Сервер перегружен, попробуйте позже...</p>
                                <p class=\"check_imei\"><a href=\"carrier_check.php\" class=\"special_info\" >Вы можете воспользоваться платной услугой</a></p>
                        </div>
                      </li>";

                }
                ?>
            </ul>
        </div>
    </div>
</div>
