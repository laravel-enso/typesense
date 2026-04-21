<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use LaravelEnso\Typesense\Models\Settings;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TypesenseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());
    }

    #[Test]
    public function can_view_settings_form(): void
    {
        $this->get(route('integrations.typesense.settings.index', [], false))
            ->assertStatus(200)
            ->assertJsonStructure(['form']);
    }

    #[Test]
    public function can_update_settings(): void
    {
        $settings = Settings::factory()->create(['enabled' => false]);

        $this->patch(route('integrations.typesense.settings.update', $settings, false), [
            'enabled' => true,
        ])->assertStatus(200)
            ->assertJsonFragment(['message' => 'Settings were stored sucessfully']);

        $this->assertTrue($settings->fresh()->enabled);
    }

    #[Test]
    public function enabled_is_required_on_update(): void
    {
        $settings = Settings::factory()->create(['enabled' => false]);

        $this->patch(route('integrations.typesense.settings.update', $settings, false), [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['enabled']);
    }

    #[Test]
    public function current_creates_the_singleton_row_when_missing(): void
    {
        Cache::flush();

        $settings = Settings::current();

        $this->assertNotNull($settings->id);
        $this->assertCount(1, Settings::all());
    }

    #[Test]
    public function enabled_reflects_the_cached_singleton_state(): void
    {
        Cache::flush();
        Settings::query()->delete();
        Settings::factory()->create([
            'id' => 1,
            'enabled' => true,
        ]);

        $this->assertTrue(Settings::enabled());

        Cache::flush();
        Settings::query()->whereKey(1)->update(['enabled' => false]);

        $this->assertFalse(Settings::enabled());
    }
}
