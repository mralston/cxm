<?php

namespace Mralston\Cxm\Enums;

enum AuthMode: string
{
    case BASIC = 'basic';
    case BEARER = 'bearer';
}