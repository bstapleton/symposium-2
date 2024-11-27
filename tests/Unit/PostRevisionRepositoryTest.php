<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostRevision;
use App\Models\User;
use App\Repositories\PostRevisionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PostRevisionRepository::class)]
#[UsesClass(User::class)]
#[UsesClass(PostRevision::class)]
class PostRevisionRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private PostRevisionRepository $repository;
    private User $user;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PostRevisionRepository();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function it_can_retrieve_all_post_histories()
    {
        // Create some post histories
        PostRevision::factory()->count(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Retrieve all post histories
        $postHistories = $this->repository->all();

        // Assert that we have 3 post histories
        $this->assertCount(3, $postHistories);
    }

    #[Test]
    public function it_can_retrieve_a_post_revision_by_id()
    {
        // Create a post history
        $postHistory = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Retrieve the post history by ID
        $retrievedPostHistory = $this->repository->show($postHistory->id);

        // Assert that we have the correct post history
        $this->assertEquals($postHistory->id, $retrievedPostHistory->id);
    }

    #[Test]
    public function it_can_create_a_new_post_revision_title()
    {
        // Create a revision with a changed title
        $postHistory = PostRevision::factory()->onlyTitle($this->post->id, $this->user->id)->create();

        // Assert that we have a new post history
        $this->assertCount(1, PostRevision::all());

        // It's definitely bound to a post
        $this->assertNotNull($postHistory->post->slug);

        // And the data that we're expecting is there
        $this->assertNotNull($postHistory->title);
        $this->assertNull($postHistory->text);
    }

    #[Test]
    public function it_can_create_a_new_post_revision_text()
    {
        // Create a revision with a changed title
        $postHistory = PostRevision::factory()->onlyText($this->post->id, $this->user->id)->create();

        // Assert that we have a new post history
        $this->assertCount(1, PostRevision::all());

        // It's definitely bound to a post
        $this->assertNotNull($postHistory->post->slug);

        // And the data that we're expecting is there
        $this->assertNull($postHistory->title);
        $this->assertNotNull($postHistory->text);
    }

    #[Test]
    public function that_validation_fails_if_no_title_or_text_is_provided()
    {
        $postHistory = PostRevision::make([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'created_at' => now(),
        ]);

        $this->assertFalse(!$postHistory->save());
    }

    #[Test]
    public function post_histories_can_have_parent_post_histories()
    {
        $postHistory = PostRevision::factory()->withParent($this->post->id, $this->user->id)->create([
            'post_id' => $this->post->id
        ]);

        $this->assertNotNull($postHistory->parent);
        $this->assertInstanceOf(PostRevision::class, $postHistory->parent);
        $this->assertEquals($postHistory->parent_id, $postHistory->parent->id);
    }

    #[Test]
    public function it_can_destroy_a_post_revision()
    {
        // Create a post history
        $postHistory = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Assert that the post history exists before destruction
        $this->assertNotNull(PostRevision::find($postHistory->id));

        // Destroy the post history
        $this->repository->destroy($postHistory->id);

        // Assert that the post history was destroyed
        $this->assertNull(PostRevision::find($postHistory->id));
    }

    #[Test]
    public function it_can_retrieve_the_older_post_histories()
    {
        // Create some post histories
        PostRevision::factory(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create a newer one too
        $new = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $older = $this->repository->older($new->id);

        $this->assertNotCount(0, $older);
        $this->assertNotContains($new->id, $older->pluck('id')->toArray());
    }

    #[Test]
    public function it_can_retrieve_the_newer_post_histories()
    {
        // Create a post history
        $old = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create some newer ones
        PostRevision::factory(3)->create([
            'post_id' => $this->post->id,
                'user_id' => $this->user->id
        ]);

        $newer = $this->repository->newer($old->id);

        $this->assertNotCount(0, $newer);
        $this->assertNotContains($old->id, $newer->pluck('id')->toArray());
    }

    #[Test]
    public function it_can_retrieve_the_oldest_post_revision()
    {
        // Create a post history
        $old = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create some newer ones
        PostRevision::factory(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $new = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $first = $this->repository->oldest($new->id);

        $this->assertTrue($first->id === $old->id);
    }

    #[Test]
    public function it_resolves_null_if_current_is_the_oldest()
    {
        // Create a post history
        $revision = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $oldest = $this->repository->oldest($revision->id);

        $this->assertNull($oldest);
    }

    #[Test]
    public function it_can_retrieve_the_newest_post_revision()
    {
        // Create a post history
        $old = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create some newer ones
        PostRevision::factory(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $new = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $last = $this->repository->newest($old->id);

        $this->assertTrue($last->id === $new->id);
    }

    #[Test]
    public function it_resolves_null_if_current_is_the_newest()
    {
        // Create a post history
        $revision = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $newest = $this->repository->newest($revision->id);

        $this->assertNull($newest);
    }

    #[Test]
    public function it_can_retrieve_the_previous_post_revision()
    {
        // Create a post history
        $old = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create a newer one too
        $new = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $previous = $this->repository->previous($new->id);

        $this->assertNotEquals($previous->id, $new->id);
        $this->assertEquals($old->id, $previous->id);
        $this->assertLessThan($new->id, $previous->id);
    }

    #[Test]
    public function it_resolves_null_if_there_is_no_previous_revision()
    {
        // Create a post history
        $revision = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $previous = $this->repository->previous($revision->id);

        $this->assertNull($previous);
    }

    #[Test]
    public function it_can_retrieve_the_next_post_revision()
    {
        // Create a post history
        $old = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        // Create a newer one too
        $new = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $next = $this->repository->next($old->id);

        $this->assertNotEquals($next->id, $old->id);
        $this->assertEquals($new->id, $next->id);
        $this->assertGreaterThan($old->id, $next->id);
    }

    #[Test]
    public function it_resolves_null_if_there_is_no_next_revision()
    {
        // Create a post history
        $revision = PostRevision::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $next = $this->repository->next($revision->id);

        $this->assertNull($next);
    }
}
