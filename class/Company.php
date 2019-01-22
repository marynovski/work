<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 22.01.19
 * Time: 10:59
 */

class Company
{
    private $name;
    private $address;
    private $mailCode;
    private $city;
    private $phone = [];
    private $email;
    private $web;
    private $categories = [];

    public function __construct($name, $address, $mailCode, $city, $phone, $email, $web, $categories){
        $this->name = $name;
        $this->address = $address;
        $this->mailCode = $mailCode;
        $this->city = $city;
        $this->phone = $phone;
        $this->email = $email;
        $this->web = $web;
        $this->categories = $categories;
    }



}