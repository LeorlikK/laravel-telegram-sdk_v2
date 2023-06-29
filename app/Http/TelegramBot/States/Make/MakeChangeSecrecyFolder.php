<?php

namespace App\Http\TelegramBot\States\Make;

use App\Http\TelegramBot\States\StateMake;
use App\Models\Folder;
use App\Models\Tab;
use Carbon\Carbon;

class MakeChangeSecrecyFolder
{
    protected StateMake $stateMake;

    public function __construct(StateMake $stateMake)
    {
        $this->stateMake = $stateMake;
    }

    public function make(): null|string
    {
        if ($this->stateMake->argumentsService->v){
            $folder = Folder::find($this->stateMake->parentId);

            if ($this->stateMake->argumentsService->v === 'null'){
                $folder->display = null;
                $folder->save();
            }elseif(str_starts_with($this->stateMake->argumentsService->v, 'd')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $time = now()->addDays($matches[0]);
                $folder->display = $time;
                $folder->save();
            }elseif (str_starts_with($this->stateMake->argumentsService->v, 'w')){
                preg_match('/\d+/', $this->stateMake->argumentsService->v, $matches);
                $time = now()->addWeeks($matches[0]);
                $folder->display = $time;
                $folder->save();
            }

            return null;

        }elseif(Carbon::hasFormat($this->stateMake->text, 'Y-m-d H:i:s')){
            $folder = Folder::find($this->stateMake->parentId);
            $carbon = Carbon::parse($this->stateMake->text);
            $folder->display = $carbon;
            $folder->save();

            return null;

        }else{
            return '3';
        }
    }
}
