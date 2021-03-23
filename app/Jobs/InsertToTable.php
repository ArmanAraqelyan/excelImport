<?php

namespace App\Jobs;

use App\Events\ExcelInsertedData;

use App\Imports\ExcelInfoImportCollection;
use App\Models\Uploads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Predis\Client;

class InsertToTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $redis_client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redis_client = new Client();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $upload = Uploads::select(['id' , 'file_name'])->first();
        if($upload){
            while(!$this->redis_client->get($upload->file_name . '_end')){
                $this->redis_client->del($upload->file_name . '_end');
                $importer = new ExcelInfoImportCollection;
                if($start_from = $this->redis_client->get($upload->file_name)){
                    $importer->setStartFrom( (int) $start_from);
                }
                ExcelFacade::import($importer,$upload->file_name , 'excel');
                if($importer->break){
                    $this->redis_client->del($upload->file_name);
                    $upload->delete();
                    Storage::disk('excel')->delete($upload->file_name);
                    $this->redis_client->set($upload->file_name . '_end' , true);
                }else{
                    $this->redis_client->set($upload->file_name, $importer->count_rows);
                    Artisan::call('excel:import');
                }
            }
        }
    }
}
