# Pickle

[![Build Status](https://travis-ci.org/alnutile/pickle.svg?branch=master)](https://travis-ci.org/alnutile/pickle)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Gherkin to Dusk

This will attempt to make a easy way to work with Dusk from a Gherkin formatted file.
In someways Behat but built around Dusk and how it goes about making the underlaying
framework and testing work seemlessly.

For example you can make a feature in `tests/features` folder name `tests/features/profile.feature`


In that file you might have

```
Feature: Test Profile Page
  Can See and Edit my profile
  As a user of the system
  So I can manage my profile

@happy_path
Scenario: Edit Profile
  Given I have a profile created
  And I am in edit mode
  Then I can change the first name
  And the last name
  And and save my settings
  Then when I view my profile it will have those new settings

Scenario: View Profile
  Given I have a profile created
  And I am in view mode
  Then I can see the first name
  And the last name
  And and go into edit mode
  Then when I will be at the edit form
```


Then by running `pickle initialize --unit tests/features/profile.feature` you will have a new Dusk file

`tests/Unit/ProfileTest.php`

It will look like this

```
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}

```

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practises by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```




## Install

Via Composer

``` bash
$ composer require alnutile/dg
```

## Usage

``` php
$skeleton = new alnutile\dg();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email me@alfrednutile.info instead of using the issue tracker.

## Credits

- [Alfred Nutile][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/alnutile/dg.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alnutile/dg/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/alnutile/dg.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/alnutile/dg.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alnutile/dg.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/alnutile/dg
[link-travis]: https://travis-ci.org/alnutile/dg
[link-scrutinizer]: https://scrutinizer-ci.com/g/alnutile/dg/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/alnutile/dg
[link-downloads]: https://packagist.org/packages/alnutile/dg
[link-author]: https://github.com/alnutile
[link-contributors]: ../../contributors
