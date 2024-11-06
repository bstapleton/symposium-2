<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostHistory;
use App\Models\User;
use App\Repositories\PostHistoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PostHistoryRepository::class)]
#[UsesClass(User::class)]
#[UsesClass(PostHistory::class)]
class PostHistoryRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private PostHistoryRepository $repository;
    private User $user;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PostHistoryRepository();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function it_can_retrieve_all_post_histories()
    {
        // Create some post histories
        PostHistory::factory()->count(3)->create([
            'post_id' => $this->post->id
        ]);

        // Retrieve all post histories
        $postHistories = $this->repository->all();

        // Assert that we have 3 post histories
        $this->assertCount(3, $postHistories);
    }

    #[Test]
    public function it_can_retrieve_a_post_history_by_id()
    {
        // Create a post history
        $postHistory = PostHistory::factory()->create([
            'post_id' => $this->post->id
        ]);

        // Retrieve the post history by ID
        $retrievedPostHistory = $this->repository->show($postHistory->id);

        // Assert that we have the correct post history
        $this->assertEquals($postHistory->id, $retrievedPostHistory->id);
    }

    #[Test]
    public function it_can_create_a_new_post_history()
    {
        // Create some data for the post history
        $data = [
            'post_id' => $this->post->id,
            'title' => 'Test Title',
            'text' => 'Test Text',
        ];

        // Create the post history
        $this->repository->store($data);

        // Assert that we have a new post history
        $this->assertCount(1, PostHistory::all());
    }

    #[Test]
    public function post_histories_can_have_parent_post_histories()
    {
        $postHistory = PostHistory::factory()->withParent($this->post->id)->create([
            'post_id' => $this->post->id
        ]);

        $this->assertNotNull($postHistory->parent);
        $this->assertInstanceOf(PostHistory::class, $postHistory->parent);
        $this->assertEquals($postHistory->parent_id, $postHistory->parent->id);
    }

    #[Test]
    public function it_can_destroy_a_post_history()
    {
        // Create a post history
        $postHistory = PostHistory::factory()->create([
            'post_id' => $this->post->id
        ]);

        // Assert that the post history exists before destruction
        $this->assertNotNull(PostHistory::find($postHistory->id));

        // Destroy the post history
        $this->repository->destroy($postHistory->id);

        // Assert that the post history was destroyed
        $this->assertNull(PostHistory::find($postHistory->id));
    }
}
