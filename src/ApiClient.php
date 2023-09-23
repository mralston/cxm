<?php

namespace Mralston\Cxm;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mralston\Cxm\Models\Campaign;
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

    /**
     * @param Contact $contact
     * @param DataList $dataList
     * @return Contact
     * @throws \Illuminate\Http\Client\RequestException
     * @deprecated
     */
    public function customerLoadSingle(Contact $contact, DataList $dataList): Contact
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

    public function createContact(Contact $contact, DataList $dataList): Contact
    {
        $payload = [
            'options' => [
                'cd_list_id' => $dataList->uuid,
                'create_customers' => true,
//                'custom_fields' , // Custom information that we might want to pass along
//                'dry_run' => true,
                'mapping_fields' => $contact->mappingFields(),
                'queue_options' => [
                    'auto_queue_for_dialling' => true,
                    'force_dial' => false,
                    'priority' => 0,
                ],
                'return_data' => true,
//                    'settings' => [
//                        'check_primary_email' => false,
//                        'check_primary_tel' => false,
//                        'dup_campaign_check' => false,
//                        'dup_campaigns' => [],
//                        'dup_list_check' => false,
//                        'dup_lists' => [],
//                        'dup_system_check' => false,
//                        'dup_upload_check' => false,
//                        'primary_email_required' => false,
//                        'primary_tel_required' => false
//                    ],
            ],
            'data' => [
                $contact->attributesToArray(),
            ]
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/contact', $payload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill([
            'uuid' => $json['data'][0]['contact_data_id']
        ]);
    }

    public function getCampaigns(): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/campaigns')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($campaign) => Campaign::make([
                'uuid' => $campaign['id'],
                ...collect($campaign)->except('id'),
            ]));
    }

    public function createCampaign(Campaign $campaign): Campaign
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/campaign', $campaign->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $campaign->fill([
            'uuid' => $json['data']['id'],
            ...collect($json['data'])->except('id'),
        ]);
    }

    public function updateCampaign(Campaign $campaign): Campaign
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/campaign/' . $campaign->uuid, $campaign->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $campaign->fill([
            ...collect($json['data'])->except('id'),
        ]);
    }

    public function deleteCampaign(Campaign $campaign): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/campaign/' . $campaign->uuid)
            ->throw();

        $json = $this->response->json();

        return true;
    }

    public function getCampaignDataLists(Campaign $campaign): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/campaign/' . $campaign->uuid . '/cd-list')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($dataList) => DataList::make([
                'uuid' => $dataList['id'],
                ...collect($dataList)->except('id'),
            ]));
    }

    public function getAllDataLists(): Collection
    {
        return $this->getCampaigns()
            ->map(fn ($campaign) => $this->getCampaignDataLists($campaign))
            ->flatten(1);
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
