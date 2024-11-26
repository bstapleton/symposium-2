<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostRevision;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\TestCase;

#[CoversClass(PostRepository::class)]
#[UsesClass(User::class)]
#[UsesClass(Post::class)]
#[UsesClass(PostRevision::class)]
class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected PostRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new PostRepository();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_retrieve_all_posts()
    {
        // Create some posts
        Post::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // Retrieve all posts
        $posts = $this->repository->all();

        // Assert that we have 3 posts
        $this->assertCount(3, $posts);
    }

    #[Test]
    public function it_can_retrieve_a_post_by_slug()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'slug' => 'test-post',
        ]);

        PostRevision::factory()->create([
            'post_id' => $post->id
        ]);

        // Retrieve the post by slug
        $retrievedPost = $this->repository->show('test-post');

        // Assert that we have the correct post
        $this->assertEquals($post->id, $retrievedPost->id);
        $this->assertEquals($post->slug, $retrievedPost->slug);
    }

    #[Test]
    public function it_can_create_a_new_post()
    {
        // Create some data for the post
        $data = [
            'user_id' => $this->user->id,
            'slug' => 'i-am-a-teapot',
            'title' => 'I am a teapot',
            'text' => 'Short and stout',
        ];

        // Create the post
        $post = $this->repository->store($data);

        // Assert that we have a new post
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($data['slug'], $post->slug);
    }

    #[Test]
    public function it_can_destroy_a_post()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Assert that the post exists before destruction
        $this->assertNotNull(Post::find($post->id));

        // Destroy the post
        $this->repository->destroy($post->id);

        // Assert that the post was destroyed
        $this->assertNull(Post::find($post->id));
    }
}
