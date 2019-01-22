<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 21.01.19
 * Time: 12:29
 */

abstract class GetHtmlElements
{

    /**
     * @param string $class
     * @param string $html
     * @param int $loops
     */
    public static function getByElement(string $class, string $html, int $loops)
    {
        foreach ($html->find($class) as $element) {
            echo $element->href . '<br>';
        }
    }

    public static function getChildByElement($parentElement = [], $childElement = array(), $html, $count)
    {
        foreach ($html->find($parentElement[$count]) as $element) {

            foreach ($element->find($childElement[$count]) as $link) {

                $html = file_get_html($link->href);
                if($count > 1){
                    $count--;
                    self::getChildByElement($parentElement, $childElement, $html, $count);

                }else{
                    echo $link->href.'<br>';
                }

            }
        }


    }
}