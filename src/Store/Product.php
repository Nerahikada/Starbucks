<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

final readonly class Product
{
    /**
     * @param PurchaseMethod[] $purchaseMethods
     */
    public function __construct(
        public int $sku,
        public string $name,
        public ?int $price,
        public string $image,
        public array $purchaseMethods,
    ) {
        array_walk($purchaseMethods, fn($any) => assert($any instanceof PurchaseMethod));
    }
}