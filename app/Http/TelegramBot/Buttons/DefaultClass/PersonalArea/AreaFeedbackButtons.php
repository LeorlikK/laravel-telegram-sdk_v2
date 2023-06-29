<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaFeedback;
use App\Http\TelegramBot\PaginateButtons;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Collection;

class AreaFeedbackButtons extends PaginateButtons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $buttons->add([
            ['text' => '💬 Write Report', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);
        $buttons->add([
            ['text' => '📨 Answer from admin' . ($user->countAnswerReportState('answer')), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Answer".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "ac:N".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function writeButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        if ($argumentsService->v){
            $caption = "Опишите вашу проблему и мы постараемся её решить";
        }else{
            $caption = "Выберите тему";
        }
        $buttons->add([
            ['text' => ($argumentsService->v === '1' ? "✅" : "").(AreaFeedback::theme(1)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:1"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '2' ? "✅" : "").(AreaFeedback::theme(2)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:2"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '3' ? "✅" : "").(AreaFeedback::theme(3)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:3"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '4' ? "✅" : "").(AreaFeedback::theme(4)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:4"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '5' ? "✅" : "").(AreaFeedback::theme(5)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:5"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '6' ? "✅" : "").(AreaFeedback::theme(6)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "ac:N".'_'.
                "v:6"
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

    public static function answerFromAdminButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $reports = Report::with('user')
            ->where('type', 'answer')
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
                        "fp:$report->id"
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
        $caption =
            '📇 Theme: ' . (AreaFeedback::theme($report->theme)) . "\n\r".
            '✉️ Message: ' . $report->message . "\n\r";

        $buttons->add([
            ['text' => '💬 Answer',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:AnswerAdmin".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Answer".'_'.
                "ac:N".'_'.
                "p:$argumentsService->p".'_'.
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
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }
}
