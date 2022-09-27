<?php

    namespace App\Events;

    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Broadcasting\PresenceChannel;
    use Illuminate\Broadcasting\PrivateChannel;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class RecordUpdated
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        public $object;
        public $originalData;
        public $data;

        public function __construct($object, $originalData, $data)
        {
            $this->object = $object;
            $this->originalData = $originalData;
            $this->data = $data;
        }

        /**
         * Get the channels the event should broadcast on.
         *
         * @return \Illuminate\Broadcasting\Channel|array
         */
        public function broadcastOn()
        {
            return new PrivateChannel('channel-name');
        }
    }
