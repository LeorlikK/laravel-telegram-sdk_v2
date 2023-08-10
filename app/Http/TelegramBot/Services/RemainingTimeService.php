<?php

namespace App\Http\TelegramBot\Services;

use Illuminate\Support\Carbon;

class RemainingTimeService
{
    public function getRemainingTime($diff): string
    {
        $remainingTime = '';
        if ($diff->days >= 365){
            $year = floor($diff->days / 365);
            $remainingTime = '> ' . $year . ' года';
        } elseif ($diff->days >= 60){
            $months = floor($diff->days / 60);
            $remainingTime = '> ' . $months . ' месяцев';
        } elseif ($diff->days >= 30) {
            $months = floor($diff->days / 30);
            $remainingTime = '> ' . $months . ' месяца';
        } elseif ($diff->days >= 7) {
            $weeks = floor($diff->days / 7);
            $remainingTime = '> ' . $weeks . ' недели';
        } elseif ($diff->days >= 1) {
            $remainingTime = $diff->days . ' ' . $this->getDaySuffix($diff->days);
        } elseif ($diff->h >= 1) {
            $remainingTime = $diff->h . ' ' . $this->getHourSuffix($diff->h);
        } else {
            $remainingTime = 'Меньше часа';
        }

        return $remainingTime;
    }

    private function getDaySuffix($days): string
    {
        $lastDigit = $days % 10;

        if ($lastDigit == 1 && $days != 11) {
            return 'день';
        } elseif (in_array($lastDigit, [2, 3, 4]) && !in_array($days, [12, 13, 14])) {
            return 'дня';
        } else {
            return 'дней';
        }
    }

    private function getHourSuffix($hours): string
    {
        $lastDigit = $hours % 10;

        if ($lastDigit == 1 && $hours != 11) {
            return 'час';
        } elseif (in_array($lastDigit, [2, 3, 4]) && !in_array($hours, [12, 13, 14])) {
            return 'часа';
        } else {
            return 'часов';
        }
    }

    public static function getTimeFromHours(string|int $hours): string
    {
        $time = Carbon::make($hours) ? $hours : now()->addHours($hours);
        $carbonTime = Carbon::parse($time);
        $str = '';
        $lostTime = $carbonTime->diff(now());
        $str .= $lostTime->y != 0 ? " $lostTime->y" . "y " : "";
        $str .= $lostTime->m != 0 ? " $lostTime->m" . "m " : "";
        $str .= $lostTime->d != 0 ? " $lostTime->d" . "d " : "";
        $str .= $lostTime->h != 0 ? "$lostTime->h" . "h " : "";
        $str .= $lostTime->i != 0 ? "$lostTime->i" . "m " : "";
        $str .= $lostTime->s != 0 ? "$lostTime->s" . "s " : "";

        return $str;
    }
}
