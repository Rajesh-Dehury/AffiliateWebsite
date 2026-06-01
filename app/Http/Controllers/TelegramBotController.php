<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function setWebhook()
    {
        try {
            $response = Telegram::setWebhook(['url' => route('telegram.webhook', ['token' => config('telegram.bots.mybot.token')])]);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function handleWebhook(Request $request)
    {
        $update = Telegram::getWebhookUpdate();
        Log::info('Telegram Update', ['update' => $update]);
        return response()->json(['status' => 'success']);
    }

    public function sendMessageToGroup($chatId, $message)
    {
        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
            ]);
            session()->flash('success', 'Posted to Telegram!');
        } catch (TelegramSDKException $e) {
            // Handle exception
            Log::error($e->getMessage());
        }
    }
}
