<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

enum PurchaseMethod: string
{
    case StarbucksCoffee = 'STARBUCKS_COFFEE';
    case ReserveBar = 'RESERVE_BAR';
    case ReserveStore = 'RESERVE_STORE';
    case ReserveRoasteryTokyo = 'RESERVE_ROASTERY_TOKYO';
    case TeaCafe = 'TEA_CAFE';
    case OnlineStore = 'ONLINE_STORE';
}