<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_ships_crawlable_content_and_structured_data(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<title>'.e(config('portfolio.seo.title')).'</title>', false)
            ->assertSee('<h1>Sambit Maity — Full-Stack Software Engineer</h1>', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"@type":"Person"', false);
    }

    public function test_google_tags_render_only_when_configured_and_in_production(): void
    {
        config(['services.google' => ['site_verification' => 'token-123', 'gtm' => 'GTM-TEST1', 'ga4' => 'G-TEST1']]);

        // Outside production: verification tag yes, tracking no.
        $this->get('/')
            ->assertSee('<meta name="google-site-verification" content="token-123">', false)
            ->assertDontSee('GTM-TEST1')
            ->assertDontSee('G-TEST1');

        $this->app['env'] = 'production';

        $this->get('/')
            ->assertSee('googletagmanager.com/gtm.js', false)
            ->assertSee('googletagmanager.com/ns.html?id=GTM-TEST1', false)
            ->assertSee('gtag/js?id=G-TEST1', false);
    }

    public function test_no_google_tags_without_ids(): void
    {
        config(['services.google' => ['site_verification' => null, 'gtm' => null, 'ga4' => null]]);
        $this->app['env'] = 'production';

        $this->get('/')
            ->assertDontSee('google-site-verification')
            ->assertDontSee('googletagmanager.com');
    }
}
