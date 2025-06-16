<?php

namespace Support\Traits;

use Illuminate\Support\Carbon;
use Config;

trait CommonTrait {

    public function convertDateFormat($date, $format='', $newFormat='Y-m-d')
    {
        $currentFormat = empty($format) ? Config::get('constants.dateFormat') : $format;
        return Carbon::createFromFormat($currentFormat, $date)->format($newFormat);
    }

}