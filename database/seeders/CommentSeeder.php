<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Traits\DisableForeignKeys;
use App\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeys(); // using trait, stop foreign keys cheack to delete all the records from db
        $this->truncate("comments"); // delete all records
        Comment::factory(3)->create(); // requring factory to create dummy users
        $this->enableForeignKeys(); // re enable foreign keys check cuase i stop it
    }
}
