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


$html = file_get_html("https://infirmy.cz/");
$element = new GetElement();
$link = $element->getItemLoop('h3.hp-categories__item-title', $html, 'href' );

foreach($link as $value){
    echo $value.'<br> GIT';
}



/** @var simple_html_dom_node $class */
$main_cat_count = $html->find('span.badge.hp-categories__count');

/** @var simple_html_dom_node $main_cat_link */
$main_cat_link = $html->find('h3.hp-categories__item-title');

echo '<div class="main_categories">';
foreach($main_cat_count as $categoryCount){
    echo $categoryCount->innertext.'<br>';

}
echo '</div>';
echo '<div class="main_categories">';

foreach($main_cat_link as $element){

    foreach($element->find('a') as $categoryLink){
        echo $categoryLink->href.'<br>';

    }
}
echo '</div>';

echo '<div style="clear: both;"></div>';

/** @var simple_html_dom_node $sub_cat_link */
$sub_cat_link = $html->find('h3.hp-categories__item-title');

foreach($sub_cat_link as $element) {

        foreach ($element->find('a') as $link) {

            /** @var simple_html_dom_node $html */
            $html = file_get_html($link->href);

            /** @var simple_html_dom_node $sub_cat_count */
            $sub_cat_count = $html->find('small.categories__count');
            /** @var simple_html_dom_node $sub_cat_link */
            $sub_cat_link = $html->find("li.categories__item");
            echo '<div>';
            echo '<div class="sub_categories">';

            foreach($sub_cat_count as $subCategoryCount){
                echo $subCategoryCount->innertext.'<br>';

            }
            echo '</div><div class="sub_categories">';

            foreach ($sub_cat_link as $element) {

                foreach ($element->find('a') as $link) {
                    echo $link->href . '<br>';
                }
            }
            echo '</div>';
            echo '</div>';

        }
}




    




?>