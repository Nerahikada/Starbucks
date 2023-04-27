<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Cart;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Nerahikada\Starbucks\Constants;
use Nerahikada\Starbucks\Exception\InvalidTokenException;
use Nerahikada\Starbucks\Exception\TokenExpiredException;
use Nerahikada\Starbucks\Store\Product;

final readonly class ShoppingCart
{
    private CookieJar $jar;
    private Client $client;

    public function __construct(string $token = null)
    {
        if ($token && (strlen($token) !== 128 || !ctype_xdigit($token))) {
            throw new InvalidTokenException('Invalid token format');
        }

        $this->client = new Client([
            RequestOptions::COOKIES => $this->jar = new CookieJar(
                true,
                [['Name' => 'sbj_ols_cart_token_production', 'Value' => $token ?? '', 'Domain' => '.starbucks.co.jp']]
            ),
            RequestOptions::HEADERS => [
                'User-Agent' => Constants::USER_AGENT,
            ],
        ]);

        $this->client->get('https://cart.starbucks.co.jp/');
        if ($token && $this->getToken() !== $token) {
            throw new InvalidTokenException('The token is expired or invalid');
        }
    }

    public function getToken(): string
    {
        return $this->jar->getCookieByName('sbj_ols_cart_token_production')->getValue();
    }

    public function touch(): void
    {
        if ($this->isExpired()) {
            throw new TokenExpiredException('The token expired on ' . $this->getExpireAt()->format(DATE_ATOM));
        }

        $oldToken = $this->getToken();
        $this->client->get('https://cart.starbucks.co.jp/');
        if ($this->getToken() !== $oldToken) {
            // I haven't seen it, but should I consider it?
            throw new TokenExpiredException('The token expired while renewing');
        }
    }

    public function isExpired(): bool
    {
        return $this->getExpireAt() < new DateTimeImmutable();
    }

    public function getExpireAt(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            urldecode($this->jar->getCookieByName('sbj_ols_cart_token_expire_production')->getValue())
        );
    }

    public function addProduct(Product $product, int $amount = 1): bool
    {
        $response = $this->client->post('https://menu.starbucks.co.jp/api/items/cart', [
            RequestOptions::JSON => ['jan_code' => $product->sku, 'quantity' => $amount],
            RequestOptions::HTTP_ERRORS => false,
        ]);
        $data = json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);
        //var_dump($data);
        return match ($data['code']) {
            '000' => false, // unknown error, おそらくオンラインストで購入できないものを購入しようとした
            '110' => false, // out of stock
            '130' => false, // 同時購入不可, 例えば通常商品と冷凍商品(4524785522183)
            200 => true,
        };
    }

    // TODO: get cart contents
}