<?php

namespace App\Services\Date;

class DateDifference{
    
    public function calcDiff($time1, $time2){

        $diff = date_diff($time1, $time2);
        $output;

        if($diff->format('%d') == 1){
            // days
            $output= $diff->format('%d day');
        } else if($diff->format('%d') >= 2){
            // Day
            $output= $diff->format('%d days');
        } else if($diff->format('%i') == 0){
            // Echo seconds
            $output= $diff->format('%s seconds');
        } else if($diff->format('%h') == 0){
            // echo minutes
            $output= $diff->format('%i minutes');        
        } else {
            // echo hours
            $output = $diff->format('%h hours');          
        }

        return $output;
        
    }

}