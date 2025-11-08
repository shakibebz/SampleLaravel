<?php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_shorten_validation_error()
    {
        $resp = $this->postJson('/api/v1/shorten', ['url' => 'not-a-url']);
        $resp->assertStatus(422);
    }

    /** @test */
    public function test_can_shorten_url()
    {
        $resp = $this->postJson('/api/v1/shorten', [
            'url' => 'https://www.example.com/some/very/long/path'
        ]);

        $resp->assertStatus(201)
            ->assertJsonStructure(['short_url']);

        $this->assertDatabaseCount('urls', 1);
    }

    /** @test */
    public function test_redirect_increments_clicks()
    {
        $u = Url::create([
            'original_url' => 'https://www.example.com',
            'short_code' => 'abc123',
        ]);

        $resp = $this->get('/abc123');
        $resp->assertStatus(301);
        $resp->assertRedirect('https://www.example.com');

        $this->assertDatabaseHas('urls', [
            'id' => $u->id,
            'clicks' => 1,
        ]);
    }

    /** @test */
    public function test_list_urls_returns_records()
    {
        Url::create(['original_url' => 'https://a.com', 'short_code' => 'aaa111']);
        Url::create(['original_url' => 'https://b.com', 'short_code' => 'bbb222']);

        $resp = $this->getJson('/api/v1/urls');

        $resp->assertStatus(200);
        $resp->assertJsonStructure([
            ['original_url', 'short_code', 'clicks', 'created_at']
        ]);
    }

    /** @test */
    public function test_delete_url()
    {
        $u = Url::create([
            'original_url' => 'https://a.com',
            'short_code' => 'del123',
        ]);

        $resp = $this->deleteJson("/api/v1/urls/{$u->id}");

        $resp->assertStatus(200);
        $this->assertDatabaseMissing('urls', ['id' => $u->id]);
    }
}
