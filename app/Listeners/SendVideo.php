<?php

namespace App\Listeners;

use App\Events\VideoProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendVideo
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
     * @param  \App\Events\VideoProcessed  $event
     * @return void
     */
    public function handle(VideoProcessed $event)
    {
        Log::channel('video')->info([$event]);
    }
}
