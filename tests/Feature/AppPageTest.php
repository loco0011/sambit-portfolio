<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_side_project_has_a_page_linked_from_home_and_sitemap(): void
    {
        Storage::fake('local');
        $apps = config('portfolio.side_projects');
        $this->assertNotEmpty($apps);

        foreach ($apps as $app) {
            $this->get("/apps/{$app['slug']}")
                ->assertOk()
                ->assertSee(e($app['headline']), false)
                ->assertSee('<link rel="canonical" href="'.route('apps.show', $app['slug']).'">', false)
                ->assertSee('"@type":"BreadcrumbList"', false);

            $this->get('/')->assertSee('href="'.route('apps.show', $app['slug']).'"', false);
            $this->get('/sitemap.xml')->assertSee('<loc>'.route('apps.show', $app['slug']).'</loc>', false);
        }

        $this->get('/apps/not-an-app')->assertNotFound();
    }

    public function test_android_app_without_an_upload_says_coming_soon_and_has_no_download(): void
    {
        Storage::fake('local');

        $this->get('/apps/klyro')->assertOk()->assertSee('Download coming soon');
        $this->get('/apps/klyro/download/app')->assertNotFound();
    }

    public function test_website_page_offers_no_apk(): void
    {
        $this->get('/apps/kolkata-hiring-map')->assertOk()->assertSee('Launching soon')->assertDontSee('How to install');
    }

    public function test_uploaded_apk_is_offered_with_its_size_and_checksum_but_no_version(): void
    {
        Storage::fake('local');
        $this->actingAs(User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'correct-horse-battery']));

        $this->postJson('/admin/api/apps/klyro/app', ['file' => UploadedFile::fake()->create('klyro.zip', 10)])->assertUnprocessable();
        $this->postJson('/admin/api/apps/klyro/nope', ['file' => UploadedFile::fake()->create('klyro.apk', 10)])->assertNotFound();

        $this->postJson('/admin/api/apps/klyro/app', ['file' => UploadedFile::fake()->createWithContent('klyro-1.0.0.apk', str_repeat('x', 3 * 1048576))])
            ->assertOk()
            ->assertJsonPath('apps.3.slug', 'klyro')
            ->assertJsonPath('apps.3.builds.0.upload.size', 3 * 1048576);

        Storage::disk('local')->assertExists('apps/klyro-app.apk');
        $sha = hash('sha256', str_repeat('x', 3 * 1048576));

        $this->get('/apps/klyro')
            ->assertOk()
            ->assertSee(route('apps.download', ['klyro', 'app']), false)
            ->assertSee('3 MB')
            ->assertSee($sha)
            ->assertDontSee('1.0.0');

        $this->get('/apps/klyro/download/app')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.android.package-archive')
            ->assertDownload('Klyro.apk');
    }

    public function test_app_uploads_need_login(): void
    {
        $this->getJson('/admin/api/apps')->assertUnauthorized();
        $this->postJson('/admin/api/apps/klyro/app')->assertUnauthorized();
    }
}
