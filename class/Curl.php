<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 21.01.19
 * Time: 11:54
 */

class Curl
{
    private $url;
    private $handle;


    public function __construct($url)
    {
        $this->url = $url;
        $this->handle = curl_init($this->url);


    }

    public function getHtml(){
        $html = curl_exec($this->handle);
        return $html;

    }

    public function __destruct()
    {
        curl_close($this->handle);
    }


}