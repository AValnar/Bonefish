Bonefish
========

Bonefish Framework

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/?branch=master)  [![Build Status](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/build.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/build-status/master)

Bonefish is a dead simple near zero configuration php framework.

Features
========
- Autoload classes with PSR-4 standard
- Commandline Tool
- Package Kickstarter
- Templating Engine provided by Nette\Latte
- Simple .neon configurations by Nette\Neon
- Nette\Tracy Debugger
- Composer packages with type "bonefish-package" are automatically installed in /Packages *
- Dead simple Router
- Easy ORM with Nette\Database & Uestla/YetORM
- Easy Viewhelper/Macro Support for Nette\Latte
- Easy Dependency Injection with Bonefish DI
- Clean Package System

\* Note: This is only working once https://github.com/composer/installers/pull/181 is merged

Installation
============
This Package is automatically tested with PHP 5.4, 5.5 and 5.6 but also works with PHP 5.3

Please use Composer to install this package.
```shell
$ composer require av/bonefish-bonefish:dev-master
```
\* Note: This is only needed if https://github.com/composer/installers/pull/181 is not yet merged
Overwrite your composer/installer with the above fork please to enable proper Package installation

Create a cache directory and a cache directory inside this previously created one.
Afterwards set the path in /Configuration/Configuration.neon
lattePath is relative to cachePath

Usage
=====
Actually it is already working.
Don't believe me ?
Just call your index.php and you should already see "Hello World"

Anyway if you want to do a little more than to display "Hello World" check out the wiki.
