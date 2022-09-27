<?php

    namespace App\Listeners;

    use App\Events\RecordCreated;
    use App\Interfaces\LoggableInterface;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Support\Facades\App;

    class RecordCreatedLog
    {
        /**
         * Create the event listener.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }

        /**
         * Handle the event.
         *
         * @param  RecordCreated  $event
         * @return void
         */
        public function handle(RecordCreated $event)
        {
            $object = $event->object;

            if($object instanceof LoggableInterface) {
                $object->logs()->create([
                    'type' => 'created',
                    'subject' => 'Created',
                    'data_submitted' => json_encode($event->data),
                ]);
            }
        }
    }
