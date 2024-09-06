<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Traits\DisableForeignKeys;
use App\Traits\TruncateTable;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeys(); // must disableForeignKeys checks to remove all records from db
        $this->truncate("posts");

        // create posts and relation to user, so not need to do this to UserSeeder
        $posts = Post::factory(200)->create();
        $posts->each(function (Post $post) {
            $post->users()->sync([FactoryHelper::getRandomModelId(User::class)]); // sync accept an array
        });

        $this->enableForeignKeys(); // re able foreignkeys checks
    }
}
