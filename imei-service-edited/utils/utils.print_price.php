<?php

function print_price($price){

    $type = $_COOKIE['currency_type'];
   if(empty($type)){
        $type = 'rub';
    }
    $url = "http://www.cbr.ru/scripts/XML_daily.asp";
    $content = file_get_contents($url);
    $pattern = "|<Valute id=\"([^\"]+)\">[\s]*<NumCode>([^<]+)</NumCode>[\s]*<CharCode>([^\<]+)</CharCode>[\s]*<Nominal>([^\<]+)</Nominal>[\s]*<Name>([^\<]+)</Name>[\s]*<Value>([^\<]+)</Value>[\s]*</Valute>|is";
    preg_match_all($pattern,$content,$out);

    for($i = 0; $i < count($out[1]); $i++){
        $out[6][$i] = str_replace(",",".",$out[6][$i]);
        if($out[3][$i] == "USD") $usd = sprintf("%3.2f",$out[6][$i]);
        if($out[3][$i] == "UAH") {
        $uah_res = 10 / ($out[6][$i]);
            $uah = sprintf("%3.2f",$uah_res);
        }
        if($out[3][$i] == "EUR") $eur = sprintf("%3.2f",$out[6][$i]);

    }

    switch($type){
        case('usd'):
            $r = $price / $usd;
            $result = sprintf("%3.2f",$r);
            $c = " USD";
            break;
        case('eur'):
            $r = $price / $eur;
            $result = sprintf("%3.2f",$r);
            $c = " EUR";
            break;
        case('uah'):
            $r = $price * $uah;
            $result = sprintf("%3.2f",$r);
            $c = " UAH";
            break;
        case('rub'):
            $r = $price;
            $result = $r;
            $c = " RUB";
            break;
    }
    return $result." ".$c;
}
?>