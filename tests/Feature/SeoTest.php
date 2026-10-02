<?php

namespace Tests\Feature;

use App\Support\PortfolioContent;
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

    public function test_older_saved_content_picks_up_new_config_sections(): void
    {
        // A save made before the `seo` section existed, with one experience entry removed.
        $saved = config('portfolio');
        unset($saved['seo'], $saved['profile']['timezone']);
        $saved['profile']['headline'] = 'Saved headline';
        $saved['experience'] = array_slice($saved['experience'], 1);
        PortfolioContent::save($saved);

        $content = PortfolioContent::get();

        $this->assertSame(config('portfolio.seo'), $content['seo']);
        $this->assertSame(config('portfolio.profile.timezone'), $content['profile']['timezone']);
        $this->assertSame('Saved headline', $content['profile']['headline']);
        $this->assertCount(count(config('portfolio.experience')) - 1, $content['experience']);

        $this->get('/')->assertSee('<title>'.e(config('portfolio.seo.title')).'</title>', false);
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
