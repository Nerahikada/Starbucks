<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

enum PurchaseMethod: string
{
    case StarbucksCoffee = 'STARBUCKS_COFFEE';
    case OnlineStore = 'ONLINE_STORE';
    case ReserveStore = 'RESERVE_STORE';
    case ReserveBar = 'RESERVE_BAR';
    case ReserveRoasteryTokyo = 'RESERVE_ROASTERY_TOKYO';
    case TeaCafe = 'TEA_CAFE';
}