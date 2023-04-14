<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

enum Brand: string
{
    case StarbucksCoffee = "starbucks-coffee";
    case StarbucksReserveRoasteryTokyo = "starbucks-reserve-roastery-tokyo";
    case StarbucksReserve = "starbucks-reserve";
    case Teavana = "teavana";
    case BeenThereSeries = "been-there-series";
    case JimotoMadePlus = "jimoto-made-plus";
    case JimotoMadeSeries = "jimoto-made-series";
}