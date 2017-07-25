# Pickle

[![Build Status](https://travis-ci.org/alnutile/pickle.svg?branch=master)](https://travis-ci.org/alnutile/pickle)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


<a name="overview"></a>
## Overview

![](https://dl.dropboxusercontent.com/s/7ek8r3477lljab4/pickle.jpg?dl=0)

Converting Gherkin file to PHPUnit Compatible and Dusk Compatible files.

This will attempt to make an easy way to work with Dusk and PHPUnit from a Gherkin formatted file.


If you are familiar with Behat then this workflow might be similar.

**[Intro Video](https://youtu.be/GmA-6hnhljI)**

**[Intro Slides](https://goo.gl/djVXhJ)**


## Topics

  * [UI Example](#ui)
  * [Running](#running)
  * [RoadMap](#roadmap)
  * [Install](#install)
  * [Testing](#testing)
  * [Contributing](#contributing)
  * [Security](#security)
  * [Credits](#credits)
  * [License](#license)

### Initialize 

In this example I have written a feature <sup><a href="#feature">1</a></sup> file and now I want to turn that into my first PHPUnit compatible test.

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

One of the key aspects I will talk about later is how I can use one file to drive two types of tests, Integration then Browser. And how the Gherkin syntax can influence how I name my classes in line with the business writing of the feature <sup><a href="#bdd">2</a></sup>.

![](https://dl.dropboxusercontent.com/s/44lfxdd2wxrorcv/one_file.png?dl=0)


>NOTE: the above feature does not really summarize the goal in business terms it is still quite a bit focused on the web.


So at this point I can type, assuming you installed Pickle globally as I cover below:

```
pickle initialize tests/features/profile.feature 
```

or for a Browser test:

```
pickle initialize --context=browser tests/features/profile.feature
```

In this case let's focus on domain context eg Integration.

Now it will make a test for me in `tests/Feature/ProfileTest.php`

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

Now this is just icing on the cake and you can just default back to the basics
and it will all still work.

```
pickle run tests/features/profile.feature 
```

Or just go back to using PHPUnit

```
phpunit tests/Feature/ProfileTest.php
```

Or Dusk via Pickle

```
pickle run --context=browser tests/features/profile.feature 
```

Or via Dusk

```
php artisan dusk tests/Browser/ProfileTest.php
```

<a name="ui"></a>
### UI Example



After I am done with my Integration tests and all that work is in place I can start on the Browser tests.

So I run the command 

```
pickle run --context=browser tests/features/profile.feature 
```

And I get 


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
            //etc...
            //etc...
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

By setting the `$this->browser` as global I can work one step or function at a time:

  * visit the page
  * enter a form field 
  * enter another form field
  * submit
  * look for results 
  
  
### Appending Tests

As here is an example of when you need to add new Scenarios to a feature file and you need to then update the Class file.

For example

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

  @new_scenario
  Scenario: View Profile
    Given I have a profile created
    And I am in view mode
    Then I can see the first name
    And the last name
```

We now need to append more to it so we run

`pickle append tests/features/test_profile.feature`

or

`pickle append --context=browser tests/features/test_profile.feature`

This will add the new Scenario and methods making sure not to duplicate Scenarios.

🤖🤖**WARNING**🤖🤖

This will not update existing Scenarios

For example you modify this Scenario

```
  Scenario: Edit Profile
    Given I have a profile created
    And I am in edit mode
    Then I can change the first name
    And the last name
    And and save my settings
    Then when I view my profile it will have those new settings
```

to

```
  Scenario: Edit Profile
    Given I have a profile created
    And I am in edit mode
    Then I can change the first name
    And the last name
    And I can add my photo
    And and save my settings
    Then when I view my profile it will have those new settings
```

So the new line `And I can add my photo` will show up as a method

```
protected function andICanAddMyPhoto() {
   $this->markIncomplete("Time to Code");
 }
```

**but** it will not add it to the `Scenario` step as seen after initialize as 

```
    public function testEditProfile()
    {
        $this->browse(function (Browser $browser) {
            $this->browser = $browser;
            $this->foo();
            $this->bar();
        });
    }
```

You just need to add it, to the correct ordered location before or after

```
$this->foo();
$this->bar();
$this->andICanAddMyPhoto();
```


  
<a name="roadmap"></a>
## RoadMap

### Initialize (DONE)

The ability to use a Gherkin file to create a Unit or Browser test

#### Todo

Move the work into `pickle` cli file see at root of app. Simple stuff since it is working in the test

I will take that one asap

Right now the test show it working now I need to add it to the global command


### Append Snippets (DONE)

The ability to add more steps and scenarios to existing Unit and Browser tests

So if they add new steps or scenarios to the feature pickle is smart enough to append the scenario(s)
(easy stuff there) but also append steps into the scenario as needed.

#### Todo

Everything! I mean I have code to prevent duplicate methods.

### Run from Gherkin (DONE)

Running from the Gherkin test either the domain or ui test with nice Gherkin based output

eg

```
pickle run tests/features/profile.feature --domain
```

And it would know to use the `tests/Feature/ProfileTest.php`

and output in that nice Gherkin format like Behat.


#### Todo


I will track these as Issues [here](https://github.com/alnutile/pickle/issues)

And as much as possible / soon in Projects [here](https://github.com/alnutile/pickle/projects/1)




<a name="install"></a>
## Install

First you need to install the Global Composer Tool to help over all [learn more](https://packagist.org/packages/consolidation/cgr)


```
composer global require consolidation/cgr
```

Then make sure that `~/.composer/vendor/bin` to your $PATH`

You might have this already

eg edit your ~/.bash_profile` and add


```
export PATH=~/.composer/vendor/bin:$PATH
```

Then type

```
source ~/.bash_profile
```

Now if you type
```
cgr --help
```

You should get some output like this

```
The 'cgr' tool is a "safer" alternative to 'composer global require'.
Installing projects with cgr helps avoid dependency conflicts between
different tools.  Use 'cgr' wherever 'composer global require' is recommended.

.....
.....
.....

```

**Now for Pickle**

```
cgr alnutile/pickle:*
```

now you should be able to run from any location on your Mac

```
pickle help
```


and to upgrade often since it is a busy project


```
cgr update alnutile/pickle
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


## Footnotes

<a name="feature">1</a> Feature files are written in Gherkin. You can see some examples [here](https://github.com/alnutile/recipes/blob/master/features/profile/home.feature)

The "Feature" area gives the feature a title then sense

  * What is being built
  * Who it is being built for
  * What is the business result
  
Then then leads into the "Scenarios"


<a name="bdd">2</a>

You can read more about BDD [here](http://stakeholderwhisperer.com/posts/2014/10/introducing-modelling-by-example) 
 


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
