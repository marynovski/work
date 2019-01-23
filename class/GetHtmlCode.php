<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 23.01.19
 * Time: 08:03
 */

abstract class GetHtmlCode{

    public static function load($url){
        $htmlCode = file_get_html($url);
        return $htmlCode;
    }

}