<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\DataList;

trait DataLists
{
    public function getDataLists(): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/cd-list')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($dataList) => DataList::make([
                'uuid' => $dataList['id'],
                ...collect($dataList)->except('id'),
            ]));
    }

    public function createDataList(DataList $dataList): DataList
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/cd-list', $dataList->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $dataList->fill([
            'uuid' => $json['data']['id'],
            ...collect($json['data'])->except('id'),
        ]);
    }

    public function updateDataList(DataList $dataList): DataList
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/cd-list/' . $dataList->uuid, $dataList->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $dataList->fill([
            ...collect($json['data'])->except('id'),
        ]);
    }

    public function deleteDataList(DataList $dataList): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/cd-list/' . $dataList->uuid)
            ->throw();

        $json = $this->response->json();

        return true;
    }
}