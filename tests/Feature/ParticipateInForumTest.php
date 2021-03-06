<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_unauthenticated_user_may_not_add_replies()
    {
        $this->withExceptionHandling();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->create();

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('/login');
    }

    /**
     * test
     *
     * @return void
     */
    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        /**
         * Error: Class 'Doctrine\DBAL\Driver\PDOSqlite\Driver' not found
         */
        //Given we have an authenticated user
        $this->be($user = factory('App\User')->create());

        //And an existing thread

        $thread = factory('App\Thread')->create();

        //When a user adds a reply to the thread

        $reply = factory('App\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());

        //Replies

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);

        $this->assertEquals(1, $thread->fresh()->replies_count);

    }

    /**
     * @test
     */

    public function a_reply_requires_a_body()
    {
        $this->signIn();

        $thread = create('App\Thread');

        //When a user adds a reply to the thread

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * @test
     */

    public function unautorized_user_cannot_delete_reply()
    {
        $reply = create('App\Reply');

        $this->delete("replies/{$reply->id}")->assertRedirect('/login');

        $this->signIn()->delete("replies/{$reply->id}")->assertStatus(403);
    }

    /**
     * @test
     */

    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->user()->id]);

        $reply = create('App\Reply', ['user_id' => auth()->user()->id, 'thread_id' => $thread->id]);

        $this->delete("replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /**
     * @test
     */

    public function authorized_users_can_update_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->user()->id]);

        $this->patch("replies/{$reply->id}", ['body' => 'You\'ve been changed!'])->assertStatus(201);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'You\'ve been changed!']);
    }

    /**
     * @test
     */

    public function unautorized_user_cannot_update_reply()
    {
        $reply = create('App\Reply');

        $this->patch("replies/{$reply->id}")->assertRedirect('/login');

        $this->signIn()->patch("replies/{$reply->id}")->assertStatus(403);
    }

    /**
     * @test
     */
    public function replies_that_contain_spam_may_not_be_created()
    {
//        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => 'This is spammmmmmmmmmmm!']);

//        $this->expectException(\Exception::class);

        $this->post($thread->path() . '/replies', $reply->toArray())
        ->assertStatus(302);
    }

    /**
     * @test
     */
    public function a_user_can_leave_reply_only_once_per_minute()
    {
//        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => 'This is reply!']);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200)
        ;

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(403)
        ;
    }
}
