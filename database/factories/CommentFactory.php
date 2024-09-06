<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "body"=> [], //  expected to store JSON or array data (e.g., a list of items
            'user_id' => FactoryHelper::getRandomModelId(User::class), // use FactoryHelper created to reuse the code
            'post_id' => FactoryHelper::getRandomModelId(Post::class),
        ];
    }
}
