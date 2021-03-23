<?php

namespace App\Listeners;

use App\Events\FileUploaded;
use App\Jobs\InsertToTable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;


class FileUploadedCommand
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->redis_client = new Client();
    }

    /**
     * Handle the event.
     *
     * @param  FileUploaded  $event
     * @return void
     */
    public function handle(FileUploaded $event)
    {
        InsertToTable::dispatch()->delay(now()->addSeconds(10));
    }
}
