<?php

namespace Mralston\Cxm\Enums;

enum AuthMode: int
{
    case BASIC = 0;
    case BEARER = 1;
}