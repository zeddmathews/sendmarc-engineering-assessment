<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_player()
    {
        $response = $this->post('/api/players', [
            'first_name' => 'Roger',
            'last_name' => 'Federer',
            'email' => 'roger@example.com',
            'rank' => 'Platinum',
            'country' => 'Switzerland',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                    'first_name' => 'Roger',
                    'email' => 'roger@example.com',
                ]);

                $this->assertDatabaseHas('players', ['email' => 'roger@example.com']);

    }
}
