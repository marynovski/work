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




$firmyFile = fopen("paginegialle.csv", 'a');
//$progresFile = fopen("progres.txt", 'a');
//$curl->getLastLineInProgressFile()
for($strona = $argv[1];$strona<=$argv[2];$strona++) {
    $html2 = $curl->getHtml("https://www.paginegialle.it/ricerca/parrucchiere/p-" . $strona);
    //ZIVE FIRMY POBIERANIE DANYCH Z JEDNEJ STRONY
    $html2 = str_get_html($html2);
    $linki_do_firm = GetElement::getItemLoop('h1.fn > a', $html2, 'href');
    $linkiIterator = 0;
    foreach ($linki_do_firm as $link) {
        $linki_do_firm[$linkiIterator] = $linki_do_firm[$linkiIterator];
        $linkiIterator++;
//        echo 'Strona '.$strona.' Link do firmy: '.($linkiIterator).'/20'.$linki_do_firm[$linkiIterator-1].PHP_EOL;
    }
    $jakiIterator = 0;
    foreach ($linki_do_firm as $link) {
        $firmaHtml = $curl->getHtml($linki_do_firm[$jakiIterator]);

        preg_match('/\<h1 class\=\"rgs sh\_rgs\"\>(.*?)\<\/h1\>/',$firmaHtml,$nazwa);
//            preg_match_all('/\<div class\=\"street\-address\"\>(.*?)\<\/div\>/',$firmaHtml,$adres);

        $html3 = str_get_html($firmaHtml);
        $www = GetElement::getItemLoop('div.cta > a.icn-sitoWeb', $html3, 'href');



        preg_match_all('/\<span\>(.*?)\<\/span\>/', $firmaHtml, $adres);
        preg_match('/\<span class\=\"postal\-code\"\>(.*?)\<\/span\>/',$firmaHtml,$kodPocztowy);
        preg_match('/\<span class\=\"locality\"\>(.*?)\<\/span\>/',$firmaHtml,$miasto);

//            print_r($adres);

        foreach ($adres[1] as $element) {
            $element = str_replace(' ','',$element);
            if (preg_match('/^[0-9]{9,11}$/', $element)) {
                $telefon = $element;
            }
        }


//            echo 'Nazwa: ' . $nazwa[1] . '<br>';
//            echo 'Adres: ' . $adres[1][3] . '<br>';
//            echo 'Telefon: ' . $telefon . '<br>';
//            echo 'Miasto: ' . $miasto[1] . '<br>';
//            echo 'Kod pocztowy: ' . $kodPocztowy[1] . '<br>';
//            echo "WWW: " . $www[0] . '<br>';
//        print_r($nazwa[1]);
//            var_dump($adres);
//            print_r($miasto[1]);
//            print_r($kodPocztowy[1]);
//            print_r($telefon);

        if (!empty($nazwa[1])) {
            $companyString = '"';
            $companyString .= $nazwa[1] . '"' . "\t";
        } else {
            $companyString = '"';
            $companyString .= '"'."\t";
        }
        if (!empty($adres[1][3])) {
            $companyString .= '"';
            $companyString .= $adres[1][3] . '"' . "\t";
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
        if (!empty($telefon)) {
            $companyString .= '"';
            $companyString .= $telefon . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($www[0])) {
            $companyString .= '"';
            $companyString .= $www[0] . '"' . "\n";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\n";
        }
        echo $companyString;

        fwrite($firmyFile, $companyString);
        echo 'Strona '.$strona.' Firma: '.($jakiIterator).'/20 '. $companyString . PHP_EOL;

        $jakiIterator++;
    }

}
fclose($firmyFile);
//fclose($progresFile);





