#ZF1 Boilerplate App (work in progress)
[![Build Status](https://travis-ci.org/jkobus/zf1-boilerplate.png?branch=master)](https://travis-ci.org/jkobus/zf1-boilerplate)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/jkobus/zf1-boilerplate/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
[![Latest Stable Version](https://poser.pugx.org/skajdo/zf1-boilerplate/v/stable.png)](https://packagist.org/packages/skajdo/zf1-boilerplate)
[![Dependencies Status](https://d2xishtp1ojlk0.cloudfront.net/d/13387588)](http://depending.in/jkobus/zf1-boilerplate)

This application is intended to be used in small or medium sized projects.

###Features

- works with PHP 5.3.26, 5.4, 5.5
- easy to deploy
- has its own **DI** container based on [Pimple](http://pimple.sensiolabs.org/) (very easy to use and fast)
- you have your own controller and view implementations; you can do whatever you want
- DI container is fully compatible with code completion functions in your IDE (PHPStorm tested)
- no more bootstrapping horror - plain old PHP will do !
- code completion for helpers and view methods - see instructions inside views !
- modular structure
- easy testing (see ./tests) with code coverage
- ability to create and register **service providers** to reuse them in other applications using this boilerplate

###Installation

Install [Composer](http://getcomposer.org/) (if you don't have it yet, get it. You'll make your life easier, trust me.)

Run:

    composer create-project -s dev skajdo/zf1-boilerplate ./app-name-here

Running tests:

on Linux:

    // in your project directory type:
    ./vendor/bin/phpunit -c ./tests/config.xml ./tests/fixtures

on Windows:

    // open cmd in your project directory and type:
    test
    // or, to display phpunit help:
    phpunit

Now get to work !

###Contributing

All code contributions must go through a pull request.
Fork the project, create a feature branch, and send me a pull request.
To ensure a consistent code base, you should make sure the code follows
the [coding standards](http://symfony.com/doc/2.0/contributing/code/standards.html).
If you would like to help take a look at the [list of issues](https://github.com/jkobus/zend-for-facebook/issues) or issue a pull request with a cool feature or whatever you think is needed.

###Requirements

See **composer.json** for a full list of dependencies.

###Authors

Jacek Kobus <j.kobus@hypermediaisobar.com>

###License information

    See the file LICENSE.txt for copying permission.