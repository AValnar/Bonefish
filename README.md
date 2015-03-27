[ WIP - Work In Progress ] Bonefish
========

Bonefish Framework

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/?branch=master)  [![Build Status](https://scrutinizer-ci.com/g/AValnar/Bonefish/badges/build.png?b=master)](https://scrutinizer-ci.com/g/AValnar/Bonefish/build-status/master)

Bonefish is a dead simple near zero configuration php framework.
This one has a lot of similarties to TYPO3\Flow but aims to be easier to use and setup as well as a smaller overhead.

Features
========
- Autoload classes with PSR-4 standard
- CLI enviorment: Raptor : Call commandoControllers and execute commands like TYPO3\Flow. Includes by default: package kickstarter, routes generator and hello world example
- Extended Nette\Latte Templating with Viewhelpers
- YAML like Configurations via Nette\Neon
- THE BEST Debugger you will ever use: Nette\Tracy you will see why ;)
- Composer packages with type "bonefish-package" are automatically installed in /Packages for clean managment
- Dead simple Router with support for RESTful APIs
- Easy ORM
- Easy Dependency Injection with Bonefish DI, Annotation Injections just like Flow
- ACL support to restrict ControllerActions from spezialized profiles
- and more!

Installation
============
This Package is automatically tested with PHP 5.4, 5.5, 5.6, 7.0 and hhvm.

To get it to work clone this repository and use composer install.
```shell
$ git clone https://github.com/AValnar/Bonefish.git
$ cd Bonefish
$ composer install
$ php bonefish-cli execute Bonefish Generator generate
```

Usage
=====
If you followed all the installtion steps you are officailly running Bonefish!
Call your website and look at that marvelous "hello World" print.
But jokes aside if you need more in-depth help check out the wiki.

Cheers!
