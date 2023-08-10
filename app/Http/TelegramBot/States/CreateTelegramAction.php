<?php

namespace App\Http\TelegramBot\States;

use App\Http\TelegramBot\Aliases;
use App\Http\TelegramBot\Auth\Authentication;
use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Services\ArgumentsService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Objects\Update;

class CreateTelegramAction
{
    protected Update $update;
    protected ArgumentsService $argumentsService;
    protected string $action;

    public function __construct(Update $update, string $arguments, string $action)
    {
//        Log::channel('test')->info($arguments);
        $this->update = $update;
        $this->argumentsService = new ArgumentsService($arguments);
        $this->action = $action;
    }


    public function __invoke(): void
    {
        $authentication = new Authentication();
        $user = $authentication->handle($this->update);
        if ($user && $user->is_blocked){
            $this->argumentsService->er = '10';
            (new InputAlert($user, $this->update,
                $this->argumentsService))->handleCallbackQuery();
        }elseif($user && !$user->is_blocked){
            if ($this->action === 'send'){
                $user->state()->delete();
                $className = Aliases::getFullNameSpace($this->argumentsService->cl);
                $createdClass = new $className($user, $this->update, $this->argumentsService);
                $createdClass->sendCreate();
            }elseif ($this->action === 'edit'){
                if ($this->argumentsService->s) $user->state()->delete();
                $className = Aliases::getFullNameSpace($this->argumentsService->cl);
                $createdClass = new $className($user, $this->update, $this->argumentsService);
                $createdClass->handleCallbackQuery();
            }elseif ($this->action === 'state'){
                if ($state = $user->state){
                    (new StateMake($this->update, $user, $this->argumentsService, $state))->make();
                }
            }
        }
    }
}
