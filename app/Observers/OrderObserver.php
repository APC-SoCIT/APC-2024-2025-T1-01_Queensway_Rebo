<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderHistory;

class OrderObserver
{
    public function updating(Order $order)
    {
        if ($order->isDirty('order_status')) {
            $originalStatus = $order->getOriginal('order_status');
            $newStatus = $order->order_status;

            $order->histories()->create([
                'status' => $newStatus,
                'message' => "Status changed from $originalStatus to $newStatus.",
            ]);
        }
    }
}
