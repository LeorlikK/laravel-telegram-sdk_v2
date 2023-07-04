<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Folder;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $folder = Folder::find(2);
        $carbonTime = Carbon::parse($folder->display);

        $str = '';
        $lostTime = $carbonTime->diff(now());
        $str .= $lostTime->y != 0 ? " $lostTime->y" . " y" : "";
        $str .= $lostTime->m != 0 ? " $lostTime->m" . " m" : "";
        $str .= $lostTime->d != 0 ? " $lostTime->d" . " d" : "";
        $str .= $lostTime->h != 0 ? "$lostTime->h" . ":" : "";
        $str .= $lostTime->i != 0 ? "$lostTime->i" . ":" : "";
        $str .= $lostTime->s != 0 ? "$lostTime->s" : "";
//        $res = $lostTime->format('%Y дней %m дней %d дней, %h часов, %i минут, %s секунд');
        dump($str);

//        $targetDate = Carbon::parse('2023-07-10 12:00:00'); // Замените на вашу целевую дату

//        $currentTime = Carbon::now();
//        $remainingTime = $currentTime->diff($targetDate);

// Получение оставшегося времени в читаемом формате
//        $remainingTimeString = $remainingTime->format('%d дней, %h часов, %i минут, %s секунд');

//        dump($remainingTimeString);

        $this->assertTrue(true);
    }
}
