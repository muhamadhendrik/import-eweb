<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImportProgressUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $progress;

    public function __construct($progress)
    {
        $this->progress = $progress;
    }

    public function broadcastOn()
    {
        return new Channel('import-progress');
    }
}
