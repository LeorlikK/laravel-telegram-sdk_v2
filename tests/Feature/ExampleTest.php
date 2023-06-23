<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Pay;
use App\Models\Product;
use App\Models\User;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use function PHPUnit\Framework\isEmpty;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $int = '0.53T';
        $res = is_numeric($int);
        dump($res);









        $this->assertTrue(true);
    }
}
