<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function report(Throwable $e)
    {
        $code = $e->getCode();
        $message = mb_strcut($e->getMessage(), 0, 200);
        $file = $e->getFile();
        $line = $e->getLine();


        $message = (string)view('errors.send_message', compact('code',  'message', 'file', 'line'));
        Telegram::sendMessage([
            'chat_id' => '1059208615',
            'text' => $message,
            'parse_mode' => 'html'
        ]);
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
