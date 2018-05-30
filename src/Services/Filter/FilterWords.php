<?php

namespace App\Services\Filter;

class FilterWords{

    public function __construct(){
        
    }

    public function filterWords($sentence){

        $badWords = ['shit', 'fuck'];

        foreach($badWords as $badWord){
            if(strpos($sentence, $badWord) !== false){
                return true;
            }
        }

        return false;

    }
}