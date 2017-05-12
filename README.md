# Pickle

[![Build Status](https://travis-ci.org/alnutile/pickle.svg?branch=master)](https://travis-ci.org/alnutile/pickle)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Topics

  * [Overview](#overview)
  * [UI Example](#ui)
  * [RoadMap](#roadmap)
  * [Install](#install)
  * [Testing](#testing)
  * [Contributing](#contributing)
  * [Security](#security)
  * [Credits](#credits)
  * [License](#license)

<a name="overview"></a>
## Overview

Gherkin to Dusk / PHPUnit

This will attempt to make a easy way to work with Dusk and PHPUnit from a Gherkin formatted file.
In someways Behat but built around Dusk and PHPUnit and how it goes about making the underlying
framework and testing work seamlessly.


### Initialize
For example I make a file `tests/feature/profile.feature`

```
Feature: Test Profile Page
  Can See and Edit my profile
  As a user of the system
  So I can manage my profile

  Scenario: Edit Profile
    Given I have a profile created
    And I am in edit mode
    Then I can change the first name
    And the last name
    And and save my settings
    Then when I view my profile it will have those new settings
```

So at this point I can type

```
vendor/bin/pickle initialize tests/features/profile.feature  --unit
```

or 

```
vendor/bin/pickle initialize tests/features/profile.feature  --browser
```

In this case let's focus on domain.

Now it will make a test for me in `tests/Unit/ProfileTest.php`

and I can start working on that file which would look something like this

```
<?php

class ProfileTest extends TestCase {

    /**
      * @group editProfile
      */
    public function testGivenIHaveAProfileCreated() {
        $this->givenIHaveAProfileCreated();
        $this->andIamInEditMode();
        //etc etc
    }

    protected function andIamInEditMode() {
        $this->markTestIncomplete('Time to code');
    }

    protected function andIamInEditMode() {
        $this->markTestIncomplete('Time to code');
    }
    
    //and all the rest

}

```

### Running

Now this is just icing on the cake and not ready yet BUT you can just default back to the basics
and it will all still work.

```
# Coming soon...
vendor/bin/pickle run --domain tests/features/profile.feature 
```

Or now using just go back to using PHPUnit

```
phpunit tests/Unit/ProfileTest.php
```

Or Dusk via Pickle

```
# Comming less soon...
vendor/bin/pickle run --ui tests/features/profile.feature 
```

Or via Dusk

```
php artisan dusk tests/Browser/ProfileTest.php
```

<a name="ui"></a>
### UI Example

Here is an example of what a Dusk UI test will then look like

```
<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $this->browser = $browser;
            $this->visitHome();
            $this->seeSomething();
        });
    }

    private function visitHome()
    {
        $this->browser->visit('/');
    }

    private function seeSomething()
    {
        $this->browser->assertSee('Laravel');
    }
}

```

Setting the Browser as global set we can work one step at a time 

  * visit the page
  * enter a form field 
  * enter another form field
  * submit
  * look for results 
  
<a name="roadmap"></a>
## RoadMap

Step 1) Initialize (PENDING)

The ability to use a Gherkin file to create a Unit or Browser test

### Todo

Move the work into `pickle` 

Right now the test show it working now I need to add it to the global command


Step 2) Append Snippets (in progress)

The ability to add more steps and scenarios to existing Unit and Browser tests

### Todo

Everything! 

Step 3) Run from Gherkin

Running from the Gherkin test either the domain or ui test with nice Gherkin based output

### Todo

Everything! 

Step 4) Finalize Docs around this global command

  * Intro Video explaining what and why
  * Good Docs


<a name="install"></a>
## Install

Via Composer

``` bash
$ composer require anutile/pickle
```


<a name="testing"></a>
## Testing

``` bash
$ composer test
```
<a name="contributing"></a>
## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

<a name="security"></a>
## Security

If you discover any security related issues, please email me@alfrednutile.info instead of using the issue tracker.

<a name="credits"></a>
## Credits

- [Alfred Nutile][link-author]
- [All Contributors][link-contributors]

<a name="license"></a>
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/alnutile/pickle.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alnutile/pickle/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/alnutile/pickle.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/alnutile/pickle.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alnutile/pickle.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/alnutile/pickle
[link-travis]: https://travis-ci.org/alnutile/pickle
[link-scrutinizer]: https://scrutinizer-ci.com/g/alnutile/pickle/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/alnutile/pickle
[link-downloads]: https://packagist.org/packages/alnutile/pickle
[link-author]: https://github.com/alnutile
[link-contributors]: ../../contributors
