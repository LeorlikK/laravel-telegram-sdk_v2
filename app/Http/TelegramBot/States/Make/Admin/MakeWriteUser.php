<?php

namespace App\Http\TelegramBot\States\Make\Admin;

use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\States\StateMake;
use App\Models\Report;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;

class MakeWriteUser
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

            if ($this->stateMake->state->v2 === 'Report'){
                $report = Report::with('userFrom')->find($this->stateMake->parentId);
                $userId = $report->userFrom->tg_id;
            }elseif ($this->stateMake->state->v2 === 'User'){
                $user = User::find($this->stateMake->parentId);
                $userId = $user->tg_id;
            }else return '21';

            Telegram::copyMessage([
                'chat_id' => $userId,
                'from_chat_id' => $this->stateMake->update->message->chat->id,
                'message_id' => $this->stateMake->update->message->messageId,
            ]);
            if ($this->stateMake->state->v2) $this->stateMake->argumentsService->setArgument('sw', $this->stateMake->state->v2);

            $this->stateMake->argumentsService->er = '39';
            (new InputAlert($this->stateMake->user, $this->stateMake->update,
                $this->stateMake->argumentsService))->handleCallbackQuery();
            return null;
        }

        return '20';
    }
}
