<?php

namespace App\Traits;

use http\Exception\InvalidArgumentException;

trait OperationsHelper
{
    // Your reusable methods go here
    function getBuildIdColumnName($type){
        $type = strtolower($type);
        if ($type == 'milling' || $type ==2 ) {
            return 'milling_build_id';
        } else if ($type == '3dprinting'|| $type ==3 ) {
            return 'printing_build_id';
        } else if ($type == 'pressing' || $type ==5 ) {
           return 'pressing_build_id';
        } else if ($type == 'sintering' || $type ==4) {
           return 'sintering_build_id';
        }else {
            return null;
        }
}
}
