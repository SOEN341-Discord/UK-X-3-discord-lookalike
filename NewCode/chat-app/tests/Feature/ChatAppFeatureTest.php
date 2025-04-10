<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Events;

class ChatAppFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_create_a_group()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/groups', [
            'name' => 'Project Team',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('groups', ['name' => 'Project Team']);
    }

    /** @test */
    public function user_can_join_a_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/groups/{$group->id}/join");
        $response->assertStatus(200);
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_send_message_to_group()
    {
        \Illuminate\Support\Facades\Bus::fake();
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->users()->attach($user);

        $response = $this->actingAs($user)->post("/groups/{$group->id}/messages", [
            'message' => 'Hello group!',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('group_messages', [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'message' => 'Hello group!',
        ]);
    }

    /** @test */
    public function group_message_is_visible_to_group_members()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->users()->attach($user);

        $group->messages()->create([
            'user_id' => $user->id,
            'message' => 'Hello group!',
        ]);

        $response = $this->actingAs($user)->get("/groups/{$group->id}/messages");
        $response->assertStatus(200)->assertSee('Hello group!');
    }

    /** @test */
    public function user_can_create_a_channel()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $response = $this->actingAs($user)->postJson('/channels', [
            'name' => 'General',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('channels', ['name' => 'General']);
    }

    /** @test */
    public function test_channel_name_must_be_unique()
    {
        $user = User::factory()->create(['is_admin' => true]);
    
        Channel::factory()->create(['name' => 'General']);
    
        $response = $this->actingAs($user)->postJson('/channels', [
            'name' => 'General',
            'description' => 'Does not matter',
        ]);
    
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

}
