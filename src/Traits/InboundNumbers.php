<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\DataList;
use Mralston\Cxm\Models\InboundNumber;

trait InboundNumbers
{
    public function getInboundNumbers(): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/inbound-number')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($inboundNumber) => InboundNumber::make($inboundNumber));
    }

    public function createInboundNumber(InboundNumber $inboundNumber): InboundNumber
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/inbound-number', $inboundNumber->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $inboundNumber->fill($json['data']);
    }

    public function updateInboundNumber(InboundNumber $inboundNumber): InboundNumber
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch(
                $this->endpoint . '/inbound-number/' . $inboundNumber->id,
                collect($inboundNumber->getAttributes())->except('id')
            )
            ->throw();

        $json = $this->response->json();

        return $inboundNumber->fill($json['data']);
    }

    public function deleteInboundNumber(InboundNumber $inboundNumber): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/inbound-number/' . $inboundNumber->id)
            ->throw();

        $json = $this->response->json();

        return true;
    }
}