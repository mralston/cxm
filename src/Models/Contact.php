<?php

namespace Mralston\Cxm\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'uuid',
        'title',
        'firstname',
        'middlename',
        'lastname',
        'full_name',
        'company_name',
        'department',
        'website',
        'industry',
        'address1',
        'address2',
        'address3',
        'town',
        'county',
        'postcode',
        'country',
        'longitude',
        'latitude',
        'dob',
        'gender',
        'email',
        'sms',
        'tel1',
        'tel2',
        'tel2',
        'tel3',
        'tel4',
        'tel5',
        'tel6',
        'notes',
        'security_phrase',
        'case_reference',
        'source_reference',
    ];

    protected $attributes = [
        'uuid' => null,
        'title' => null,
        'firstname' => null,
        'middlename' => null,
        'lastname' => null,
        'full_name' => null,
        'company_name' => null,
        'department' => null,
        'website' => null,
        'industry' => null,
        'address1' => null,
        'address2' => null,
        'address3' => null,
        'town' => null,
        'county' => null,
        'postcode' => null,
        'country' => null,
        'longitude' => 0,
        'latitude' => 0,
        'dob' => null,
        'gender' => null,
        'email' => null,
        'sms' => null,
        'tel1' => null,
        'tel2' => null,
        'tel2' => null,
        'tel3' => null,
        'tel4' => null,
        'tel5' => null,
        'tel6' => null,
        'notes' => null,
        'security_phrase' => null,
        'case_reference' => null,
        'source_reference' => null,
    ];

    protected $casts = [
        'dob' => 'date:Y-m-d',
    ];
}