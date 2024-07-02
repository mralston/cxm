<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Enums\AuthMode;

trait Auth
{
    protected AuthMode $authMode = AuthMode::BASIC;

    private function authHeaders(): array
    {
        return match ($this->authMode) {
            // Original CXM authentication used a bearer token for both headers
            AuthMode::BEARER => [
                'Authorization' => 'Bearer ' . $this->authenticate(), // oAuth token
                'X-Authorization' => 'Bearer ' . $this->token, // CXM basic auth token
            ],
            // CXM 15.4+ authentication uses a basic token for the X-Authorization header
            AuthMode::BASIC => [
                'Authorization' => 'Bearer ' . $this->authenticate(), // oAuth token
                'X-Authorization' => 'Basic ' . $this->token, // CXM basic auth token
            ]
        };
    }

    private function authenticate(): string
    {
        $cacheKey = static::class . '-oauth-token-' . $this->clientId;

        if (!empty($token = Cache::get($cacheKey))) {
            return $token;
        }

        $this->response = Http::asForm()
            ->post($this->endpoint . '/oauth2/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->secret,
            ])
            ->throw();

        $json = $this->response->json();

        Cache::put($cacheKey, $json['access_token'], $json['expires_in']);

        return $json['access_token'];
    }

    public function basicAuth()
    {
        $this->authMode = AuthMode::BASIC;
        return $this;
    }

    public function bearerAuth()
    {
        $this->authMode = AuthMode::BEARER;
        return $this;
    }

    public function setAuthMode(AuthMode $authMode)
    {
        $this->authMode = $authMode;
    }

    public function getAuthMode(): AuthMode
    {
        return $this->authMode;
    }
}