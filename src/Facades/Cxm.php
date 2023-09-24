<?php

namespace Mralston\Cxm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mralston\Cxm\ApiClient
 * @method static \Illuminate\Http\Client\Response getLastResponse()
 *
 * @see \Mralston\Cxm\Traits\Campaigns
 * @method static \Illuminate\Support\Collection getCampaigns()
 * @method static \Mralston\Cxm\Models\Campaign createCampaign(\Mralston\Cxm\Models\Campaign $campaign)
 * @method static \Mralston\Cxm\Models\Campaign updateCampaign(\Mralston\Cxm\Models\Campaign $campaign)
 * @method static bool deleteCampaign(\Mralston\Cxm\Models\Campaign $campaign)
 * @method static \Illuminate\Support\Collection getCampaignDataLists(\Mralston\Cxm\Models\Campaign $campaign)
 *
 * @see \Mralston\Cxm\Traits\Contacts
 * @method static \Mralston\Cxm\Models\Contact customerLoadSingle(\Mralston\Cxm\Models\Contact $contact, \Mralston\Cxm\Models\DataList $dataList)
 * @method static \Mralston\Cxm\Models\Contact createContact(\Mralston\Cxm\Models\Contact $contact, \Mralston\Cxm\Models\DataList $dataList)
 *
 * @see \Mralston\Cxm\Traits\DataLists
 * @method static \Illuminate\Support\Collection getDataLists()
 * @method static \Mralston\Cxm\Models\DataList createDataList(\Mralston\Cxm\Models\DataList $dataList)
 * @method static \Mralston\Cxm\Models\DataList updateDataList(\Mralston\Cxm\Models\DataList $dataList)
 * @method static bool deleteDataList(\Mralston\Cxm\Models\DataList $dataList)
 *
 * @see \Mralston\Cxm\Traits\InboundNumbers
 * @method static getInboundNumbers(): \Illuminate\Support\Collection
 * @method static \Mralston\Cxm\Models\InboundNumber createInboundNumber(\Mralston\Cxm\Models\InboundNumber $inboundNumber)
 * @method static \Mralston\Cxm\Models\InboundNumber updateInboundNumber(\Mralston\Cxm\Models\InboundNumber $inboundNumber)
 * @method static bool deleteInboundNumber(\Mralston\Cxm\Models\InboundNumber $inboundNumber)
 *
 * @see \Mralston\Cxm\Traits\Sources
 * @method static \Mralston\Cxm\Models\Source getSource(\Mralston\Cxm\Models\Source $source)
 *
 * @see \Mralston\Cxm\Traits\Users
 * @method static \Illuminate\Support\Collection getUsers()
 * @method static \Mralston\Cxm\Models\User createUser(\Mralston\Cxm\Models\User $user)
 * @method static \Mralston\Cxm\Models\User updateUser(\Mralston\Cxm\Models\User $user)
 * @method static bool deleteUser(\Mralston\Cxm\Models\User $user)
 */
class Cxm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cxm';
    }
}
