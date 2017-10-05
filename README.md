# Laravel autohttptests

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

**No more writing tests by hand =D**

All you have to do is turn it on and use your app as usual

This middleware will **intercept** your requests and **recreate** expected behaviour as tests. 

![example](https://user-images.githubusercontent.com/4065733/31188928-3ff9edcc-a8fc-11e7-9d64-3503ff60031c.png)

Your tests are automatically generated and saved in storage as **autohttptests.txt**

- Make requestas acting as same user
- Http requests with any verb (get, post)
- Assert http response code
- Assert errors

## Example 

Example of auto-generated tests

```
$this->post('home/something', [
    'name'         => 'a',
    'lastname'     => 'a',
    'city'         => '',
    'hobbies'   => '',
    'twitter_username' => 'a',
])
    ->assertStatus(302)
    ->assertSessionHasErrors([
        'name',
        'country_id',
        'twitter_username',
    ]);

$this->post('home/something', [
    'name'              => 'asdfa',
    'lastname'          => 'asdfa',
    'country_id' => '1',
    'city'              => '',
    'hobbies'        => '',
    'twitter_username'      => 'asdfa',
])
    ->assertStatus(302)
    ->assertRedirect('home/something');

$this->get('home/something')
    ->assertStatus(200);

```

- The first post **throws errors** because fields were too short. 
- The second post request is **successful**, doesn't have errors and redirects. 
- The last call is just following the redirection.


## Install

Via Composer

``` bash
$ composer require eduardoarandah/autohttptests
```

## Usage

Add this value to your .env file when you want to record your requests into tests

``` bash
AUTOTESTS=true
```

When you are done, change it to AUTOTESTS=false

Then create a test in your command line, example:

``` bash
php artisan make:test AutoTest
```

Copy the lines in your **storage/autohttptests.txt** file into your test, example:

``` php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class AutoTest extends TestCase
{

    public function testExample()
    {
        $this->post('home/something', [
            'name'             => 'a',
            'lastname'         => 'a',
            'city'             => '',
            'hobbies'          => '',
            'twitter_username' => 'a',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name',
                'country_id',
                'twitter_username',
            ]);

        $this->post('home/something', [
            'name'             => 'asdfa',
            'lastname'         => 'asdfa',
            'country_id'       => '1',
            'city'             => '',
            'hobbies'          => '',
            'twitter_username' => 'asdfa',
        ])
            ->assertStatus(302)
            ->assertRedirect('home/something');

        $this->get('home/something')
            ->assertStatus(200);

    }
}

```


## Credits

- [Eduardo Aranda Hernandez][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/eduardoarandah/autohttptests.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/eduardoarandah/autohttptests/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/eduardoarandah/autohttptests.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/eduardoarandah/autohttptests.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/eduardoarandah/autohttptests.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/eduardoarandah/autohttptests
[link-travis]: https://travis-ci.org/eduardoarandah/autohttptests
[link-scrutinizer]: https://scrutinizer-ci.com/g/eduardoarandah/autohttptests/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/eduardoarandah/autohttptests
[link-downloads]: https://packagist.org/packages/eduardoarandah/autohttptests
[link-author]: https://github.com/eduardoarandah
[link-contributors]: ../../contributors
