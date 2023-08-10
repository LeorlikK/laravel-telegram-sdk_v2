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
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_my(): void
    {
        $this->assertTrue(true);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        /**
         * Если я вызывают метод, который использует твис в другом классе, то какое твис он возмет?
         */

        $this->assertTrue(true);
    }
}
