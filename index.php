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

/** @var simple_html_dom $htmlCode */
$htmlCode = file_get_html("https://infirmy.cz/");

/** @var GetElement $element */
$element = new GetElement();

/** @var array $mainCategoryHref */
$mainCategoryHref = $element->getItemLoop('h3.hp-categories__item-title > a', $htmlCode, 'href' );

/** @var array $count */
$count = $element->getItemLoop('span.badge.hp-categories__count', $htmlCode, 'innertext');
$i = 0; //foreach iterator
/**
 * 1. Foreach uses $mainCategoryHref links to get all subCategories links and count of companies
 * 2. Foreach uses $subCategoryHref links to get all companies lists links
 * 3. For loades all pages with company links
 * 4. Foreach gets Company data and saves it to $company[] array
 * 5. Foreach gets $company[] array and do with it whatever I want... now it saves data to .csv
 */
foreach($mainCategoryHref as $value1){
    //echo $count[$mainCategoryIterator++].' => '.$value.'<br><hr>';

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
    $iterator = 0;

    foreach($subCategoryHref as $value2){

        //echo $subCount[$subCountIterator++].' => '.$value.'<br>';
        /** delete spaces from count of companies in subCategories */
        $subCount[$subCountIterator] = str_replace(' ', '', $subCount[$subCountIterator]);

        /**
         * counting number of pages in one of subCategories
         */
        $pages = ((int)$subCount[$subCountIterator++]/15);

        if($pages > (int)$pages){
            $pages++;
        }

        //echo (int)$pages.'<br>';

        $htmlCode = file_get_html($value2);
        $l = 1;
        $company = [];
        //loop looking for all pages
        for($k=1;$k<=(int)$pages;$k++){
            $htmlCode = file_get_html($value2.'?page='.$k);

            $company_link = $element->getItemLoop('h3.firm__title > a', $htmlCode, 'href');

            foreach($company_link as $value4){
                //echo $l.' => '.$value4.'<br>';
                //$company[] = $value4;
                $htmlCode = file_get_html($value4);
                $company['name'] = $element->getItemLoop('h1.firm-detail__title', $htmlCode, 'innertext');
                $company['address'] = $element->getItemLoop('h2.firm-detail__address', $htmlCode, 'innertext');

                if($l >= 1){
                    break;
                }


                $l++;
            }
            $l = 0;
            foreach($company as $value5){
                //echo 'Firma nr '.$l.'<br>';
                //echo $company['name'][$l].'<br>';
                //echo $company['address'][$l].'<br>';
                //echo '<hr>';
                $companyString  =   "Firma nr $l"
                                .   $company['name'][$l]
                                .   $company['address'][$l];


                $file = fopen("firmy.csv", 'a');
                fwrite($file, $companyString);
                fclose($file);

            }
            if($k === 1){
                break;
            }
        }



        if($iterator === 5){
            break;
        }
        $iterator++;
    }
    

}






    




?>