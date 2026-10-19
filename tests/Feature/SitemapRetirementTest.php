<?php

namespace Tests\Feature;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapRetirementTest extends TestCase
{
    use RefreshDatabase;

    public function test_backend_sitemap_url_is_not_served(): void
    {
        $this->get('/sitemap.xml')->assertNotFound();
    }

    public function test_robots_response_does_not_advertise_a_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $this->assertStringStartsWith('text/plain', $response->headers->get('Content-Type'));
        $this->assertStringNotContainsStringIgnoringCase('sitemap:', $response->getContent());
    }

    public function test_sitemap_generator_is_not_registered_or_scheduled(): void
    {
        $kernel = $this->app->make(ConsoleKernel::class);
        $this->assertArrayNotHasKey('generate:sitemap', $kernel->all());

        $scheduledCommands = array_map(
            static fn ($event) => $event->command ?? '',
            $this->app->make(Schedule::class)->events()
        );

        $this->assertStringNotContainsString('generate:sitemap', implode("\n", $scheduledCommands));
    }
}
