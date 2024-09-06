<?php

namespace Tests\Feature\Feature\Api\V1\Post;

use App\Events\Models\Post\PostCreated;
use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase; // this trait to refrsh DB every time make test to not create many posts

    protected $uri = '/api/v1/posts';

    public function test_index(){

        // load data in the DB
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn($post) => $post->id)->toArray(); // map for collection not array

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);

        // verify records
        $data = $response->json('data');

        // in php while use arrow function no need to pass varaible to the function
        collect($data)->each(fn ($post) => $this->assertTrue(in_array($post['id'], $postIds)));
    }


    public function test_show(){

        $dummy = Post::factory()->create();

        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        // $this->assertEquals($dummy->id, $result['id'], 'response ID not the same as model ID ');
        // data_get: Get an item from an array or object using and can define default value
        $this->assertEquals($dummy->id, data_get($result,'id'), 'response ID not the same as model ID ');

    }


    public function test_create(){

        // stop the event and access to it to put assertions
        Event::fake();

        $dummy = Post::factory()->make();

        $response = $this->json('post', $this->uri, $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');

        Event::assertDispatched(PostCreated::class);

        // Filters the $result collection to include only the keys present in the Post model’s attributes
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $field) use ($dummy) {
            $this->assertSame(data_get($dummy, $field), $value, 'response ID not the same as model ID');
        });

    }

    public function test_update()
    {
        $dummy = Post::factory()->create();
        $dummy2 = Post::factory()->make();

        Event::fake();

        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($toUpdate) use($dummy, $dummy2){
            $response = $this->json('patch', $this->uri . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');

            Event::assertDispatched(PostUpdated::class);

            $this->assertSame(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate),'Failed to update model.');
        });
    }

    public function test_delete()
    {
        Event::fake();

        $dummy = Post::factory()->create();

        $response = $this->json('delete', $this->uri . '/'.$dummy->id);

        $result = $response->assertStatus(200);

        Event::assertDispatched(PostDeleted::class);

        $this->expectException(ModelNotFoundException::class);

        Post::query()->findOrFail($dummy->id);

    }


}
