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
            ->map(fn ($campaign) => Campaign::make($campaign));
    }

    public function createCampaign(Campaign $campaign): Campaign
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/campaign', $campaign->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $campaign->fill($json['data']);
    }

    public function updateCampaign(Campaign $campaign): Campaign
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/campaign/' . $campaign->id, $campaign->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $campaign->fill($json['data']);
    }

    public function deleteCampaign(Campaign $campaign): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/campaign/' . $campaign->id)
            ->throw();

        $json = $this->response->json();

        return true;
    }

    public function getCampaignDataLists(Campaign $campaign): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/campaign/' . $campaign->id . '/cd-list')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($dataList) => DataList::make($dataList));
    }
}