<?php

namespace MHMartinez\ImpersonateUser\app\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UserFinishedImpersonating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Request $request)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
