<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Pay;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::find(31);
        /**
         * @var $user User
         */
        $result = $user->countAnswerReportState();
        dump($result);


        $this->assertTrue(true);
    }
}
