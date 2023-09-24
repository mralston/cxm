<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\User;

trait Users
{
    public function getUsers(): Collection
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/user')
            ->throw();

        $json = $this->response->json();

        return collect($json['data'])
            ->map(fn ($user) => User::make($user));
    }

    public function createUser(User $user): User
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/user', $user->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $user->fill($json['data']);
    }

    public function updateUser(User $user): User
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/user/' . $user->id, $user->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $user->fill($json['data']);
    }

    public function deleteUser(User $user): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/user/' . $user->id)
            ->throw();

        $json = $this->response->json();

        return true;
    }
}