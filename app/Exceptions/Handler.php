<?php

namespace App\Exceptions;

use App\Jobs\TelegramSendAdminJob;
use App\Models\User;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $message = mb_strcut($e->getMessage(), 0, 300);
        $file = $e->getFile();
        $line = $e->getLine();
        $message = (string)view('messages.errors.send_message',
            compact('code',  'message', 'file', 'line'));

        $haveAdminsRole = User::where('role_id', 1)->get();
        foreach ($haveAdminsRole as $admin) {
            TelegramSendAdminJob::dispatch($admin, $message);
        }
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
