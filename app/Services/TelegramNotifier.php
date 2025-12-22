<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    public function isEnabled(): bool
    {
        return (bool) config('telegram.enabled')
            && !empty(config('telegram.bot_token'))
            && !empty(config('telegram.chat_id'));
    }

    public function sendMessage(string $text): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $token = config('telegram.bot_token');
        $chatId = config('telegram.chat_id');

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post($url, [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'disable_web_page_preview' => true,
                ]);

            if (!$response->successful()) {
                Log::warning('Telegram sendMessage failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram sendMessage exception', ['exception' => $e]);
            return false;
        }
    }

    public function sendOrderCreated(Order $order): bool
    {
        $order->loadMissing('items');

        $pickupType = match ($order->pickup_type) {
            'delivery' => 'Доставка',
            default => 'Самовывоз',
        };

        $paymentMethod = match ($order->payment_method) {
            'card' => 'Картой',
            default => 'Наличными',
        };

        $lines = [];
        $lines[] = "Новый заказ #{$order->id}";
        $lines[] = "Имя: {$order->name}";
        $lines[] = "Телефон: {$order->phone}";
        $lines[] = "Email: {$order->email}";
        $lines[] = "Получение: {$pickupType}";

        if ($order->pickup_type === 'delivery') {
            $lines[] = "Адрес: {$order->address}";
        }

        $lines[] = "Оплата: {$paymentMethod}";
        $lines[] = "Сумма: " . number_format((float) $order->total, 2, '.', ' ') . " ₽";
        $lines[] = "Товары:";

        foreach ($order->items as $item) {
            $itemTotal = number_format((float) $item->total, 2, '.', ' ');
            $lines[] = "- {$item->name} x{$item->quantity} = {$itemTotal} ₽";
        }

        return $this->sendMessage(implode("\n", $lines));
    }
}
