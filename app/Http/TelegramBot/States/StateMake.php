<?php

namespace App\Http\TelegramBot\States;

use App\Http\TelegramBot\Info\Exceptions\InputException;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Http\TelegramBot\States\Make\Admin\MakeAddPayForUser;
use App\Http\TelegramBot\States\Make\Admin\MakeBlockUser;
use App\Http\TelegramBot\States\Make\Admin\MakeChangeRoleName;
use App\Http\TelegramBot\States\Make\Admin\MakeChangeRoleUser;
use App\Http\TelegramBot\States\Make\Admin\MakeChangeRoleValue;
use App\Http\TelegramBot\States\Make\Admin\MakeCreateRole;
use App\Http\TelegramBot\States\Make\Admin\MakeDeletePayForUser;
use App\Http\TelegramBot\States\Make\Admin\MakeDeleteRole;
use App\Http\TelegramBot\States\Make\Admin\MakeFindByTgId;
use App\Http\TelegramBot\States\Make\Admin\MakeUnlockUser;
use App\Http\TelegramBot\States\Make\Admin\MakeWriteUser;
use App\Http\TelegramBot\States\Make\MakeAddPayBasket;
use App\Http\TelegramBot\States\Make\MakeChangeCaptionFolder;
use App\Http\TelegramBot\States\Make\MakeChangeEmojiFolder;
use App\Http\TelegramBot\States\Make\MakeChangeImageFolder;
use App\Http\TelegramBot\States\Make\MakeChangeNameFolder;
use App\Http\TelegramBot\States\Make\MakeChangePeriodPay;
use App\Http\TelegramBot\States\Make\MakeChangePricePay;
use App\Http\TelegramBot\States\Make\MakeChangeSecrecyFolder;
use App\Http\TelegramBot\States\Make\MakeChangeSortedFolder;
use App\Http\TelegramBot\States\Make\MakeChangeVisibilityFolder;
use App\Http\TelegramBot\States\Make\MakeCreateFolder;
use App\Http\TelegramBot\States\Make\MakeCreateSpecialClass;
use App\Http\TelegramBot\States\Make\MakeDeleteFolder;
use App\Http\TelegramBot\States\Make\MakeDeletePayBasket;
use App\Http\TelegramBot\States\Make\MakeDeletePayProduct;
use App\Http\TelegramBot\States\Make\MakePayProduct;
use App\Http\TelegramBot\States\Make\Reports\MakeAnswerReport;
use App\Http\TelegramBot\States\Make\Reports\MakeCreateReport;
use App\Http\TelegramBot\States\Make\Reports\MakeDeleterReport;
use App\Models\Folder;
use App\Models\Pay;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

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

            // Admin Panel
            case $this->state->action === 'ChangeRoleUserC':
                $makeClass = new MakeChangeRoleUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'UserUnlockC':
                $makeClass = new MakeUnlockUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'UserBlockC':
                $makeClass = new MakeBlockUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'CreateRoleC':
                $makeClass = new MakeCreateRole($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeRoleNameC':
                $makeClass = new MakeChangeRoleName($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangeRoleValueC':
                $makeClass = new MakeChangeRoleValue($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeleteRoleValueC':
                $makeClass = new MakeDeleteRole($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'FindByIdC':
                $makeClass = new MakeFindByTgId($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                break;

            case $this->state->action === 'AddPayForUserC':
                $makeClass = new MakeAddPayForUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeletePayForUserC':
                $makeClass = new MakeDeletePayForUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'WriteUserC':
                $makeClass = new MakeWriteUser($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

//            case $this->state->action === 'WritePersonalAreaC':
//                $makeClass = new MakeDeletePayForUser($this);
//                $error = $makeClass->make();
//                $arguments = [
//                    'callbackClassName' => $this->state->TabClass,
//                    'update' => $this->reworkUpdate($this->chatId)
//                ];
//                $this->argumentsService->ac = 'N';
//                $this->argumentsService->fp = $this->parentId;
//                break;

            // PAY
            case $this->state->action === 'PayProductC':
                $makeClass = new MakePayProduct($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'ChangePeriodPayF':
                $makeClass = new MakeChangePeriodPay($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case str_starts_with($this->state->action, 'ChangePricePayF'):
                $makeClass = new MakeChangePricePay($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'AddPayF':
                $makeClass = new MakeAddPayBasket($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeletePayF':
                $makeClass = new MakeDeletePayBasket($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeletePayM':
                $makeClass = new MakeDeletePayProduct($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            // Reports
            case $this->state->action === 'CreateReportC':
                $makeClass = new MakeCreateReport($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = 0;
                break;

            case $this->state->action === 'AnswerReportUserC':
                $makeClass = new MakeAnswerReport($this);
                $error = $makeClass->make();
                $arguments = [
                    'callbackClassName' => $this->state->TabClass,
                    'update' => $this->reworkUpdate($this->chatId)
                ];
                $this->argumentsService->ac = 'N';
                $this->argumentsService->fp = $this->parentId;
                break;

            case $this->state->action === 'DeleteReportUserC':
                $makeClass = new MakeDeleterReport($this);
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
            $this->argumentsService->er = $error;
            $this->deleteMessage($this->chatId, $this->messageId);
            (new InputException($this->user, $this->update,
                $this->argumentsService))->handleCallbackQuery();
        }
    }

    function deleteFolderChildren($folderId, &$link): void
    {
        $folder = Folder::find($folderId);

        if (!$folder){
            return;
        }

        $children = Folder::where('parentId', $folderId)->get();

        foreach ($children as $child) {
            $this->deleteFolderChildren($child->id,$link);
        }

        if ($folder->products->isNotEmpty()) {
            $link = 17;
            return;
        }

        if (!$link){
            $folder->product?->folders()->detach();
            $productId = $folder->product?->id;
            if ($productId){
                $pays = Pay::with('user')->where('product_id', $productId)->get();
                Pay::where('product_id', $productId)->delete();
                $usersTgId = $pays->pluck('user.tg_id');
                Cache::deleteMultiple($usersTgId);

                Cache::forget($this->user->tg_id);
                $this->user->updatePurchasedProducts();
                $this->user->updateCache($this->user);
            }
            $folder->product?->delete();

            $folder->delete();
        }
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
