<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Contact;
use Mralston\Cxm\Models\DataList;

trait Contacts
{
    /**
     * @param Contact $contact
     * @param DataList $dataList
     * @return Contact
     * @throws \Illuminate\Http\Client\RequestException
     * @deprecated
     */
    public function customerLoadSingle(Contact $contact, DataList $dataList): Contact
    {
        $this->requestPayload = [
            'cd_list_id' => $dataList->id,
            'contact_data' => $contact->attributesToArray(),
            'customer_tags' => []
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/customer/load/single', $this->requestPayload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill($json['data']['contact_data']);
    }

    public function createContact(Contact $contact, DataList $dataList): Contact
    {
        $this->requestPayload = [
            'options' => [
                'cd_list_id' => $dataList->id,
                'create_customers' => true,
                'custom_fields' => $contact->customMappingFields(),
//                'dry_run' => true,
                'mapping_fields' => $contact->mappingFields(),
                'queue_options' => [
                    'auto_queue_for_dialling' => false, // This *forces* them into the 'hopper' so to speak
                    'force_dial' => false,
                    'priority' => 0,
                ],
                'return_data' => true,
//                    'settings' => [
//                        'check_primary_email' => false,
//                        'check_primary_tel' => false,
//                        'dup_campaign_check' => false,
//                        'dup_campaigns' => [],
//                        'dup_list_check' => false,
//                        'dup_lists' => [],
//                        'dup_system_check' => false,
//                        'dup_upload_check' => false,
//                        'primary_email_required' => false,
//                        'primary_tel_required' => false
//                    ],
            ],
            'data' => [
                [
                    ...$contact->attributesToArray(),
                    ...$contact->customFields,
                ]
            ]
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/contact', $this->requestPayload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill($json['data'][0]);
    }

    public function getContact(string $uuid): Contact
    {
        //create the contact
        $contact = Contact::make();

        //get the contact from cxm
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/contact/' . $uuid)
            ->throw();

        //get the json response
        $json = $this->response->json();

        //return the contact
        return $contact->fill($json['data']);

    }
}
