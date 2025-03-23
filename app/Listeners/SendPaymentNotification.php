<?php

namespace App\Listeners;

use App\Events\TransactionProcessed;
use App\Models\Notification;

class SendPaymentNotification
{
    public function handle(TransactionProcessed $event)
    {
        $transaction = $event->transaction;
        
        $title = "Transaksi Berhasil";
        $message = "Anda telah menerima transaksi sebesar Rp " . number_format($transaction->amount, 2, ',', '.');
        
        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => $title,
            'message' => $message,
            'status' => 'UNREAD',
        ]);
    }
}
