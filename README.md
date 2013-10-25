##ZF1 Boilerplate App

This application is intended to be used in small or medium sized projects.

###Features

- easy to deploy
- has its own DI container based on [Pimple](http://pimple.sensiolabs.org/) (very easy to use and fast)
- you have your own controller and view implementations; you can do whatever you want
- DI container is fully compatible with code completion functions in your IDE (PHPStorm tested)
- no more bootstrapping horror - plain old PHP will do !
- code completion for helpers and view methods - see instructions inside views !

###Installation

Install [Composer](http://getcomposer.org/) (if you don't have it yet, get it. You'll make your life easier, trust me.)

Run:

    composer create-project -s dev skajdo/zf1-boilerplate ./app-name-here

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

Jacek Kobus - <kobus.jacek@gmail.com>

###License information

    See the file LICENSE.txt for copying permission.
