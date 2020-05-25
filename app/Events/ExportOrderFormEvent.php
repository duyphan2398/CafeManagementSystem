<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportOrderFormEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product_diff;
    public $data;
    public $host;
    public $url;
    public function __construct($product_diff, $data, $url, $host)
    {
        $this->product_diff = $product_diff;
        $this->data         = $data;
        $this->url          = $url;
        $this->host         = $host;
    }

    public function broadcastOn()
    {
        return ['desktop-app'];
    }

    public function broadcastAs()
    {
        return 'orderProducts-event';
    }
}
