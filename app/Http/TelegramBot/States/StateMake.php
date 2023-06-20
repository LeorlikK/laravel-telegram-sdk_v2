<?php

namespace App\Http\TelegramBot\States;

use App\Http\TelegramBot\Exceptions\UserInputException;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\States\Make\MakeChangeCaptionFolder;
use App\Http\TelegramBot\States\Make\MakeChangeEmojiFolder;
use App\Http\TelegramBot\States\Make\MakeChangeImageFolder;
use App\Http\TelegramBot\States\Make\MakeChangeNameFolder;
use App\Http\TelegramBot\States\Make\MakeChangeSecrecyFolder;
use App\Http\TelegramBot\States\Make\MakeChangeSortedFolder;
use App\Http\TelegramBot\States\Make\MakeChangeVisibilityFolder;
use App\Http\TelegramBot\States\Make\MakeCreateFolder;
use App\Http\TelegramBot\States\Make\MakeCreateSpecialClass;
use App\Http\TelegramBot\States\Make\MakeDeleteFolder;
use App\Models\Button;
use App\Models\Folder;
use App\Models\State;
use App\Models\Tab;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use function PHPUnit\Framework\isEmpty;

class StateMake
{
    public Update $update;
    public User $user;
    public ArgumentsService $argumentsService;
    public State $state;

    public ?string $text;
    public int $messageId;
    public int $chatId;
    public int $parentId;
    public int $sortedId;

    public function __construct(
        Update $update,
        User $user,
        ArgumentsService $argumentsService,
        State $state,
    )
    {
        $this->update = $update;
        $this->user = $user;
        $this->argumentsService = $argumentsService;
        $this->state = $state;
    }

    public function make(): void
    {
        $this->text = $this->update->getMessage()->get('text');
        $this->messageId = $this->update->getMessage()->get('message_id');
        $this->chatId = $this->update->getChat()->get('id');
        $this->parentId = $this->state->parentId ?? 0;
        $this->sortedId = 1;

        switch (true) {
            case $this->state->action === 'CreateF':
                $makeClass = new MakeCreateFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeNameF':
                $makeClass = new MakeChangeNameFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeEmojiF':
                $makeClass = new MakeChangeEmojiFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeCaptionF':
                $makeClass = new MakeChangeCaptionFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeImageF':
                $makeClass = new MakeChangeImageFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeSecrecyF':
                $makeClass = new MakeChangeSecrecyFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeVisibilityF':
                $makeClass = new MakeChangeVisibilityFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeSortedF':
                $makeClass = new MakeChangeSortedFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->update
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeleteF':
                $makeClass = new MakeDeleteFolder($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case str_starts_with($this->state->action, 'SClass'):
                $makeClass = new MakeCreateSpecialClass($this);
                $error = $makeClass->make();

                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;



            default:
                $error = 'Action not found';
                break;
        }

        if (empty($error)){
            $this->user->state()->delete();
            $this->deleteMessage($this->chatId, $this->messageId);
            $this->redirectBeforeCreate($arguments);
        }else{
            $this->deleteMessage($this->chatId, $this->messageId);
            UserInputException::sendError($this->update, $error);
        }
    }

    function deleteFolderChildren($folderId): void
    {
        $folder = Folder::find($folderId);

        if (!$folder){
            return;
        }

        $children = Folder::where('parentId', $folderId)->get();

        foreach ($children as $child) {
            $this->deleteFolderChildren($child->id);
        }

        $folder->delete();
    }

    public function deleteMessage(string $chatId, string $messageId): void
    {
        if ($this->state->messageId != $this->update->getMessage()->get('message_id')){
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);
        }
    }

    public function reworkUpdate(string $chatId):Update
    {
        $from = $this->update->getMessage()->get('from');
        $from = $from->toArray();

        return new Update([
            'message' => new Message([
                'message_id' => $this->state->messageId,
                'chat' => new Chat(['id' => $chatId]),
                'from' => new \Telegram\Bot\Objects\User($from)
            ])
        ]);
    }

    public function redirectBeforeCreate(array $arguments): void
    {
        $callbackClass = new $arguments['callbackClassName']($this->user, $arguments['update'], $this->argumentsService);
        $callbackClass->handleCallbackQuery();
    }
}
