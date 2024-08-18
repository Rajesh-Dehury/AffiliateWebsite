<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function sendMessageToGroup($chatId, $message)
    {
        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        } catch (TelegramSDKException $e) {
            // Handle exception
            Log::error($e->getMessage());
        }
    }
}
