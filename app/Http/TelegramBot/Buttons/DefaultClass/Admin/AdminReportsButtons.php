<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\Admin;

use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaFeedback;
use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Report;
use Illuminate\Support\Collection;

class AdminReportsButtons extends PaginateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $reports = Report::with('user')
            ->where('type', 'report')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $reports->total();

        foreach ($reports->items() as $report) {
            /**
             * @var $report Report
             */
            $buttons->add([
                ['text' => ($report->state ? "" : "❗️") . $report->user->username . "(".(AreaFeedback::theme($report->theme).")"),
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
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function reportButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $report = Report::with('user')->find($argumentsService->fp);
        $caption = '👥 User: ' . "\n\r" .
            '    tg id - ' . $report->user->tg_id . "\n\r" .
            '    username - ' . $report->user->username . "\n\r".
            '    first name - ' . $report->user->first_name . "\n\r".
            '    last name - ' . $report->user->last_name . "\n\r".
            '    language - ' . $report->user->language . "\n\r".
            '    role - ' . $report->user->role->name . "\n\r".
            '    mail - ' . (str_replace('_', '', $report->user->mail)) . "\n\r".
            '    number - ' . $report->user->number . "\n\r".
            '    edit - ' . $report->user->edit . "\n\r".
            '    is premium - ' . $report->user->is_premium . "\n\r".
            '    is blocked - ' . $report->user->is_blocked . "\n\r" . "\n\r".
            '📇 Theme: ' . (AreaFeedback::theme($report->theme)) . "\n\r".
            '✉️ Message: ' . $report->message . "\n\r";

        $buttons->add([
            ['text' => '💬 Answer',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:Answer".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'
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
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return [$buttons, $caption];
    }

    public static function answerReportButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
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
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
