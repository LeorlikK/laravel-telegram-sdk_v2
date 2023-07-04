<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaFeedback;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Report;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminReportsButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $reports = Report::with('userFrom')
            ->where('type', 'report')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $reports->total();

        foreach ($reports->items() as $report) {
            /**
             * @var $report Report
             */
            $buttons->add([
                ['text' => ($report->state ? "" : "❗️") . $report->userFrom->username . "(".(AreaFeedback::theme($report->theme).")"),
                    'callback_data' =>
                        "cl:$argumentsService->cl".'_'.
                        "sw:Report".'_'.
                        "p:$argumentsService->p".'_'.
                        "fp:$report->id".'_'
                ],
            ]);
        }

        $buttons = self::paginateNavigation($buttons, $totalFolder, $perPage, $argumentsService, $buttonMinus, $buttonPlus);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function reportButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $report = Report::with('userFrom')->find($argumentsService->fp);
        $caption = '👥 User: ' . "\n\r" .
            '    tg id - ' . $report->userFrom->tg_id . "\n\r" .
            '    username - ' . $report->userFrom->username . "\n\r".
            '    first name - ' . $report->userFrom->first_name . "\n\r".
            '    last name - ' . $report->userFrom->last_name . "\n\r".
            '    language - ' . $report->userFrom->language . "\n\r".
            '    role - ' . $report->userFrom->role->name . "\n\r".
            '    mail - ' . (str_replace('_', '', $report->userFrom->mail)) . "\n\r".
            '    number - ' . $report->userFrom->number . "\n\r".
            '    edit - ' . $report->userFrom->edit . "\n\r".
            '    is premium - ' . $report->userFrom->is_premium . "\n\r".
            '    is blocked - ' . $report->userFrom->is_blocked . "\n\r" . "\n\r".
            '📇 Theme: ' . (AreaFeedback::theme($report->theme)) . "\n\r".
            '✉️ Message: ' . $report->message . "\n\r";
        $caption = Str::limit($caption, 1024);

        $buttons->add([
            ['text' => '💬 Answer',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:ChoiceAnswer".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
            ],
        ]);
        $buttons->add([
            ['text' => '↗️ User',
                'callback_data' =>
                    "cl:AdminUsers".'_'.
                    "sw:User".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:".($report->userFrom->id).'_'.
                    "r:$argumentsService->fp"
            ],
        ]);
        $buttons->add([
            ['text' => '❌ Delete Report',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:Delete".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return [$buttons, $caption];
    }

    public static function choiceAnswerReportButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '📨 Answer to personal area',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:Answer".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
            ],
        ]);
        $buttons->add([
            ['text' => '✉️ Write to chat',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:AnswerChat".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function answerReportButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function deleteReportButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '✔️ Confirm',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:MakeDelete".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'.
                    'v:del'
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }
}
