<?php

namespace Mralston\Cxm\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date:Y-m-d',
    ];

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
}