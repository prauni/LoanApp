<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class NotifyPostCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        Log::debug('*** Listeners created *** ****');
    }

    /**
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        Log::debug('*** Listeners handle *** ****');
        echo '-----'.$event->pid;
        DB::table('test')->insert(
            ['name' => 'john@example.com']
        );
        //echo 'hello';
    }
}
