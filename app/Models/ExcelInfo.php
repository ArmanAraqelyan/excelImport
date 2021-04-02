<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'row_id',
        'file_id',
        'name',
        'date',
    ];

    /**
    * Get Allowed mime types
    *
    * @return Array
    */
    public static function getAllowedMimeTypes(){
        return [
            'xlsx',
            'doc',
            'xlsx',
            'xlsb',
            'xltx',
            'xltm'
        ];
    }
}
