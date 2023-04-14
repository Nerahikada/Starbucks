<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

use BackedEnum;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Nerahikada\Starbucks\Constants;

final readonly class OnlineStore
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            RequestOptions::HEADERS => [
                'User-Agent' => Constants::USER_AGENT
            ],
        ]);
    }

    /**
     * @param string|null $query keywords
     * @param PurchaseMethod[]|null $purchaseMethods
     * @param Brand[]|null $brands
     * @param int[]|float[]|null $price
     * @yield Product
     */
    public function searchProducts(
        ?string $query = null,
        ?Category $category = null,
        ?array $purchaseMethods = null,
        ?bool $inStock = true, //オンライン在庫の有無
        ?bool $onlineOnly = null,   //オンラインストア限定
        ?array $brands = null,
        ?SortOrder $sort = null,
        ?array $price = null,    //tuple? // e.g. price=5000.0-*
        // TODO: more params
    ): Generator
    {
        array_map(fn(PurchaseMethod $_) => $_, $purchaseMethods ?? []);
        array_map(fn(Brand $_) => $_, $brands ?? []);

        $toString = fn(array $enums): string => implode(',', array_map(fn(BackedEnum $e) => $e->value, $enums));

        return $this->fetchProducts([
            'query' => $query,
            'category_code' => $category?->value,
            'purchase_methods' => $purchaseMethods ? $toString($purchaseMethods) : null,
            'inventory_quantity' => $inStock,
            'online_store' => $onlineOnly,
            'brand_code' => $brands ? $toString($brands) : null,
            'sort' => $sort?->value,
        ]);
    }

    /**
     * @yield Product
     */
    public function fetchProducts(array $queries = []): Generator
    {
        $page = 1;
        $count = 0;
        do {
            $response = $this->client->get(
                'https://menu.starbucks.co.jp/search',
                [RequestOptions::QUERY => [...$queries, 'page' => $page++]]
            );
            $json = explode('=', explode('</script>', $response->getBody()->getContents(), 2)[0], 2)[1];
            $result = json_decode($json, true, flags: JSON_THROW_ON_ERROR)['data'];
            foreach ($result['hits']['hits'] as $item) {
                $methods = array_map(PurchaseMethod::from(...), $item['_source']['purchase_methods']);
                $defaultImage = null;
                $productData = [];
                foreach ($item['inner_hits']['sku_images']['hits']['hits'] as $image) {
                    $sku = (int)$image['_source']['sku_code'];
                    $defaultImage ??= $productData[$sku][0][] = $image['_source']['image_url'];
                }
                foreach ($item['inner_hits']['prices']['hits']['hits'] as $price) {
                    $sku = (int)$price['_source']['sku_code'];
                    $productData[$sku][1][] = $price['_source']['price_in_vat'];
                }
                foreach ($productData as $sku => $_) {
                    $productData[$sku][0] ??= [$defaultImage];
                    $productData[$sku][1] ??= [null];
                    assert(count($productData[$sku][0]) === 1);
                    assert(count($productData[$sku][1]) === 1);
                }
                foreach ($productData as $sku => $data) {
                    yield new Product($sku, $item['_source']['item_name'], $data[1][0], $data[0][0], $methods);
                }
                ++$count;
            }
        } while ($result['hits']['total']['value'] > $count);
    }
}