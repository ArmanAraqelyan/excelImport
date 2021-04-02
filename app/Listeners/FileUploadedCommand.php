<?php

namespace App\Listeners;

use App\Events\FileUploaded;
use App\Imports\ImportExcels;

use App\Models\Uploads;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;


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
        $importer = new ImportExcels;
        $importer->setProperties('file_id' , $event->file_id);
        ExcelFacade::queueImport($importer,$event->file_name , 'excel');
//        InsertToTable::dispatch()->delay(now()->addSeconds(10));
    }
}
