<!Doctype html>
<head>
    <link href="style.css" rel="stylesheet">
</head>

<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 21.01.19
 * Time: 11:31
 */

require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';
include_once 'class/GetElement.php';

$companyString = []; //table with companies data

/** @var simple_html_dom $htmlCode */
$htmlCode = file_get_html("https://infirmy.cz/");

/** @var GetElement $element */
$element = new GetElement();

/** @var array $mainCategoryHref */
$mainCategoryHref = $element->getItemLoop('h3.hp-categories__item-title > a', $htmlCode, 'href' );

/** @var array $count */
$count = $element->getItemLoop('span.badge.hp-categories__count', $htmlCode, 'innertext');
$mainCategoryIterator = 0; //foreach iterator
/**
 * 1. Foreach uses $mainCategoryHref links to get all subCategories links and count of companies
 * 2. Foreach uses $subCategoryHref links to get all companies lists links
 * 3. For loades all pages with company links
 * 4. Foreach gets Company data and saves it to $company[] array
 * 5. Foreach gets $company[] array and do with it whatever I want... now it saves data to .csv
 */$m = 1;
 $mainCategoryHrefIterator = 0;
foreach($mainCategoryHref as $value1){
    //echo $count[$mainCategoryIterator].' => '.$value1.'<br><hr>';
    $mainCategoryIterator++;
    $htmlCode = file_get_html($value1);
    /** @var array $subCategoryHref
     *  gets all href to subCategories
     */
    $subCategoryHref = $element->getItemLoop('li.categories__item > a', $htmlCode, 'href');


    /** @var array $subCount
     * gets counts of companies in subCategories
     */
    $subCount = $element->getItemLoop('small.categories__count', $htmlCode, 'innertext');

    $subCountIterator = 0;


    if($mainCategoryHrefIterator > 0){
        break;
    }

    $subCategoryHrefIterator = 0;
    foreach($subCategoryHref as $value2){

        echo $subCount[$subCountIterator++].' => '.$value2.'<br>';
//        /** delete spaces from count of companies in subCategories */
//        $subCount[$subCountIterator] = str_replace(' ', '', $subCount[$subCountIterator]);
//
//        /**
//         * counting number of pages in one of subCategories
//         */
        $pages = ((int)$subCount[$subCountIterator++]/15);
//
//        /** if $pages is float ++1 */
        if($pages > (int)$pages){
           $pages++;
        }

        //echo (int)$pages.'<br>';

        $htmlCode = file_get_html($value2);
//        $l = 1;
        $company = [];
//        //loop looking for all pages
        if($subCategoryHrefIterator > 1){
            break;
        }
        for($k=1;$k<=(int)$pages;$k++){
            $htmlCode = file_get_html($value2.'?page='.$k);

            $company_link = $element->getItemLoop('h3.firm__title > a', $htmlCode, 'href');

            foreach($company_link as $value4){
                //echo $l.' => '.$value4.'<br>';
                //$company[] = $value4;
                $htmlCode = file_get_html($value4);
                $company['name'] = $element->getItemLoop('h1.firm-detail__title', $htmlCode, 'innertext');
                $company['address'] = $element->getItemLoop('h2.firm-detail__address', $htmlCode, 'innertext');

                /**stoper */
//                if($k > 1){
//                    break;
//                }


                //$l++;
            }
            $z = 0;

            foreach($company as $value5){
                echo 'Firma nr '.$z.'<br>';
                echo $company['name'][$z].'<br>';
                echo $company['address'][$z].'<br>';
                echo '<hr>';

                $companyString[$m-1]  =   "Firma nr $m \n"
                                .   $company['name'][$z]."\n"
                                .   $company['address'][$z]."\n\n";

                $m++;


            }
            /**stoper */
            if($k > 1){
                break;
            }
        }
//
//
           /**stoper */

        $subCategoryHrefIterator++;
    }


    $mainCategoryHrefIterator++;
}

/*var_dump($companyString);


$file = fopen("firmy.csv", 'a');
foreach($companyString as $fields){
    fwrite($file, $fields);
}
fclose($file);*/





    




