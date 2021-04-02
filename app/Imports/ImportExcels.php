<?php

namespace App\Imports;

use App\Models\ExcelInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Events\AfterImport;

class ImportExcels implements ToModel,WithCalculatedFormulas,WithChunkReading,WithBatchInserts,WithStartRow,ShouldQueue
{
    public $limit,$file_id;

    public function __construct()
    {
        $configs          = \Config::get('excel');
        $this->limit      = $configs['limit'] ?? 1000;
    }

    public function setProperties($name , $value)
    {
        if(property_exists($this , $name)){
            $this->$name = $value;

//            Way that you allow changing only public properties
//            $is_public = false;
//            $reflector = new \ReflectionClass($this);
//            $public_properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
//            foreach ($public_properties as $k => $property){
//                if($name === $property->getName()){
//                    $is_public = true;
//                }
//            }
//            if($is_public){
//                $this->$name = $value;
//                return;
//            }
//            return 'The Value must be public';
        }
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row): ExcelInfo
    {
        return new ExcelInfo([
            'row_id'     => (int) $row[0],
            'file_id'    => (int) $this->file_id,
            'name'       => $row[1],
            'date'       => date('d.m.Y' , \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2])),
        ]);
    }

    public function chunkSize(): int
    {
        return $this->limit;
    }

    public function batchSize(): int
    {
        return 1000;
    }


    public function startRow(): int
    {
        return 2;
    }
}
