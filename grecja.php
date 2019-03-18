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




$firmyFile = fopen("grecja.csv", 'a');
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

    foreach ($linki_do_firm as $link) {
        $fyrmaHtml = $curl->getHtml("https://www.vrisko.gr/advdetails/6cc44a1_0daj6a3d3k7g3165b___a63f5f463k0cc6_a6a5fag742abebf032c_6?what=%CE%9A%CE%BF%CE%BC%CE%BC%CF%89%CF%84%CE%AE%CF%81%CE%B9%CE%B1%20%CE%9A%CE%BF%CF%85%CF%81%CE%B5%CE%AF%CE%B1&where=&region=1");
//        echo $fyrmaHtml;
        $fyrmaHtml = str_get_html($fyrmaHtml);
        $nazwa = GetElement::getItemLoop('label.companyLabel_class > span', $fyrmaHtml, 'innertext');
//        print_r($nazwa);
        $daneAdresowe = GetElement::getItemLoop('td.DetailAddressTd > label', $fyrmaHtml, 'innertext');
//        print_r($daneAdresowe);

        $daneAdresoweIterator = 0;
        foreach ($daneAdresowe as $adres) {
            $rozbiteAdresy = explode(',', $daneAdresowe[$daneAdresoweIterator]);
            $ulice[$daneAdresoweIterator] = trim($rozbiteAdresy[0]);
            preg_match('/<meta itemprop\=\"addressLocality\" content\=\"(.*?)\" \/\>/', $daneAdresowe[$daneAdresoweIterator], $miasto);


            $miasta[$daneAdresoweIterator] = trim(" Miasto");

//        print_r($rozbiteAdresy);

            foreach ($rozbiteAdresy as $rozbityAdres) {
                if (preg_match('/^\s[0-9]{5}$/', $rozbityAdres, $kod)) {
                    $kody[$daneAdresoweIterator] = trim($kod[0]);
                }
            }
        }

        $telefon = GetElement::getBySelector('div.details_list_content_class > label.rc_firstphone', $fyrmaHtml);
        $www = GetElement::getBySelector('a.rc_Detaillink',$fyrmaHtml);

        var_dump($nazwa[0]);
        var_dump($ulice[0]);
        var_dump($miasta[0]);
        var_dump($kody[0]);
        var_dump($telefon[0]->innertext);
        var_dump($www[0]->href);


        $nazwa = trim($nazwa[0]);
//        var_dump($nazwa);
        $adres = trim($ulice[0]);
//        var_dump($adres);
        $miasto = trim($miasta[0]);
//        var_dump($miasto);
        $kodPocztowy = trim($kody[0]);
//        var_dump($kodPocztowy);
        $tel = trim($telefon[0]->innertext);
//        var_dump($tel);
        $strona = trim($www[0]->href);
//        var_dump($strona);


    }

//    $nazwy = GetElement::getItemLoop('h2.CompanyName > a > meta', $html2Dom, 'content' );
//    $daneAdresowe = GetElement::getItemLoop('div.AdvAddress', $html2Dom, 'innertext' );
//
//    $daneAdresoweIterator = 0;
//    foreach ($daneAdresowe as $adres) {
//        $rozbiteAdresy = explode(',', $daneAdresowe[$daneAdresoweIterator]);
//        $ulice[$daneAdresoweIterator] = $rozbiteAdresy[0];
//        preg_match('/<meta itemprop\=\"addressLocality\" content\=\"(.*?)\" \/\>/', $daneAdresowe[$daneAdresoweIterator], $miasto);
//        $miasta[$daneAdresoweIterator] = $miasto[1];
//
////        print_r($rozbiteAdresy);
//
//        foreach ($rozbiteAdresy as $rozbityAdres) {
//            if (preg_match('/^\s[0-9]{5}$/', $rozbityAdres, $kod)) {
//                $kody[$daneAdresoweIterator] = $kod[0];
//            }
//        }
//
//        $www[$daneAdresoweIterator] = GetElement::getItemLoop('a.siteLink > meta', $html2Dom, 'content');
//
////        echo $ulice[$daneAdresoweIterator].'<br>';
////        echo $miasta[$daneAdresoweIterator].'<br>';
////        echo '<hr>';
//
//
//
//        print_r($nazwy);
//        print_r($ulice);
//        print_r($miasta);
//        print_r($kody);
//        die();
        if (!empty($nazwa)) {
            $companyString = '"';
            $companyString .= $nazwa . '"' . "\t";
        } else {
            $companyString = '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($adres)) {
            $companyString .= '"';
            $companyString .= $adres . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($miasto)) {
            $companyString .= '"';
            $companyString .= str_replace(' ', '', $miasto) . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($kodPocztowy)) {
            $companyString .= '"';
            $companyString .= $kodPocztowy . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($tel)) {
            $companyString .= '"';
            $companyString .= $tel . '"' . "\t";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\t";
        }
        if (!empty($strona)) {
            $companyString .= '"';
            $companyString .= $strona . '"' . "\n";
        } else {
            $companyString .= '"';
            $companyString .= '"' . "\n";
        }
        echo $companyString;

        fwrite($firmyFile, $companyString);
        echo 'Strona ' . $strona . ' Firma: ' . ($daneAdresoweIterator) . '/20 ' . $companyString . PHP_EOL;

        $daneAdresoweIterator++;
        die();
    }



fclose($firmyFile);
//fclose($progresFile);





