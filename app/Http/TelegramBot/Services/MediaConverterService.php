<?php

namespace App\Http\TelegramBot\Services;

class MediaConverterService
{
    public static function messageMediaConverter($message): ?array
    {
        $media = null;

        if (isset($message['photo']) && count($message['photo']) > 0) {
            echo "Got photo\n";
            $media = ['file_id' => $message['photo'][0]['file_id'], 'type' => 'photo'];
        } elseif (isset($message['video'])) {
            echo "Got video\n";
            $media = ['file_id' => $message['video']['file_id'], 'type' => 'video'];
        } elseif (isset($message['animation'])) {
            echo "Got Animation\n";
            $media = ['file_id' => $message['animation']['file_id'], 'type' => 'animation'];
        } elseif (isset($message['document'])) {
            echo "Got document\n";
            $type = 'document';
            if (isset($message['document']['mine_type']) && strpos($message['document']['mine_type'], 'video') !== false) {
                $type = 'video';
            }
            $media = ['file_id' => $message['document']['file_id'], 'type' => $type];
        } elseif (isset($message['audio'])) {
            echo "Got audio\n";
            $media = ['file_id' => $message['audio']['file_id'], 'type' => 'audio'];
        } elseif (isset($message['voice'])) {
            echo "Got Voice\n";

//            try {
//                $localFilePath = 'media/voice.mp3';
//                $media = ['file_id' => $message['voice']['file_id'], 'type' => 'audio'];
//
//                $fileLink = $ctx->telegram->getFileLink($message['voice']['file_id'])->get('href');
//                $response = \Illuminate\Support\Facades\Http::get($fileLink);
//
//                Storage::disk('local')->put($localFilePath, $response->getBody());
//
//                $newMessage = $ctx->replyWithAudio(['source' => storage_path('app/'.$localFilePath)]);
//                $media = ['file_id' => $newMessage['audio']['file_id'], 'type' => 'audio'];
//
//                $ctx->deleteMessage(['message_id' => $newMessage['message_id']]);
//            } catch (Exception $error) {
//                echo 'error: '.$error->getMessage()."\n";
//                notifyDev($error->getMessage());
//            }
        }

        if ($media && isset($media['file_id']) && isset($media['type'])) {
            echo "Got new media\n";
            return $media;
        }else{
            return null;
        }
    }
}
