<?php

namespace App\Services\Filter;

use App\Entity\Blogposts;

use App\Services\Filter\FilterWords;

class Filter{

    private $filterWords;

    public function __construct(FilterWords $filterWords){
        $this->filterWords = $filterWords;
    }

    public function hasBadWords($blogpost){

        $case1 = $this->filterWords->filterWords($blogpost->getTitle());
        $case2 = $this->filterWords->filterWords($blogpost->getDescription());
        $case3 = $this->filterWords->filterWords($blogpost->getContent());

        return $case1 || $case2 || $case3;
    }
}