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
        $user30 = 68448;
        $user31 = 1059208615;
        $user32 = 1434698404;
        $user = Cache::get('1059208615');
        dump($user);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        /**
         * Если я вызывают метод, который использует твис в другом классе, то какое твис он возмет?
         */
        $roleV = 10;

        $folders = Folder::with(['buttons', 'product'])
            ->where('blocked', '!=', true)
            ->orWhere(function ($query) use ($roleV) {
                $query->where('blocked', true)
                    ->where('visibility', '>', $roleV);
            })
            ->orWhere(function ($query) use ($roleV) {
                $query->where('blocked', true)
                    ->where('visibility', null)
                    ->orWhere('visibility', '>', $roleV);
            })

            ->get();
        dump($folders->pluck('id'));



        $this->assertTrue(true);
    }
}
