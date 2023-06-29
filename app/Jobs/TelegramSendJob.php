<?php

namespace App\Jobs;

use App\Http\TelegramBot\Aliases;
use App\Http\TelegramBot\Auth\Authentication;
use App\Http\TelegramBot\Exceptions\UserAlertException;
use App\Http\TelegramBot\Info\Alerts\InputAlert;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\States\StateMake;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Objects\Update;

class TelegramSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Update $update;
    protected ArgumentsService $argumentsService;
    protected string $action;
    /**
     * Create a new job instance.
     */
    public function __construct(Update $update, string $arguments, string $action)
    {
        $this->update = $update;
        $this->argumentsService = new ArgumentsService($arguments);
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $authentication = new Authentication();
        $user = $authentication->handle($this->update);
        if ($user->is_blocked){
            $this->argumentsService->er = '10';
            (new InputAlert($user, $this->update,
                $this->argumentsService))->handleCallbackQuery();
        }else{
            if ($this->action === 'send'){
                $user->state()->delete();
                $className = Aliases::getFullNameSpace($this->argumentsService->cl);
                $createdClass = new $className($user, $this->update, $this->argumentsService);
                $createdClass->sendCreate();
            }elseif ($this->action === 'edit'){
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
