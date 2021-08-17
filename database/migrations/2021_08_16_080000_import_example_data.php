<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportExampleData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Not applicable for modified database since the category id is duplicated
        // DB::unprepared(file_get_contents(__DIR__ . '/../seeds/20210526-ecolla-db-backup.sql'));
        // DB::unprepared(file_get_contents(__DIR__ . '/../seeds/20210531-ecolla-db-backup.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/../seeds/20210815-ecolla-db-backup.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
