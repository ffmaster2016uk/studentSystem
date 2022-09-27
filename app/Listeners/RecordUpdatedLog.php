<?php

namespace App\Listeners;

use App\Events\RecordUpdated;
use App\Interfaces\LoggableInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordUpdatedLog
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
     * @param  RecordUpdated  $event
     * @return void
     */
    public function handle(RecordUpdated $event)
    {
        $originalData = $event->originalData;
        $object = $event->object;
        $submittedData = $event->data;

        if($object instanceof LoggableInterface) {
            $object->logs()->create([
                'type' => 'updated',
                'subject' => 'Updated',
                'data_submitted' => json_encode($submittedData),
                'previous_data' => json_encode($originalData),
                'data_changed' => $object->buildChangedDataStructure($originalData, $submittedData)
            ]);
        }
    }
}
