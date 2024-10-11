<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramNotifierService
{
    private $client;
    private $telegramApiUrl;
    private $botToken;
    private $chatId;

    public function __construct(HttpClientInterface $client, string $botToken, string $chatId)
    {
        $this->client = $client;
        $this->botToken = $botToken;
        $this->chatId = $chatId;
        $this->telegramApiUrl = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
    }

    public function sendNotification(string $message): void
    {
        $response = $this->client->request('POST', $this->telegramApiUrl, [
            'json' => [
                'chat_id' => $this->chatId,
                'text' => $message,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error al enviar el mensaje a Telegram');
        }
    }
}
