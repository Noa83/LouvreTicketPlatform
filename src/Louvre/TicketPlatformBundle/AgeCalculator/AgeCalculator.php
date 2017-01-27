<?php

namespace Louvre\TicketPlatformBundle\AgeCalculator;

class AgeCalculator
{
    public function getAgeFromBirthDate($birthDate) {
        list($year, $month, $day) = explode('-', $birthDate->format('Y-m-d'));
        $date['month'] = date('m');
        $date['day'] = date('d');
        $date['year'] = date('Y');
        $years = $date['year'] - $year;
        if ($month >= $date['month']) {
            if ($month == $date['month']) {
                if ($day > $date['day']) $years--;
            } else {
                $years--;
            }
        }
        return $years;
    }
}