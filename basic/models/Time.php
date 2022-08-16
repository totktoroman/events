<?php

namespace app\models;

use Yii;
use DateTime;
use DateTimeZone;
class Time{
   
    function getDatetimeNow() {
        $tz_object = new DateTimeZone('Asia/Irkutsk');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }

}
