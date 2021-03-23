<?php

namespace App\Http\Controllers;

// Models
use App\Events\FileUploaded;
use App\Models\Uploads;

// Requests
use App\Http\Requests\ExcelUploadRequest;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function upload(ExcelUploadRequest $req){
        $file_name = uniqid() . '.xlsx';
        $storage_path = Storage::disk('excel')->put($file_name, @file_get_contents($req->excel_file->getRealPath()));
        if($storage_path){
            $uploaded = Uploads::create([
                'file_name'  => $file_name,
            ]);
            FileUploaded::dispatch($uploaded);
        }
        return redirect()->back()->with('success_upload', 'Upload success, Please wait for import data');
    }
}
