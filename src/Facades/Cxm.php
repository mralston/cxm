<?php

namespace Mralston\Cxm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static addressPostcodeReady(string $postcode)
 *
 * @see \Mralston\Cxm\Api
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
