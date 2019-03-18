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




//$firmyFile = fopen("paginegialle.csv", 'a');
//$progresFile = fopen("progres.txt", 'a');
//$curl->getLastLineInProgressFile()
for($strona = 1;$strona<=1;$strona++) {

//    $url = 'https://www.vrisko.gr/search/Κομμωτήρια-Κουρεία/?page='.$strona;
    $url = 'https://www.vrisko.gr/search/%CE%9A%CE%BF%CE%BC%CE%BC%CF%89%CF%84%CE%AE%CF%81%CE%B9%CE%B1-%CE%9A%CE%BF%CF%85%CF%81%CE%B5%CE%AF%CE%B1/?page='.$strona;
//    $url = mb_convert_encoding($url, "iso-8859-1", "UTF-8");
    $html2 = $curl->getHtml($url);
//    echo $html2;




    $html2Dom = str_get_html($html2);
    $linki_do_firm = GetElement::getItemLoop('h2.CompanyName > a', $html2Dom, 'href' );

//    print_r($linki_do_firm);
    foreach ($linki_do_firm as $link) {
//        $link = htmlspecialchars($link);
//        mb_convert_encoding(urldecode($link),'HTML-ENTITIES','UTF-8');
        echo $link;
        $fyrmaHtml = $curl->getHtml($link);
        echo $fyrmaHtml;
        die();
    }


    die();


    $nazwy = GetElement::getItemLoop('h2.CompanyName > a > meta', $html2Dom, 'content' );
    $daneAdresowe = GetElement::getItemLoop('div.AdvAddress', $html2Dom, 'innertext' );

    $daneAdresoweIterator = 0;
    foreach ($daneAdresowe as $adres) {
        $rozbiteAdresy = explode(',', $daneAdresowe[$daneAdresoweIterator]);
        $ulice[$daneAdresoweIterator] = $rozbiteAdresy[0];
        preg_match('/<meta itemprop\=\"addressLocality\" content\=\"(.*?)\" \/\>/', $daneAdresowe[$daneAdresoweIterator], $miasto);
        $miasta[$daneAdresoweIterator] = $miasto[1];

//        print_r($rozbiteAdresy);

        foreach ($rozbiteAdresy as $rozbityAdres) {
            if (preg_match('/^\s[0-9]{5}$/', $rozbityAdres, $kod)) {
                $kody[$daneAdresoweIterator] = $kod[0];
            }
        }

        $www[$daneAdresoweIterator] = GetElement::getItemLoop('a.siteLink > meta', $html2Dom, 'content');

//        echo $ulice[$daneAdresoweIterator].'<br>';
//        echo $miasta[$daneAdresoweIterator].'<br>';
//        echo '<hr>';



        print_r($nazwy);
        print_r($ulice);
        print_r($miasta);
        print_r($kody);
        die();
        if (!empty($nazwa[1])) {
            $companyString = '"';
            $companyString .= $nazwa[1] . '"' . "\t";
        } else {
            $companyString = '"';
            $companyString .= '"' . "\t";
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
            $companyString .= str_replace(' ', '', $kodPocztowy[1]) . '"' . "\t";
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
        echo 'Strona ' . $strona . ' Firma: ' . ($daneAdresoweIterator) . '/20 ' . $companyString . PHP_EOL;

        $daneAdresoweIterator++;
    }
}


fclose($firmyFile);
//fclose($progresFile);





