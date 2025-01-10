<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Source;

trait Sources
{
//    public function getSources(): Collection
//    {
//        $this->response = Http::withHeaders($this->authHeaders())
//            ->get($this->endpoint . '/cd-source')
//            ->throw();
//
//        $json = $this->response->json();
//
//        return collect($json['data'])
//            ->map(fn ($user) => Source::make($source));
//    }

    public function getSource(Source $source): Source
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/cd-source/' . $source->id)
            ->throw();

        $json = $this->response->json();

        return Source::make($json['data']);
    }

//    public function createSource(Source $source): Source
//    {
//        $this->response = Http::withHeaders($this->authHeaders())
//            ->post($this->endpoint . '/cd-source', $source->attributesToArray())
//            ->throw();
//
//        $json = $this->response->json();
//
//        return $source->fill($json['data']);
//    }

//    public function updateSource(Source $source): Source
//    {
//        $this->response = Http::withHeaders($this->authHeaders())
//            ->patch($this->endpoint . '/cd-source/' . $source->id, $source->attributesToArray())
//            ->throw();
//
//        $json = $this->response->json();
//
//        return $source->fill($json['data']);
//    }

//    public function deleteSource(Source $source): bool
//    {
//        $this->response = Http::withHeaders($this->authHeaders())
//            ->delete($this->endpoint . '/cd-source/' . $source->id)
//            ->throw();
//
//        $json = $this->response->json();
//
//        return true;
//    }
}