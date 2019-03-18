<?php

require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';
include_once 'class/GetElement.php';
include_once 'class/Curl.php';

$curl = new Curl();


$html = $curl->getHtml("https://www.zivefirmy.cz/kosmetika-kadernictvi_o35");
//ilosc firm/40 = ilosc stron
/** @var simple_html_dom $html */
$html = str_get_html($html);
$elementIloscFirm = GetElement::getBySelector('span.count', $html);
$elementIloscFirm[0] = str_replace(' ', '', $elementIloscFirm[0]);
$iloscStron = (int)$elementIloscFirm[0] = substr($elementIloscFirm[0],-16,4)/40+1;

$firmyFile = fopen("zivefirmy.csv", 'a');
$progresFile = fopen("progres.txt", 'a');
//$curl->getLastLineInProgressFile()
for($strona = 1;$strona<=1;$strona++) {
    $html = $curl->getHtml("https://www.zivefirmy.cz/kosmetika-kadernictvi_o35?pg=" . $strona);
    //ZIVE FIRMY POBIERANIE DANYCH Z JEDNEJ STRONY
    $html = str_get_html($html);
    $linki_do_firm = GetElement::getItemLoop('div.title > a', $html, 'href');
    $linkiIterator = 0;
    foreach ($linki_do_firm as $link) {
        $linki_do_firm[$linkiIterator] = 'https://www.zivefirmy.cz' . $linki_do_firm[$linkiIterator];
        $linkiIterator++;
    }

    echo "STRONA ".$strona.'<br>';
    foreach ($linki_do_firm as $link) {
        if ($firmaHtml = $curl->getHtml($link)) {
            $firmaHtml = str_get_html($firmaHtml);
            $nazwa = GetElement::getBySelector('div.wrapper-header > h1', $firmaHtml);
            $adres = GetElement::getBySelector('span[itemprop="streetAddress"]', $firmaHtml);
            $miasto = GetElement::getBySelector('span[itemprop="addressLocality"]', $firmaHtml);
            $kodPocztowy = GetElement::getBySelector('span[itemprop="postalCode"]', $firmaHtml);
            $telefon = GetElement::getBySelector('span[itemprop="telephone"]', $firmaHtml);
            $www = GetElement::getBySelector('span.title', $firmaHtml);

            echo $nrFirmy.' ';
            $nrFirmy++;

            if (!empty($nazwa[0]->innertext)) {
                $companyString = '"';
                $companyString .= $nazwa[0]->innertext . '"' . "\t";
            } else {
                $companyString = '"';
                $companyString .= '"'."\t";
            }
            if (!empty($adres[0]->innertext)) {
                $companyString .= '"';
                $companyString .= $adres[0]->innertext . '"' . "\t";
            } else {
                $companyString .= '"';
                $companyString .= '"' . "\t";
            }
            if (!empty($kodPocztowy[0]->innertext)) {
                $companyString .= '"';
                $companyString .= str_replace(' ','',$kodPocztowy[0]->innertext) . '"' . "\t";
            } else {
                $companyString .= '"';
                $companyString .= '"' . "\t";
            }
            if (!empty($miasto[0]->innertext)) {
                $companyString .= '"';
                $companyString .= $miasto[0]->innertext . '"' . "\t";
            } else {
                $companyString .= '"';
                $companyString .= '"' . "\t";
            }
            if (!empty($telefon[0]->innertext)) {
                $companyString .= '"';
                $companyString .= $telefon[0]->innertext . '"' . "\t";
            } else {
                $companyString .= '"';
                $companyString .= '"' . "\t";
            }
            if (!empty($www[0]->innertext)) {
                $companyString .= '"';
                $companyString .= $www[0]->innertext . '"' . "\n";
            } else {
                $companyString .= '"';
                $companyString .= '"' . "\n";
            }
            fwrite($firmyFile, $companyString);
            fwrite($progresFile, $strona . ' ' . $nrFirmy . "\n");
            echo '<hr>';
        }

    }

}
fclose($firmyFile);
fclose($progresFile);





