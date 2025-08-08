<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     */
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google']), 404);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider and log them in / register.
     */
    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google']), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            Log::warning('Social login failed: '.$e->getMessage(), [
                'provider' => $provider,
            ]);

            return redirect()->route('login')->with('status', 'Unable to authenticate with '.$provider.'. Please try again.');
        }

        $providerId = (string) $socialUser->getId();
        $email = $socialUser->getEmail();
        $displayName = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User');

        // Find existing user by provider id or email (only if email is available)
        $query = User::query()->where(function ($q) use ($provider, $providerId) {
            $q->where('provider', $provider)
                ->where('provider_id', $providerId);
        });

        if ($email) {
            $query->orWhere(function ($q) use ($email) {
                $q->where('email', $email);
            });
        }

        $user = $query->first();

        if (! $user) {
            $user = new User;
            $user->name = $displayName;
            $user->email = $email ?: (Str::uuid().'@example.local');
            // Set a random strong password to satisfy non-null constraint
            $user->password = Str::password();
            $user->email_verified_at = now();
        }

        // Link/update provider details
        $user->provider = $provider;
        $user->provider_id = $providerId;
        $user->avatar = $socialUser->getAvatar();
        $user->save();

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
