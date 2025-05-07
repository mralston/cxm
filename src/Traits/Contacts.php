<?php

namespace Mralston\Cxm\Traits;

use Illuminate\Support\Facades\Http;
use Mralston\Cxm\Models\Contact;
use Mralston\Cxm\Models\DataList;
use Mralston\Cxm\Models\Source;

trait Contacts
{
    public function getContact(Contact $contact): Contact
    {
        $this->response = Http::withHeaders($this->authHeaders())
            ->get($this->endpoint . '/contact/' . $contact->id)
            ->throw();

        $json = $this->response->json();

        return Contact::make($json['data']);

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

    public function updateContact(Contact $contact, DataList $dataList): Contact
    {
        $this->requestPayload = [
            'cd_list_id' => $dataList->id,
            'contact_data' => collect($contact->getAttributes())
                ->except('id')
                ->toArray(),
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/contact/' . $contact->id, $this->requestPayload)
            ->throw();

        $json = $this->response->json();

        return Contact::make($json['data']);
    }

    public function setContactOutcome(Contact $contact, DataList $dataList, string $outcomeId): bool
    {
        $this->requestPayload = [
            'cd_list_id' => $dataList->id,
            'outcome_id' => $outcomeId,
        ];

        $this->response = Http::withHeaders($this->authHeaders())
            ->patch($this->endpoint . '/contact/' . $contact->id, $this->requestPayload)
            ->throw();

        return $this->response->successful();
    }
}
