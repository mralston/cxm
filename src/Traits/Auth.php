<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait Auth
{
    private function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->authenticate(),
            'X-Authorization' => 'Bearer ' . $this->token,
        ];
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
}