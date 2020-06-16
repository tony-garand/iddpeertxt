<?php

namespace peertxt\Helpers;


use Carbon\Carbon;

class DateTimeHelper
{
    public static function DayList()
    {
        $days = [
            Carbon::MONDAY => "Monday",
            Carbon::TUESDAY => "Tuesday",
            Carbon::WEDNESDAY => "Wednesday",
            Carbon::THURSDAY => "Thursday",
            Carbon::FRIDAY => "Friday",
            Carbon::SATURDAY => "Saturday",
            Carbon::SUNDAY => "Sunday",
        ];

        return $days;
    }

    public static function TimeList()
    {
        $start = 0;
        $end = 1440;
        $times = [];

        while ($start <= $end) {
            $formDate = $start >= 780 ? date('i', $start) - 12 : date('i', $start);
            $formDate .= date(':s', $start);
            $formDate .= $start >= 720 && $start < 1440 ? " PM" : " AM";
            $times[$start] = $formDate;
            $start += 15;
        }

        return $times;
    }

    public static function convertToIntTime($time)
    {

        $arr = explode(":", $time);
//        $i = 0;
        if (count($arr) >= 2) {
            $i = $arr[0] * 60;
            $i = $i + $arr[1];
        } else {
            return $time;
        }
        return $i;
    }
}
