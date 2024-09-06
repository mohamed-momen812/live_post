<?php

namespace Tests\Unit;

use App\Exceptions\GeneralException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositrtyTest extends TestCase
{
    use RefreshDatabase;
    // all test function must start with test word and be in the snackcase

    public function test_create()
    {

        $repository = $this->app->make(PostRepository::class);

        $payload = [
            "title" => "test post",
            'body' => []
        ];

        $result = $repository->create($payload);

        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title');
    }

    public function test_update()
    {
        $repository = $this->app->make(PostRepository::class);

        $dummyPost = Post::factory(1)->create()[0];

        $payload = [
            'title' => 'abc123',
        ];

        $updated = $repository->update($dummyPost, $payload);

        $this->assertSame($payload['title'], $updated->title,'Post updated does not have the same title');
    }

    public function test_delete_will_throw_exception_when_delete_post_thet_doesnt_exist() {

        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->make()[0]; // make will create a record on the fly

        $this->expectException(GeneralException::class);

        $deleted = $repository->forceDelete($dummyPost);
    }

    public function test_delete()
    {
        // Goal: test if forceDelete() is workign

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->create()[0];

        // compare
        $deleted = $repository->forceDelete($dummyPost);

        // verify if the post deleted
        $found = Post::query()->find($dummyPost->id);

        $this->assertSame(null, $found, 'Post is not deleted');
    }
}
