<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Reply;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\TestCase;

#[CoversClass(PostRepository::class)]
#[UsesClass(User::class)]
#[UsesClass(Post::class)]
#[UsesClass(PostRevision::class)]
#[UsesClass(Reply::class)]
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
        $this->assertInstanceOf(LengthAwarePaginator::class, $posts);
    }

    #[Test]
    public function it_can_retrieve_a_post_by_slug()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Retrieve the post by slug
        $retrievedPost = $this->repository->show($post->slug);

        // Assert that we have the correct post
        $this->assertEquals($post->id, $retrievedPost->id);
    }

    #[Test]
    public function it_can_create_a_new_post()
    {
        // Create some data for the post
        $data = [
            'user_id' => $this->user->id,
            'title' => 'I am a teapot',
            'text' => 'Short and stout',
            'created_at' => now(),
        ];

        // Create the post
        $post = $this->repository->store($data);

        // Assert that we have a new post
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(Str::slug($post->title), $post->slug);
    }

    #[Test]
    public function it_can_destroy_a_post()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Destroy the post
        $this->repository->destroy($post->id);

        // Assert that the post was destroyed
        $this->assertNull(Post::find($post->id));
    }

    #[Test]
    public function it_can_destroy_a_post_with_replies()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $reply = Reply::factory()->create([
            'replyable_id' => $post->id,
            'replyable_type' => Post::class,
            'user_id' => $this->user->id
        ]);

        $replyId = $reply->id;

        // Assert that things exist before destruction
        $this->assertNotNull($post->replies->first());

        // Destroy the post
        $this->repository->destroy($post->id);

        // Assert that the post was destroyed
        $this->assertNull(Post::find($post->id));
        $this->assertNull(Reply::find($replyId));
    }

    #[Test]
    public function it_can_destroy_a_post_with_revisions()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $revision = PostRevision::factory()->create([
            'post_id' => $post->id,
            'user_id' => $this->user->id
        ]);

        $revisionId = $revision->id;

        // Assert that things exist before destruction
        $this->assertNotNull($post->revisions->first());

        // Destroy the post
        $this->repository->destroy($post->id);

        // Assert that the post was destroyed
        $this->assertNull(Post::find($post->id));
        $this->assertNull(PostRevision::find($revisionId));
    }

    #[Test]
    public function it_can_destroy_a_post_with_complex_relations()
    {
        // Create a post + reply to the post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $postId = $post->id;
        $replyToPost = Reply::factory()->withParentPost($postId)->create([
            'user_id' => $this->user->id
        ]);
        $replyToPostId = $replyToPost->id;

        // Create a reply to the post's reply
        $replyToReply = Reply::factory()->withParentReply($replyToPostId)->create([
            'user_id' => $this->user->id
        ]);
        $replyToReplyId = $replyToReply->id;

        // Create a post revision + reply to the revision
        $revision = PostRevision::factory()->create([
            'post_id' => $postId,
            'user_id' => $this->user->id
        ]);
        $revisionId = $revision->id;
        $replyToRevision = Reply::factory()->withParentRevision($revisionId)->create([
            'user_id' => $this->user->id
        ]);
        $replyToRevisionId = $replyToRevision->id;

        // Assert that things exist before destruction
        $this->assertNotNull($post->revisions->first());

        // Destroy the post
        $this->repository->destroy($post->id);

        // Assert that the post was destroyed
        $this->assertNull(Post::find($postId));
        $this->assertNull(PostRevision::find($revisionId));
        $this->assertNull(Reply::find($replyToPostId));
        $this->assertNull(Reply::find($replyToRevisionId));
        $this->assertNull(Reply::find($replyToReplyId));
    }
}
