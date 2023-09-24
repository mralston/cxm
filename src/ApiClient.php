<?php

namespace Mralston\Cxm;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use Mralston\Cxm\Traits\Auth;
use Mralston\Cxm\Traits\Campaigns;
use Mralston\Cxm\Traits\Contacts;
use Mralston\Cxm\Traits\DataLists;
use Mralston\Cxm\Traits\Users;

class ApiClient
{
    use Auth;
    use Campaigns;
    use Contacts;
    use DataLists;
    use Users;

    private Response $response;

    public function __construct(
        private string $clientId,
        private string $secret,
        private string $token,
        private string $endpoint
    ) {
        $this->endpoint = Str::of($this->endpoint)->rtrim('/');
    }

    public function getLastResponse(): Response
    {
        return $this->response;
    }
}
