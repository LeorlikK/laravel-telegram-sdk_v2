<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Folder;
use App\Models\Pay;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Laravel\Facades\Telegram;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_my(): void
    {
        $now = now();
        dump($now);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        /**
         * Если я вызывают метод, который использует твис в другом классе, то какое твис он возмет?
         */
        $collection = collect([
            1 => "cl:MenuR_ac:N_fp:1",
            2 => "cl:MenuR_ac:N_fp:2",
            8 => "cl:MenuR_ac:N_fp:8",
            11 => "cl:MenuR_ac:N_fp:11",
            12 => "cl:MenuR_ac:N_fp:12",
            13 => "cl:MenuR_ac:N_fp:13",
            14 => "cl:MenuR_ac:N_fp:14",
            15 => "cl:MenuR_ac:N_fp:15",
            16 => "cl:MenuR_ac:N_fp:16",
            17 => "cl:MenuR_ac:N_fp:17",
        ]);

        $keysCollection = $collection->keys();
        $index = $keysCollection->search(8);
        dump($index);

        $this->assertTrue(true);
    }
}
