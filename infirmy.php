<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 23.01.19
 * Time: 07:55
 */
require_once 'vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';
include_once 'class/GetElement.php';
include_once 'class/GetHtmlCode.php';

/** @var  $mainCategoryHtml */
$mainCategoryHtml = GetHtmlCode::load("https://infirmy.cz"); //get html code to var

$mainCategoryLinks = [];
$mainCategoryCount = [];
/** @var  $mainCategoryLinks get all links to main categories*/
$mainCategoryLinks = GetElement::getItemLoop('h3.hp-categories__item-title > a', $mainCategoryHtml, 'href' );
/** @var  $mainCategoryCount get all counts in main categories*/
$mainCategoryCount = GetElement::getItemLoop('span.badge.hp-categories__count', $mainCategoryHtml, 'innertext');


$subCategoryLinks = [];
$subCategoryCount = [];

$mainCategoryLinksIterator = 0;
$subCategoryLinksIterator = 0;

/** Loop Map
 *  1. Get Links and Counts of main categories
 *  2. Get Links and Counts of sub categories
 */
foreach($mainCategoryLinks as $url) {
    /** @var simple_html_dom $subCategoryHtml get html code in subcategories*/
    $subCategoryHtml = GetHtmlCode::load($url);

    /** @var  $subCategoryLinks get all links to sub categories*/
    $subCategoryLinks = GetElement::getItemLoop('li.categories__item > a', $subCategoryHtml, 'href');

    /** @var  $subCategoryCount get all count in sub categories*/
    $subCategoryCount = GetElement::getItemLoop('small.categories__count', $subCategoryHtml, 'innertext');

    /** main stoper 1 = 1 mainCategory*/
    if($mainCategoryLinksIterator > 0){
        break;
    }

    echo '<br>'.$url.'<br><hr>';

    foreach($subCategoryLinks as $subUrl){

        /** sub stoper 1 = 1 subCategory*/
        if($subCategoryLinksIterator > 0){
            break;
        }

        echo $subUrl.'<br>';
        //$companyListHtml = GetHtmlCode::load($subUrl);
        $subCategoryLinksIterator++;
    }
    $subCategoryLinksIterator = 0;
    $mainCategoryLinksIterator++;
}

