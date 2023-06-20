<?php

namespace App\Http\TelegramBot\Services;

use Telegram\Bot\Objects\Update;

class ArgumentsService
{
    public ?string $cl=null; // class
    public ?string $sw=null; // switch
    public ?string $bk=null; // back
    public ?string $bkS=null; // backSwitch
    public ?string $ac=null; // action
    public ?string $fp=null; // folderParent
    public ?string $m=null; // module
    public ?string $v=null; // some value

    public function __construct(string $arguments)
    {
        $argumentsArray = explode('_', $arguments);
        foreach ($argumentsArray as $argument) {
            $keyAndValue = explode(':', $argument);
            $key = $keyAndValue[0];
            $value = $keyAndValue[1] ?? null;
            if (isset($value) && $value !== ''){
                $this->$key = $value;
            }
        }

        dump(
            'cl: ' . $this->cl,
            'sw: ' . $this->sw,
            'bk: ' . $this->bk,
            'bkS: ' . $this->bkS,
            'ac: ' . $this->ac,
            'fp: ' . $this->fp,
            '=>>>>>>>>>>>>>>>>>>>'
        );
    }

    public function setArgument($key, $value):?string
    {
        $this->$key = $value;
        return $this->$key;
    }
}
