<?php

require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';
include_once 'class/GetElement.php';
include_once 'class/Curl.php';

$curl = new Curl();


//$html = $curl->getHtml("https://www.zivefirmy.cz/kosmetika-kadernictvi_o35");
////ilosc firm/40 = ilosc stron
///** @var simple_html_dom $html */
//$html = str_get_html($html);
//$elementIloscFirm = GetElement::getBySelector('span.count', $html);
//$elementIloscFirm[0] = str_replace(' ', '', $elementIloscFirm[0]);
//$iloscStron = (int)$elementIloscFirm[0] = substr($elementIloscFirm[0],-16,4)/40+1;




//$firmyFile = fopen("zivefirmy.csv", 'a');
//$progresFile = fopen("progres.txt", 'a');
//$curl->getLastLineInProgressFile()
for($strona = $argv[1];$strona<=$argv[2];$strona++) {
    $html2 = $curl->getHtml("https://www.zivefirmy.cz/kosmetika-kadernictvi_o35?pg=" . $strona);
    //ZIVE FIRMY POBIERANIE DANYCH Z JEDNEJ STRONY
    $html2 = str_get_html($html2);
    $linki_do_firm = GetElement::getItemLoop('div.title > a', $html2, 'href');
//    <a href="/nehtove-studio-rose-ml_f1555953?cz=35" title="NEHTOVÉ STUDIO ROSE ML">NEHTOVÉ STUDIO ROSE ML</a>
//    preg_match_all('/\<div class\=\"title\"\>\<a href\=\"(.*?)\" title\=\"(.*?)\"\>(.*?)\<\/a\>/', $html2, $linki_do_firm);
    $linkiIterator = 0;
    foreach ($linki_do_firm as $link) {
        $linki_do_firm[$linkiIterator] = 'https://www.zivefirmy.cz' . $linki_do_firm[$linkiIterator];
        $linkiIterator++;
//        echo 'Strona '.$strona.' Link do firmy: '.($linkiIterator).'/41'.$linki_do_firm[$linkiIterator-1].PHP_EOL;
    }
    $jakiIterator = 0;
    foreach ($linki_do_firm as $link) {
        $firmaHtml = $curl->getHtml($linki_do_firm[$jakiIterator]);
        preg_match('/\<h1 itemprop\=\"name\"\>(.*?)\<\/h1\>/', $firmaHtml, $nazwa);
        preg_match('/\<span itemprop\=\"streetAddress\">(.*?)\<\/span\>/', $firmaHtml, $adres);
        preg_match('/\<span itemprop\=\"addressLocality\" style\=\"font\-weight\:bold\;\"\>(.*?)\<\/span\>/', $firmaHtml, $miasto);
        preg_match('/\<span itemprop\=\"postalCode\">(.*?)\<\/span\>/', $firmaHtml, $kodPocztowy);
        preg_match('/\<span itemprop\=\"telephone\">(.*?)\<\/span\>/', $firmaHtml, $telefon);
        preg_match('/\<span class\=\"title\">(.*?)\<\/span\>/', $firmaHtml, $www);

//            print_r($nazwa);
//            print_r($adres);
//            print_r($miasto);
//            print_r($kodPocztowy);
//            print_r($telefon);
//            print_r($www);
//            echo $nazwa[1] . '<br>';
//            echo $adres[1] . '<br>';
//            echo $miasto[1] . '<br>';
//            echo $kodPocztowy[1] . '<br>';
//            echo $telefon[1] . '<br>';
//            echo $www[1] . '<br>';
        if (!empty($nazwa[1])) {
            $companyString = '"';
            $companyString .= $nazwa[1] . '"' . "\t";
        } else {
            $companyString = '"';
            $companyString .= '"'."\t";
        }
        if (!empty($adres[1])) {
            $companyString .= '"';
            $companyString .= $adres[1] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($kodPocztowy[1])) {
            $companyString .= '"';
            $companyString .= str_replace(' ','',$kodPocztowy[1]) . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($miasto[1])) {
            $companyString .= '"';
            $companyString .= $miasto[1] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($telefon[1])) {
            $companyString .= '"';
            $companyString .= $telefon[1] . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($www[1])) {
            $companyString .= '"';
            $companyString .= $www[1] . '"' . "\n";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\n";
        }
        fwrite($firmyFile, $companyString);
//            fwrite($progresFile, $strona . ' ' . $nrFirmy . "\n");
        echo 'Strona '.$strona.' Firma: '.($jakiIterator+1).'/41 '. $companyString . PHP_EOL;


        $jakiIterator++;
    }

}
fclose($firmyFile);
//fclose($progresFile);






