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
        $payload = [
            'cd_list_id' => $dataList->id,
            'contact_data' => $contact->attributesToArray(),
            'customer_tags' => []
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/customer/load/single', $payload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill($json['data']['contact_data']);
    }

    public function createContact(Contact $contact, DataList $dataList): Contact
    {
        $payload = [
            'options' => [
                'cd_list_id' => $dataList->id,
                'create_customers' => true,
//                'custom_fields' , // Custom information that we might want to pass along
//                'dry_run' => true,
                'mapping_fields' => $contact->mappingFields(),
                'queue_options' => [
                    'auto_queue_for_dialling' => true,
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
                $contact->attributesToArray(),
            ]
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->post($this->endpoint . '/contact', $payload)
            ->throw();

        $json = $this->response->json();

        return $contact->fill($json['data'][0]);
    }
}