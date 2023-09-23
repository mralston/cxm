<?php

namespace Mralston\Cxm;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mralston\Cxm\Models\Contact;
use Mralston\Cxm\Models\DataList;

class ApiClient
{
    private Response $response;

    public function __construct(
        private string $clientId,
        private string $secret,
        private string $token,
        private string $endpoint
    ) {
        $this->endpoint = Str::of($this->endpoint)->rtrim('/');
    }

    public function customerLoadSingle(Contact $contact, DataList $dataList)
    {
        $payload = [
            'cd_list_id' => $dataList->uuid,
            'contact_data' => $contact->attributesToArray(),
            'customer_tags' => []
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/customer/load/single', $payload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill([
            'uuid' => $json['data']['id'],
            ...collect($json['data']['contact_data'])->only([
                'full_name',
                'created_at',
                'updated_at',
            ])
        ]);
    }

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

    public function getLastResponse(): Response
    {
        return $this->response;
    }
}
