<?php

namespace Tests\Unit;

use App\Enums\FeatureFlag;
use App\Enums\Role;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(User::class)]
class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function create_a_new_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function update_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->update([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('newpassword123'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    #[Test]
    public function delete_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->delete();

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function restore_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->delete();

        $user->restore();

        $this->assertNotSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function force_delete_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->delete();

        $user->forceDelete();

        $this->assertNull(User::find($user->id));
    }

    #[Test]
    public function a_user_has_many_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    #[Test]
    public function a_user_has_many_replies()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $reply = Reply::factory()->create([
            'user_id' => $user->id,
            'replyable_type' => Post::class,
            'replyable_id' => $post->id
        ]);

        $this->assertInstanceOf(User::class, $reply->user);
        $this->assertEquals($user->id, $reply->user->id);
    }

    #[Test]
    public function a_user_has_many_post_revisions()
    {
        $user = User::factory()->revisionSystem()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postRevision = PostRevision::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $this->assertInstanceOf(User::class, $postRevision->user);
        $this->assertEquals($user->id, $postRevision->user->id);
    }

    #[Test]
    public function unverified_user_account()
    {
        $user = User::factory()->unverified()->create();

        $this->assertFalse($user->hasVerifiedEmail());
    }

    #[Test]
    public function user_has_role()
    {
        $user = User::factory()->create();
        $this->assertEquals(Role::USER->value, $user->role);
    }

    #[Test]
    public function user_does_not_have_role()
    {
        $user = User::factory()->create();
        $this->assertNotEquals(Role::MODERATOR->value, $user->role);
    }

    #[Test]
    public function moderator_has_role()
    {
        $user = User::factory()->moderator()->create();
        $this->assertEquals(Role::MODERATOR->value + Role::USER->value, $user->role);
    }

    #[Test]
    public function user_has_revisions_system_feature_flag()
    {
        $user = User::factory()->revisionSystem()->create();
        $this->assertEquals(FeatureFlag::REVISIONS_SYSTEM->value, $user->feature_flag);
    }
}
