<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AcDecReq implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $data;
    public $action;
    public $fac;
    public $notification

    public function __construct($user, $data, $action, $fac, $notif)
    {
         $this->user = $user;
         $this->data = $data;
         $this->action = $action;
         $this->fac = $fac;
         $this->notification = $notif;
    }
    public function broadcastOn()
    {
        return ['requested-tele'];
    }
    public function broadcastAs()
    {
        return 'requested-tele-event';
    }
}
