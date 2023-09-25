<?php

namespace Mralston\Cxm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Contact extends Model
{
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date:Y-m-d',
    ];

    public array $customFields = [];

    public function mappingFields()
    {
        return collect($this->getAttributes())
            ->keys()
            ->flip()
            ->map(function ($fluff, $attribute) {
                return [
                    'title' => $attribute,
                ];
            })
            ->toArray();
    }

    public function customMappingFields()
    {
        return collect($this->customFields)
            ->keys()
            ->flip()
            ->map(function ($fluff, $attribute) {
                return [
                    'title' => $attribute,
                ];
            })
            ->toArray() ?? [];
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields)
    {
        $this->customFields = $customFields;
        return $this;
    }
}