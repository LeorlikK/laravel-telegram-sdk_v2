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
                'role_id' => 1,
                'mail' => $from->mail,
                'number' => $from->number,
                'is_administrator' => $from->is_administrator ?? false, # Заменить на false
                'is_premium' => $from->is_premium ?? false,
                'is_blocked' => $from->is_blocked ?? false,
            ];

            /**
             * @var $user User
             */
            $this->user = Cache::remember($user['tg_id'], now()->addMinutes(20), function () use($user){
                $user = User::firstOrCreate(['tg_id' => $user['tg_id']], $user);
                $user->load('role');
                return $user;
            });
        }

        return $this->user;
    }
}
