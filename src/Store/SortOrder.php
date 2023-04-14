<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

enum SortOrder: string
{
    case NEWEST = "";   // actual desired value is null
    case RECOMMEND = "recommend";
    case HIGHER_PRICE = "price_high";
    case LOWER_PRICE = "price_low";
}