<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TelegramBot\Services\RemainingTimeService;
use App\Models\Folder;
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
        $offset = 644021339;
        $token = '5373817318:AAF4tHynVvmWuQUBBcb10r7JPlAVBsU2u9I';
        $response = Http::get("https://api.telegram.org/bot$token/getUpdates?offset=$offset");
        $data = $response->json();

        if ($data['ok']) {
            $updates = $data['result'];
            dump($updates);
            foreach ($updates as $update) {
                $offset = $update['update_id'] + 1;

                // Обработайте полученное обновление
                // Ваш код для обработки обновлений здесь
            }
        }

//        Telegram::sendMessage([
//            'chat_id' => '1059208615',
//            'text' => '111',
//        ]);

//        $users = User::where('role_id', 2)->update(['role_id' => 2]);
//        dump($users);

    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $users = User::whereIn('id', [30, 31])->get();

        $users->each(function ($user){
            $this->user = Cache::remember($user['tg_id'], now()->addMinutes(20), function () use($user){

                /**
                 * @var $user User
                 */
                $user = User::where('tg_id', $user['tg_id'])->first();
//                $user->load('role');

                $user->updatePurchasedProducts();

                return $user;
            });
        });


        $user30 = 68448;
        $user31 = 1059208615;

        $keys = $users->pluck('tg_id')->toArray();
        $data = Cache::many($keys);
//        $keys = ['68448', '1059208615', 'key3'];
//        Cache::deleteMultiple($keys);
        $res = Cache::many(['68448', '1059208615']);
        dump($res);


//        $user = User::find(31);
//        if (!$user->pays->isEmpty()){
//            dump($user->pays);
//        }else{
//            dump('nno');
//        }

        $this->assertTrue(true);
    }
}
