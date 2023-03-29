<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

use Generator;
use GuzzleHttp\Client;
use Nerahikada\Starbucks\Constants;

final class OnlineStore
{
    private readonly Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'User-Agent' => Constants::USER_AGENT
            ],
            'verify' => false
        ]);
    }

    public function fetchProducts(): Generator
    {
        $page = 1;
        $count = 0;
        do {
            $response = $this->client->get('https://menu.starbucks.co.jp/search', ['query' => ['page' => $page++]]);
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