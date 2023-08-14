<?php

namespace App\Http\TelegramBot\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Objects\Update;

class Authentication
{
    public User|null $user = null;

    public function handle(Update $update): User|null
    {
        if ($update->isType('callback_query')) $from = $update->callbackQuery->get('from');
        else $from = $update->getMessage()->get('from');

        if ($from){
            $user = [
                'tg_id' => $from->id,
                'username' => $from->id,
                'first_name' => $from->first_name,
                'last_name' => $from->last_name ?? null,
                'language' => $from->language_code,
                'role_id' => 2,
                'mail' => $from->mail,
                'number' => $from->number,
                'is_blocked' => $from->is_blocked ?? false,
            ];


            $this->user = Cache::remember($user['tg_id'], now()->addMinutes(20), function () use($user){

                /**
                 * @var $user User
                 */
                $user = User::firstOrCreate(['tg_id' => $user['tg_id']], $user);
                $user->updatePurchasedProducts();

                return $user;
            });
        }

        return $this->user;
    }
}
