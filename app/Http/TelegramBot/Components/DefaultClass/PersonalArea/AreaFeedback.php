<?php

namespace App\Http\TelegramBot\Components\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea\AreaFeedbackButtons;
use App\Http\TelegramBot\DefaultClass;
use App\Http\TelegramBot\States\StateCreate;
use App\Models\Report;

class AreaFeedback extends DefaultClass
{
    public function main(): array
    {
        $buttons = collect();

        switch ($this->argumentsService->sw){
            case 'Write':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('sw' , 'Write');
                $this->argumentsService->setArgument('m' , 'C');
                $this->argumentsService->v = $this->argumentsService->v ?? 0;
                $this->argumentsService->fp = $this->argumentsService->v;
                StateCreate::createState($this->update, $this->user, $this->argumentsService, 'CreateReport' . $this->argumentsService->m);
                $this->argumentsService->fp = null;
                $caption = "Опишите вашу проблему и мы постараемся её решить";
                $buttons = AreaFeedbackButtons::writeButtons($buttons, $this->argumentsService);
                break;
            case 'Answer':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $buttons = AreaFeedbackButtons::answerFromAdminButtons($buttons, $this->argumentsService, $this->user);
                break;
            case 'Report':
                Report::where('id', $this->argumentsService->fp)->where('state', false)->update(['state' => true]);
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                [$buttons, $caption] = AreaFeedbackButtons::reportButtons($buttons, $this->argumentsService);
                break;
            case 'AnswerAdmin':
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , class_basename($this));
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('sw' , 'AnswerAdmin');
                $this->argumentsService->setArgument('m' , 'C');
                if ($this->argumentsService->fp){
                    $fp = $this->argumentsService->fp;
                    $this->argumentsService->fp = $this->argumentsService->v;
                    StateCreate::createState($this->update, $this->user, $this->argumentsService, 'CreateReport' . $this->argumentsService->m);
                    $this->argumentsService->fp = $fp;
                }
                $buttons = AreaFeedbackButtons::answerReportButtons($buttons, $this->argumentsService);
                $caption = $this->caption("Напишите ответ администратору");
                break;
            default:
                $this->argumentsService->setArgument('cl' , class_basename($this));
                $this->argumentsService->setArgument('bk' , 'AreaMenu');
                $this->argumentsService->setArgument('bkS' , class_basename($this));
                $this->argumentsService->setArgument('fp' , null);
                $this->argumentsService->setArgument('p' , null);
                $this->argumentsService->setArgument('v' , null);
                $buttons = AreaFeedbackButtons::defaultButtons($buttons, $this->argumentsService, $this->user);
                break;
        }

        $caption = $caption ?? $this->caption('');
        $photo = $photo ?? $this->photo(null);
        $reply_markup = $reply_markup ?? $this->replyMarkup($buttons);

        return [$photo, $caption, $reply_markup];
    }

    public function handleCallbackQuery(): void
    {
        switch ($this->argumentsService->sw){
            default:
                $this->callbackUpdate();
                break;
        }
    }

    public static function theme($themeId): string
    {
        $theme = [
            '0' => 'Без темы',
            '1' => 'Ошибка с оплатой крабов',
            '2' => 'Я краб',
            '3' => 'Кто-то краб',
            '4' => 'У меня в роду были крабы',
            '5' => 'Мама говорит я краб',
            '6' => 'Другое...',
        ];

        return $theme[$themeId];
    }
}
