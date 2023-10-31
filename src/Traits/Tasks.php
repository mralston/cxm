<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Campaign;
use Mralston\Cxm\Models\DataList;
use Mralston\Cxm\Models\Task;

trait Tasks
{
    public function createTask(Task $task): Task
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/task', $task->attributesToArray())
            ->throw();

        $json = $this->response->json();

        return $task->fill($json['data']);
    }

    public function updateTask(Campaign $campaign): Campaign
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->patch(
                $this->endpoint . '/campaign/' . $campaign->id,
                collect($campaign->getAttributes())->except('id')
            )
            ->throw();

        $json = $this->response->json();

        return $campaign->fill($json['data']);
    }

    public function deleteTask(Campaign $campaign): bool
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->delete($this->endpoint . '/campaign/' . $campaign->id)
            ->throw();

        $json = $this->response->json();

        return true;
    }
}