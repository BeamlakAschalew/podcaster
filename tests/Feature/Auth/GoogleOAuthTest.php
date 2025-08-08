<?php

use App\Models\User;
use Laravel\Socialite\Contracts\Provider as SocialiteProviderContract;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;

afterEach(function () {
    // Ensure Mockery expectations are cleared between tests
    Mockery::close();
});

function mockGoogleUser(array $overrides = []): void
{
    $defaults = [
        'getId' => 'google-123',
        'getEmail' => 'new.user@example.com',
        'getName' => 'New User',
        'getNickname' => null,
        'getAvatar' => 'https://example.com/avatar.png',
    ];

    $attrs = array_merge($defaults, $overrides);

    $socialUser = Mockery::mock(SocialiteUserContract::class);
    foreach ($attrs as $method => $return) {
        $socialUser->shouldReceive($method)->andReturn($return);
    }

    $provider = Mockery::mock(SocialiteProviderContract::class);
    $provider->shouldReceive('user')->andReturn($socialUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
}

// Ensures the redirect endpoint returns a redirect response to Google's OAuth screen
// without hitting the network.
test('google oauth redirect responds with a redirect', function () {
    // Minimal config to construct the redirect URL
    config([
        'services.google.client_id' => 'test-client-id',
        'services.google.client_secret' => 'test-client-secret',
        'services.google.redirect' => config('app.url').'/auth/google/callback',
    ]);

    $response = $this->get('/auth/google/redirect');

    $response->assertRedirect();
    expect($response->headers->get('Location'))
        ->not->toBeNull()
        ->toContain('https://accounts.google.com');
});

// New user is created and authenticated on first Google login
test('new users can register via Google OAuth', function () {
    mockGoogleUser([
        'getId' => 'google-abc-1',
        'getEmail' => 'oauth.new@example.com',
        'getName' => 'OAuth New',
    ]);

    $response = $this->get('/auth/google/callback');

    $user = User::where('email', 'oauth.new@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->provider)->toBe('google');
    expect($user->provider_id)->toBe('google-abc-1');
    expect($user->avatar)->toBe('https://example.com/avatar.png');
    expect($user->email_verified_at)->not->toBeNull();

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

// Existing user (matched by email) is linked and logged in
test('existing users can login via Google OAuth by email', function () {
    $existing = User::factory()->create([
        'email' => 'existing@example.com',
        'provider' => null,
        'provider_id' => null,
        'avatar' => null,
    ]);

    mockGoogleUser([
        'getId' => 'google-xyz-9',
        'getEmail' => 'existing@example.com',
        'getName' => $existing->name,
        'getAvatar' => 'https://example.com/new-avatar.png',
    ]);

    $response = $this->get('/auth/google/callback');

    $user = $existing->fresh();

    $this->assertAuthenticatedAs($user);
    expect($user->provider)->toBe('google');
    expect($user->provider_id)->toBe('google-xyz-9');
    expect($user->avatar)->toBe('https://example.com/new-avatar.png');

    $response->assertRedirect(route('dashboard', absolute: false));
});

// Existing user matched by provider id should log in and update avatar without changing email
test('existing users can login via Google OAuth by provider id', function () {
    $existing = User::factory()->create([
        'email' => 'unchanged@example.com',
        'provider' => 'google',
        'provider_id' => 'google-keep-me',
        'avatar' => null,
    ]);

    mockGoogleUser([
        'getId' => 'google-keep-me',
        // Different email returned from provider should not change the local email
        'getEmail' => 'different@example.com',
        'getName' => $existing->name,
        'getAvatar' => 'https://example.com/updated-avatar.png',
    ]);

    $response = $this->get('/auth/google/callback');

    $user = $existing->fresh();

    $this->assertAuthenticatedAs($user);
    expect($user->email)->toBe('unchanged@example.com');
    expect($user->provider)->toBe('google');
    expect($user->provider_id)->toBe('google-keep-me');
    expect($user->avatar)->toBe('https://example.com/updated-avatar.png');

    $response->assertRedirect(route('dashboard', absolute: false));
});

// When provider does not return an email, we still create a user with a local email and authenticate
test('registers user with generated local email when google provides no email', function () {
    mockGoogleUser([
        'getId' => 'google-no-email-1',
        'getEmail' => null,
        'getName' => 'No Email User',
    ]);

    $response = $this->get('/auth/google/callback');

    $user = User::where('provider_id', 'google-no-email-1')->first();

    expect($user)->not->toBeNull();
    expect($user->email)->toContain('@example.local');
    expect($user->name)->toBe('No Email User');
    expect($user->email_verified_at)->not->toBeNull();

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

// Uses nickname when name is missing from provider
test('uses nickname when name is missing', function () {
    mockGoogleUser([
        'getId' => 'google-nick-1',
        'getEmail' => 'nick@example.com',
        'getName' => null,
        'getNickname' => 'Cool Nick',
    ]);

    $response = $this->get('/auth/google/callback');

    $user = User::where('email', 'nick@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Cool Nick');

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

// Failure from provider redirects back to login with a status message and no authentication
test('callback failure redirects to login with status', function () {
    $provider = Mockery::mock(SocialiteProviderContract::class);
    $provider->shouldReceive('user')->andThrow(new \Exception('invalid token'));
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(route('login', absolute: false));
    $response->assertSessionHas('status');
    $this->assertGuest();
});

// Unknown providers should 404 on both redirect and callback routes
test('unknown provider routes return 404', function () {
    $this->get('/auth/github/redirect')->assertNotFound();
    $this->get('/auth/github/callback')->assertNotFound();
});

// Ensures a remember token is set when logging in via Google
test('sets remember token on google login', function () {
    mockGoogleUser([
        'getId' => 'google-remember-1',
        'getEmail' => 'remember.me@example.com',
        'getName' => 'Remember Me',
    ]);

    $this->get('/auth/google/callback');

    $user = User::where('email', 'remember.me@example.com')->first();

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->getRememberToken())->not->toBeNull();
});

// Defaults to generic name when both name and nickname are missing from provider
test('defaults name to User when provider has no name or nickname', function () {
    mockGoogleUser([
        'getId' => 'google-noname-1',
        'getEmail' => 'noname@example.com',
        'getName' => null,
        'getNickname' => null,
    ]);

    $this->get('/auth/google/callback');

    $user = User::where('email', 'noname@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('User');
});
