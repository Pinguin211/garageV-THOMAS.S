<?php

namespace App\Lib;

class Math {

    public function getNumber($number): float | false
    {
        $pattern = '/^[-+]?(\d+(\.\d*)?|\.\d+)([eE][-+]?\d+)?$/';
        $match = preg_match($pattern, str_replace(',', '.', $number), $matches);
        if ($match) {
            return floatval($matches[0]);
        } else {
            return false;
        }
    }

}

