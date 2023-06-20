<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function PHPUnit\Framework\isEmpty;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $error = 1;
        $error = $error ?? 'lala';
        dump($error);
//        if (empty($error)){
//            dump('YES');
//        }

        $this->assertTrue(true);
    }
}
