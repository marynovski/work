<?php

require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';
include_once 'class/GetElement.php';
include_once 'class/Curl.php';

$return_headers = [];

$errors = '';



// funkcja odbierająca nagłówki

function headerLine($curl, $header_line ) {
    $GLOBALS['return_headers'][] = $header_line;
    return strlen($header_line);
}

$iloscFirm = 0;
$curl = new Curl();
$file = fopen('portugalia.csv', 'a');
for($i = $argv[1];$i<=$argv[2];$i++) {
    echo 'Strona ' . $i . '<br>';
    $url_to_server = 'http://www.pai.pt/q/ajax/business?contentErrorLinkEnabled=true&input=Cabeleireiros&what=Cabeleireiros&where=&type=DOUBLE&sort=&refine=&char=&location=&address=&resultlisttype=A_AND_B&page=' . $i . '&originalContextPath=http://www.pai.pt/q/business/advanced/what/Cabeleireiros/?contentErrorLinkEnabled=true';
//    $url_to_server = "http://www.pai.pt/q/ajax/business?contentErrorLinkEnabled=true&input=Cabeleireiros&what=Cabeleireiros&where=&type=DOUBLE&sort=&refine=&char=&location=&address=&resultlisttype=A_AND_B&page=4&originalContextPath=http://www.pai.pt/q/business/advanced/what/Cabeleireiros/?contentErrorLinkEnabled=true";
    $ch = curl_init($url_to_server);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // chcemy odebrać dane
    curl_setopt($ch, CURLOPT_USERAGENT, 'fake browser'); // ustawiamy user-agenta
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com'); // ustawiamy referera
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, "headerLine"); // ustawiamy własną funkcję do przetworzenia nagłówka
    $response_body = curl_exec($ch);

    $html = json_decode($response_body);
    $html = $html->html;
//    echo $html;

    preg_match_all('/\<span id\=\"listingbase(.*?)\" class\=\"result\-bn medium\"\>(.*?)\<\/span\>/', $html, $nazwy);
//$nazwy_firm = $nazwy[2];
//print_r($nazwy[2]);
    preg_match_all('/\<div class\=\"result\-address\"\>(.*?)\<\/div\>/', $html, $dane_adresowe);
    preg_match_all('/\<span class\=\"phone\-number\"\>(.*?)\<\/span\>/', $html, $telefony);
//print_r($telefony[1]);
    preg_match_all('/\<a id\=\"titlebase(.*?)\" href\=\"(.*?)\" class\=\"detaillink wt hover\-wt medium\" data\-wt\=\"(.*?)\" target\=\"\_blank\">\<span id\=\"listingbase(.*?)\" class\=\"result\-bn medium\"\>(.*?)\<\/span\> \<\/a\>/', $html, $www);
//    print_r($www);
//    die();
    $daneIterator = 0;
    foreach ($dane_adresowe[1] as $dane) {
        $dane_adresowe[1][$daneIterator] = explode('<br/>', $dane);
        $daneIterator++;
    }
    $daneIterator = 0;
    foreach ($dane_adresowe[1][$daneIterator] as $miniDane) {
        $dane_adresowe[1][$daneIterator][0] = explode(',', $dane_adresowe[1][$daneIterator][0][0]);
        $dane_adresowe[1][$daneIterator][1] = explode(' ', $dane_adresowe[1][$daneIterator][1][0]);
        $daneIterator++;
    }

    $kody = [];
    $miasta = [];
    $adresy = [];

    for ($j = 0; $j < 19; $j++) {
        $adres_i_miasto = explode(',', $dane_adresowe[1][$j][0]);
        $adres = $adres_i_miasto[0];
        $miasto = $adres_i_miasto[1];
        $postal_i_miasto = explode(' ', $dane_adresowe[1][$j][1]);
        $kod = $postal_i_miasto[0];
        $kody[$j] = $kod;
        $miasta[$j] = $miasto;
        $adresy[$j] = $adres;
    }
    $nazwy_firm = $nazwy[2];
    $adresy_www = $www[2];
    $telefoniki = $telefony[1];

//    print_r($nazwy_firm);
//    print_r($miasta);
//    print_r($adresy);
//    print_r($kody);
//    print_r($telefoniki);
//    print_r($adresy_www);

    for ($k = 0; $k < 19; $k++) {


        if (!empty($nazwy_firm[$k])) {
            $companyString = '"';
            $companyString .= $nazwy_firm[$k] . '"' . "\t";
        } else {
            $companyString = '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($adresy[$k])) {
            $companyString .= '"';
            $companyString .= $adresy[$k] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($kody[$k])) {
            $companyString .= '"';
            $companyString .= str_replace(' ', '', $kody[$k]) . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($miasta[$k])) {
            $companyString .= '"';
            $companyString .= $miasta[$k] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($telefoniki[$k])) {
            $companyString .= '"';
            $companyString .= $telefoniki[$k] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($adresy_www[$k])) {
            $companyString .= '"';
            $companyString .= $adresy_www[$k] . '"' . "\n";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\n";
        }
        fwrite($file, $companyString);
        $iloscFirm++;
        echo "Firma ".$iloscFirm."/4427".PHP_EOL;
//        echo 'Strona ' . $i . 'Firma ' . $k+1 . '/20 ' . $companyString.'<br><hr>';
    }

}
fclose($file);









