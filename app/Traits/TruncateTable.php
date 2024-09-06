<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait TruncateTable // using in DB seeder
{
    protected function truncate($tabel){
        DB::table($tabel)->truncate();
    }
}
