<?php

namespace App\Imports;

use App\Models\ExcelInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;



class ExcelInfoImportCollection implements ToCollection,WithCalculatedFormulas
{
    public $limit,$start_from,$count_rows,$break;

    public function __construct()
    {
        $configs          = \Config::get('excel');
        $this->limit      = $configs['limit'] ?? 1000;
        $this->start_from = $configs['default_start'] ?? 1;
        $this->count_rows = $this->start_from;
        $this->break      = false;
    }


    public function setStartFrom( int $start_from){
        $this->start_from = $start_from;
        $this->count_rows = $start_from;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        if($this->start_from) $rows = $rows->skip($this->start_from);
        if($this->limit) $rows = $rows->take($this->limit);
        $excel_info = [];
        $cr_date = date('Y-m-d H:i:s');
        foreach ($rows as $key => $row)
        {
            if(!$row[0] || !$row[1] || !$row[2]){
                $this->break = true;
                break;
            }
            ++$this->count_rows;
            $excel_info[] = [
                'row_id'     => (int) $row[0],
                'name'       => $row[1],
                'date'       => date('d.m.Y' , \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2])),
                'created_at' => $cr_date,
                'updated_at' => $cr_date
            ];
        }
        ExcelInfo::insert($excel_info);
    }
}
