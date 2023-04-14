<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Store;

enum Category: string
{
    /** ビバレッジ メニュー */
    case Beverage = 'beverage';
    /** ビバレッジ メニュー > コーヒー */
    case Drip = 'drip';
    /** ビバレッジ メニュー > エスプレッソ */
    case Espresso = 'espresso';
    /** ビバレッジ メニュー > フラペチーノ® */
    case Frappuccino = 'frappuccino';
    /** ビバレッジ メニュー > ティー | TEAVANA™ */
    case Tea = 'tea';
    /** ビバレッジ メニュー > Others */
    case BeverageOthers = 'beverage-others';
    /** ビバレッジ メニュー > クラフトカクテル | ARRIVIAMO™ */
    case Arriviamo = 'arriviamo';

    /** フード メニュー */
    case Food = 'food';
    /** フード メニュー > デザート */
    case Dessert = 'dessert';
    /** フード メニュー > ペストリー */
    case Pastry = 'pastry';
    /** フード メニュー > サンドイッチ */
    case Sandwich = 'sandwich';
    /** フード メニュー > パッケージフード */
    case Package = 'package';
    /** フード メニュー > その他フード */
    case FoodOthers = 'food-others';

    /** コーヒー豆 */
    case Beans = 'beans';
    /** コーヒー豆 > CORE COFFEE */
    case CoreCoffee = 'corecoffee';
    /** コーヒー豆 > SEASONAL COFFEE */
    case Seasonal = 'seasonal';
    /** コーヒー豆 > スターバックス リザーブ® */
    case Reserve = 'reserve';
    /** コーヒー豆 > スターバックス オリガミ® */
    case Origami = 'origami';
    /** コーヒー豆 > スターバックス ヴィア® */
    case Via = 'via';
    /** コーヒー豆 > スターバックス ヴィア® > アソート */
    case Assort = 'assort';
    /** コーヒー豆 > スターバックス ヴィア® > コーヒー */
    case Coffee = 'coffee';
    /** コーヒー豆 > スターバックス ヴィア® > フレーバー */
    case Flavor = 'flavor';
    /** コーヒー豆 > コーヒー関連商品 */
    case CoffeeRelated = 'coffeerelated';
    /** コーヒー豆 > コーヒー関連商品 > Syrups & Sweets */
    case SyrupsSweets = 'syrupssweets';

    /** ティー・紅茶 */
    case TeaLeaf = 'tealeaf';

    /** タンブラー＆マグカップ */
    case Tumblermug = 'tumblermug';
    /** タンブラー＆マグカップ > ボトル */
    case Bottle = 'bottle';
    /** タンブラー＆マグカップ > タンブラー */
    case Tumbler = 'tumbler';
    /** タンブラー＆マグカップ > マグカップ */
    case Mug = 'mug';

    /** グッズ */
    case Goods = 'goods';
    /** グッズ > グッズ */
    case GoodsMain = 'goods-main';
    /** グッズ > スターバックス カード */
    case StarbucksCard = 'sbcard';
    /** グッズ > ビバレッジカード */
    case BeverageCard = 'bevcard';

    /** コーヒー器具 */
    case Brewing = 'brewing';
    /** コーヒー器具 > コーヒープレス */
    case CoffeePress = 'coffee-press';
    /** コーヒー器具 > ドリッパー */
    case Dripper = 'dripper';
    /** コーヒー器具 > コーヒーメーカー */
    case CoffeeMaker = 'coffee-maker';
    /** コーヒー器具 > グラインダー */
    case Grinder = 'grinder';
    /** コーヒー器具 > アクセサリ */
    case Accessory = 'accessory';
}