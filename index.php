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

/** @var simple_html_dom $html */
$html = file_get_html("https://infirmy.cz/");

/** @var GetElement $element */
$element = new GetElement();

/** @var array $link */
$link = $element->getItemLoop('h3.hp-categories__item-title > a', $html, 'href' );
$count = $element->getItemLoop('span.badge.hp-categories__count', $html, 'innertext');
$i = 0;
foreach($link as $value){
    echo $count[$i++].' => '.$value.'<br>';

    /*$html = file_get_html($value);

    $sub_link = $element->getItemLoop('li.categories__item > a', $html, 'href');

    foreach($sub_link as $value){

    }*/

}






    




?>