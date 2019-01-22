<?php
/**
 * Created by PhpStorm.
 * User: programista
 * Date: 22.01.19
 * Time: 09:01
 */

class GetElement
{
    private $selector;
    private $html;

    /**
     * @param $selector
     * @param $html
     * @return simple_html_dom_node
     */
    private function getBySelector($selector, $html){
        /** @var simple_html_dom_node $element */
        $element = $html->find($selector);

        if($element !== NULL && !empty($element) ){
            return $element;
        }else{
            echo "Nie znaleziono żadnych elementów!";
            die();
        }

    }

    /**
     * @param Element $element
     * @param $attribute
     * @return array
     */
    public function getItemLoop($selector, $html, $attribute){
        /** @var simple_html_dom_node $element */
        $element = $this->getBySelector($selector, $html);

        $elements = [];

        foreach($element as $value){
            $elements[] = $value->{$attribute};
        }

        return $elements;
    }

}