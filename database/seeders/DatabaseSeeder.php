<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Traits\TruncateTable;
use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class); // call the given seeder class
        $this->call(PostSeeder::class);
        $this->call(CommentSeeder::class);
    }
}
