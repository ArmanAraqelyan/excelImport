<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileIdExcelInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('excel_infos', function (Blueprint $table) {
            $table->foreignId('file_id')->after('row_id')->constrained('uploads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('excel_infos', function (Blueprint $table) {
            $table->dropColumn('file_id');
        });
    }
}
