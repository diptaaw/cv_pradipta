<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeepAliveAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_keepalive_endpoint_returns_healthy_and_no_cookies(): void
    {
        $response = $this->get('/api/keepalive');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'healthy',
            'database' => 'connected',
        ]);
        $response->assertCookieMissing(config('session.cookie'));
        $response->assertHeader('Cache-Control');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_keepalive_endpoint_also_available_at_root_keepalive(): void
    {
        $response = $this->get('/keepalive');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'healthy',
            'database' => 'connected',
        ]);
        $response->assertCookieMissing(config('session.cookie'));
    }

    public function test_keepalive_endpoint_supports_head_request(): void
    {
        $response = $this->head('/api/keepalive');
        $response->assertStatus(200);
    }

    public function test_keepalive_enforces_cron_secret_when_configured(): void
    {
        config(['services.cron_secret' => 'test-cron-token-12345']);

        // Unauthenticated request should fail with 401
        $unauthResponse = $this->get('/api/keepalive');
        $unauthResponse->assertStatus(401);

        // Incorrect bearer token should fail with 401
        $badBearerResponse = $this->withHeader('Authorization', 'Bearer wrong-token')
            ->get('/api/keepalive');
        $badBearerResponse->assertStatus(401);

        // Incorrect query key should fail with 401
        $badQueryResponse = $this->get('/api/keepalive?key=wrong-token');
        $badQueryResponse->assertStatus(401);

        // Bearer token authentication should succeed
        $bearerResponse = $this->withHeader('Authorization', 'Bearer test-cron-token-12345')
            ->get('/api/keepalive');
        $bearerResponse->assertStatus(200);
        $bearerResponse->assertJson(['status' => 'healthy']);

        // Query key authentication should succeed
        $queryResponse = $this->get('/api/keepalive?key=test-cron-token-12345');
        $queryResponse->assertStatus(200);
        $queryResponse->assertJson(['status' => 'healthy']);

        config(['services.cron_secret' => null]);
    }

    public function test_notifications_endpoint_requires_admin_auth(): void
    {
        $response = $this->getJson('/api/notifications');
        $response->assertStatus(401);

        $unreadResponse = $this->getJson('/api/notifications/unread-count');
        $unreadResponse->assertStatus(401);

        $markAllResponse = $this->postJson('/api/notifications/mark-read');
        $markAllResponse->assertStatus(401);
    }

    public function test_notifications_endpoint_forbidden_for_non_admin_users(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'role_id' => null,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications');
        $response->assertStatus(403);
    }

    public function test_notifications_endpoint_accessible_by_admin(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
        ]);

        $notification = Notification::send('test', 'Test Notification', 'Description');

        $response = $this->actingAs($admin)->getJson('/api/notifications');
        $response->assertStatus(200);

        $unreadResponse = $this->actingAs($admin)->getJson('/api/notifications/unread-count');
        $unreadResponse->assertStatus(200);
        $unreadResponse->assertJson(['count' => 1]);

        $markReadResponse = $this->actingAs($admin)->postJson("/api/notifications/{$notification->id}/mark-read");
        $markReadResponse->assertStatus(200);
        $markReadResponse->assertJson(['success' => true]);

        $this->assertTrue($notification->fresh()->is_read);

        $markAllResponse = $this->actingAs($admin)->postJson('/api/notifications/mark-read');
        $markAllResponse->assertStatus(200);
        $markAllResponse->assertJson(['success' => true]);
    }

    public function test_admin_login_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'attacker@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt within the 1-minute window should be throttled
        $throttledResponse = $this->post('/admin/login', [
            'email' => 'attacker@example.com',
            'password' => 'wrongpassword',
        ]);

        $throttledResponse->assertStatus(429);
    }

    public function test_db_test_endpoint_is_blocked_when_debug_is_false(): void
    {
        config(['app.debug' => false]);
        $response = $this->get('/db-test');
        $response->assertStatus(404);
    }

    public function test_api_updates_returns_success(): void
    {
        $response = $this->get('/api/updates');
        $response->assertStatus(200);
    }

    public function test_api_archive_projects_returns_success(): void
    {
        $response = $this->get('/api/archive/projects');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'projects',
            'filters',
        ]);
    }
}
