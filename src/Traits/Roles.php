<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Role;

trait Roles
{
    public function getRoles(): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/role')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($role) => Role::make($role));
    }
}