<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Campaign;
use Mralston\Cxm\Models\DataList;

trait Campaigns
{
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
}