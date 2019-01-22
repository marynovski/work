<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 21.01.19
 * Time: 11:31
 */

require __DIR__ . '/vendor/autoload.php';

include_once 'simple_html_dom.php';


$html = file_get_html("https://infirmy.cz/");

/*$parentElements[2] = 'h3.hp-categories__item-title';
$childElements[2] = 'a';
$parentElements[1] = 'li.categories__item';
$childElements[1] = 'a';

GetHtmlElements::getChildByElement($parentElements, $childElements, $html, 2);

*/

/** @var simple_html_dom_node $class */
$main_cat_count = $html->find('span.badge.hp-categories__count');

/** @var simple_html_dom_node $main_cat_link */
$main_cat_link = $html->find('h3.hp-categories__item-title');




foreach($main_cat_count as $count){
    echo $count->innertext.'<br>';
}

foreach($main_cat_link as $element){

    foreach($element->find('a') as $link){
        echo $link->href.'<br>';
    }
}



foreach($html->find('h3.hp-categories__item-title') as $element) {

    foreach ($element->find('a') as $link) {

        /** @var simple_html_dom_node $html */
        $html = file_get_html($link->href);

        foreach ($html->find("li.categories__item") as $element) {

            foreach ($element->find('a') as $link) {
                echo $link->href . '<br>';
            }
        }
    }
}




    




?>