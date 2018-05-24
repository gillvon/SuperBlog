<?php

namespace App\Services\Date;
use App\Services\Date\DateDifference;

class Date{
    private $dateDifference;

    public function __construct(DateDifference $dateDifference){
        $this->dateDifference = $dateDifference;
    }

    public function Difference($time1, $time2){
        return $this->dateDifference->calcDiff($time1, $time2);
    }
}