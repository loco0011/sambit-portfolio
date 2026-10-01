<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'correct-horse-battery']);
    }

    public function test_admin_page_is_served_to_guests_with_lock_screen_state(): void
    {
        $this->get('/admin')->assertOk()->assertSee('"user":null', false);
        $this->get('/admin/inbox/3')->assertOk();
    }

    public function test_api_requires_login(): void
    {
        $this->getJson('/admin/api/dashboard')->assertUnauthorized();
        $this->getJson('/admin/api/messages')->assertUnauthorized();
        $this->putJson('/admin/api/content', [])->assertUnauthorized();
    }

    public function test_login_rejects_wrong_password_and_accepts_right_one(): void
    {
        $this->admin();

        $this->postJson('/admin/api/login', ['email' => 'admin@example.com', 'password' => 'nope'])->assertUnprocessable();

        $this->postJson('/admin/api/login', ['email' => 'admin@example.com', 'password' => 'correct-horse-battery'])
            ->assertOk()
            ->assertJsonPath('user.email', 'admin@example.com');

        $this->assertAuthenticated();
    }

    public function test_dashboard_counts_views_visitors_and_messages(): void
    {
        PageView::create(['path' => '/', 'visitor' => 'aaa', 'device' => 'desktop', 'referrer' => 'github.com']);
        PageView::create(['path' => '/', 'visitor' => 'aaa', 'device' => 'desktop']);
        PageView::create(['path' => '/', 'visitor' => 'bbb', 'device' => 'mobile']);
        PageView::create(['path' => '/resume', 'visitor' => 'bbb', 'device' => 'mobile']);
        ContactMessage::create(['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hello there friend']);

        $this->actingAs($this->admin())
            ->getJson('/admin/api/dashboard?days=7')
            ->assertOk()
            ->assertJsonCount(7, 'series')
            ->assertJsonPath('totals.views', 3)
            ->assertJsonPath('totals.visitors', 2)
            ->assertJsonPath('totals.resume', 1)
            ->assertJsonPath('referrers.0.host', 'github.com')
            ->assertJsonPath('devices.mobile', 1)
            ->assertJsonPath('messages.unread', 1);
    }

    public function test_messages_can_be_marked_read_and_deleted(): void
    {
        $message = ContactMessage::create(['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hello there friend']);
        $this->actingAs($this->admin());

        $this->patchJson("/admin/api/messages/{$message->id}", ['read' => true])->assertOk();
        $this->assertNotNull($message->fresh()->read_at);

        $this->deleteJson("/admin/api/messages/{$message->id}")->assertOk();
        $this->assertModelMissing($message);
    }

    public function test_content_edits_go_live_and_can_be_reset(): void
    {
        $this->actingAs($this->admin());
        $content = config('portfolio');
        $content['profile']['headline'] = 'Edited from the control unit';

        $this->putJson('/admin/api/content', ['content' => $content])
            ->assertOk()
            ->assertJsonPath('customized', true)
            ->assertJsonPath('content.projects.0.slug', config('portfolio.projects.0.slug'));

        $this->get('/')->assertSee('Edited from the control unit');

        $this->patchJson('/admin/api/content/availability', ['available' => false])->assertJsonPath('available', false);

        // Saving must keep every field, not only the validated ones.
        $this->getJson('/admin/api/content')
            ->assertJsonPath('content.profile.headline', 'Edited from the control unit')
            ->assertJsonPath('content.profile.links', config('portfolio.profile.links'))
            ->assertJsonPath('content.profile.available', false);

        $this->deleteJson('/admin/api/content')->assertJsonPath('customized', false);
        $this->get('/')->assertDontSee('Edited from the control unit');
    }

    public function test_content_requires_core_profile_fields(): void
    {
        $this->actingAs($this->admin())
            ->putJson('/admin/api/content', ['content' => ['profile' => ['name' => '']]])
            ->assertUnprocessable();
    }

    public function test_resume_upload_replaces_public_file(): void
    {
        Storage::fake('local');
        $this->actingAs($this->admin());

        $this->postJson('/admin/api/resume', ['file' => UploadedFile::fake()->create('cv.png', 10, 'image/png')])->assertUnprocessable();

        $this->postJson('/admin/api/resume', ['file' => UploadedFile::fake()->create('cv.pdf', 20, 'application/pdf')])
            ->assertOk()
            ->assertJsonPath('exists', true);

        Storage::disk('local')->assertExists('resume.pdf');
    }

    public function test_password_change_needs_current_password(): void
    {
        $this->actingAs($this->admin());

        $this->putJson('/admin/api/password', ['current_password' => 'wrong', 'password' => 'new-password-123', 'password_confirmation' => 'new-password-123'])
            ->assertUnprocessable();

        $this->putJson('/admin/api/password', ['current_password' => 'correct-horse-battery', 'password' => 'new-password-123', 'password_confirmation' => 'new-password-123'])
            ->assertOk();
    }

    public function test_home_page_visits_are_tracked_but_bots_and_admins_are_not(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (iPhone)')->withHeader('Referer', 'https://www.linkedin.com/feed')->get('/')->assertOk();
        $this->withHeader('User-Agent', 'Googlebot/2.1')->get('/')->assertOk();
        $this->actingAs($this->admin())->withHeader('User-Agent', 'Mozilla/5.0')->get('/')->assertOk();

        $this->assertSame(1, PageView::count());
        $this->assertDatabaseHas('page_views', ['path' => '/', 'device' => 'mobile', 'referrer' => 'linkedin.com']);
    }
}
