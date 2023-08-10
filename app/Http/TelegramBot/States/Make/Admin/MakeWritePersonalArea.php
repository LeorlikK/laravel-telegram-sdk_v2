<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;

class MakeWritePersonalArea
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->text){
            if ($this->stateMake->state->v1) $this->stateMake->argumentsService->setArgument('r', $this->stateMake->state->v1);
            $user = User::find($this->stateMake->parentId);
            Telegram::copyMessage([
                'chat_id' => $user->tg_id,
                'from_chat_id' => $this->stateMake->update->message->chat->id,
                'message_id' => $this->stateMake->update->message->messageId,
            ]);
            $this->stateMake->argumentsService->setArgument('sw', 'User');

            $this->stateMake->argumentsService->er = '38';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return '20';
    }
}
