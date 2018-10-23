[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * Support multi-level array (dot delimited keys) structure.
 * Localization supported.

## Installation

1. Install package

    ```bash
    composer require tantana5/laravel-settings
    ```

1. Edit config/app.php (Skip this step if you are using laravel 5.5+)

    service provider:

    ```php
    Tantana5\Setting\SettingServiceProvider::class,
    ```

    class aliases:

    ```php
    'Setting' => Tantana5\Setting\SettingFacade::class,
    ```

1. Create settings table

    ```bash
    php artisan vendor:publish --tag=settings
    php artisan migrate
    ```

## Usage

```php
Setting::get('name', 'Computer');
// get setting value with key 'name'
// return 'Computer' if the key does not exists

Setting::all();
// get all settings

Setting::lang('zh-TW')->get('name', 'Computer');
// get setting value with key and language

Setting::set('name', 'Computer', 'collection');
// set setting value by key and collection name

Setting::collection('collection');
// get all settings by collection

Setting::lang('zh-TW')->set('name', 'Computer');
// set setting value by key and language

Setting::has('name');
// check the key exists, return boolean

Setting::lang('zh-TW')->has('name');
// check the key exists by language, return boolean

Setting::forget('name');
// delete the setting by key

Setting::lang('zh-TW')->forget('name');
// delete the setting by key and language
```

## Dealing with array

```php
Setting::get('item');
// return null;

Setting::set('item', ['USB' => '8G', 'RAM' => '4G']);
Setting::get('item');
// return array(
//     'USB' => '8G',
//     'RAM' => '4G',
// );

Setting::get('item.USB');
// return '8G';
```

## Dealing with locale

By default language parameter are being resets every set or get calls. You could disable that and set your own long term language parameter forever using any route service provider or other method.

```php
Setting::lang(App::getLocale())->langResetting(false);
```

[ico-version]: https://img.shields.io/packagist/v/Tantana5/categorizable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Tantana5/categorizable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Tantana5/categorizable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Tantana5/categorizable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Tantana5/categorizable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/unisharp/categorizable
[link-travis]: https://travis-ci.org/Tantana5/categorizable
[link-scrutinizer]: https://scrutinizer-ci.com/g/Tantana5/categorizable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Tantana5/categorizable
[link-downloads]: https://packagist.org/packages/Tantana5/categorizable
[link-author]: https://github.com/Tantana5
[link-contributors]: ../../contributors
