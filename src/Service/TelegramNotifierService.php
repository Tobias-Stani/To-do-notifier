<?php

namespace App\Service;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;

class TelegramBotService
{
    private $telegram;

    public function __construct(string $botToken, string $botUsername)
    {
        // Inicializa el bot de Telegram con el token y nombre de usuario
        $this->telegram = new Telegram($botToken, $botUsername);
    }

    public function sendMessage(string $chatId, string $message)
    {
        // Configura el mensaje
        $data = [
            'chat_id' => $chatId,
            'text'    => $message,
        ];

        // Envía el mensaje usando la API de Telegram
        return Request::sendMessage($data);
    }
}
