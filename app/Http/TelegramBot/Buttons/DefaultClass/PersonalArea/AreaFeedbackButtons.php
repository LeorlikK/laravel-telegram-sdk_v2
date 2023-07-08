<?php

namespace App\Http\TelegramBot\Buttons\DefaultClass\PersonalArea;

use App\Http\TelegramBot\Buttons;
use App\Http\TelegramBot\Components\DefaultClass\PersonalArea\AreaFeedback;
use App\Http\TelegramBot\Services\ArgumentsService;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Collection;

class AreaFeedbackButtons extends Buttons
{
    public static function defaultButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $buttons->add([
            ['text' => '💬 Write Report', 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Write".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);
        $buttons->add([
            ['text' => '📨 Answer from admin' . ($user->countAnswerReportState('answer')), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:Answer".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function writeButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons = self::choiceThemeButtons($buttons, $argumentsService);

        $buttons->add([
            ['text' => '◀️ Cancel', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function answerFromAdminButtons(Collection $buttons, ArgumentsService $argumentsService, User $user): Collection
    {
        $argumentsService->p = $argumentsService->p ?? 1;
        $buttonPlus = ((int)$argumentsService->p) + 1;
        $buttonMinus = ((int)$argumentsService->p) - 1;
        $perPage = 10;
        $reports = Report::with(['userWhom' => function($item)use($user){
            $item->where('id', $user->id);
        }])
            ->where('type', 'answer')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], null, $argumentsService->p);
        $totalFolder = $reports->total();

        foreach ($reports->items() as $report) {
            /**
             * @var $report Report
             */
            $buttons->add([
                ['text' => ($report->state ? "" : "❗️") . $report->userWhom->username . "(".(AreaFeedback::theme($report->theme).")"),
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
                "fp:$argumentsService->fp"
            ],
        ]);

        return $buttons;
    }

    public static function reportButtons(Collection $buttons, ArgumentsService $argumentsService): array
    {
        $report = Report::with('userWhom')->find($argumentsService->fp);
        $caption =
            '📇 Theme: ' . (AreaFeedback::theme($report->theme)) . "\n\r".
            '✉️ Message: ' . $report->message . "\n\r";

        $buttons->add([
            ['text' => '💬 Answer',
                'callback_data' =>
                    "cl:$argumentsService->cl".'_'.
                    "sw:AnswerAdmin".'_'.
                    "p:$argumentsService->p".'_'.
                    "fp:$argumentsService->fp".'_'.
                    "v:$report->theme"
            ],
        ]);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Answer".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp"
            ],
        ]);

        return [$buttons, $caption];
    }

    public static function answerReportButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons = self::choiceThemeButtons($buttons, $argumentsService);

        $buttons->add([
            ['text' => '◀️ Back', 'callback_data' =>
                "cl:$argumentsService->bk".'_'.
                "sw:Report".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "s:1"
            ],
        ]);

        return $buttons;
    }

    public static function choiceThemeButtons(Collection $buttons, ArgumentsService $argumentsService): Collection
    {
        $buttons->add([
            ['text' => ($argumentsService->v === '0' ? "✅" : "").(AreaFeedback::theme(0)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:0"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '1' ? "✅" : "").(AreaFeedback::theme(1)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:1"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '2' ? "✅" : "").(AreaFeedback::theme(2)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:2"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '3' ? "✅" : "").(AreaFeedback::theme(3)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:3"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '4' ? "✅" : "").(AreaFeedback::theme(4)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:4"
            ],
        ]);
        $buttons->add([
            ['text' => ($argumentsService->v === '5' ? "✅" : "").(AreaFeedback::theme(5)), 'callback_data' =>
                "cl:$argumentsService->cl".'_'.
                "sw:$argumentsService->sw".'_'.
                "p:$argumentsService->p".'_'.
                "fp:$argumentsService->fp".'_'.
                "v:5"
            ],
        ]);

        return $buttons;
    }

}
