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

Anyway if you want to do a little more than to display "Hello World" you should do the following:

Add Packages
============
When you add a new Package add the configuration inside /Configuration/Packages.neon
```yaml
vendor:
  package:
    #path: otherpath/otherdir # only needed if it is not /Packages/vendor/package
    active: yes
```

Routing
=======
To call specific Packages or actions your urls should look as follows:
```php
example.com // calls the indexAction of the default package set in /Configuration/Configuration.neon with vendor and package
example.com/v:foo // call the index action of the vendor foo with the default package
example.com/v:foo/p:bar // calls the indexAction of the Vendor foo in the Package bar
example.com/v:foo/p:bar/a:baz // calls the bazAction of the Vendor foo in the Package bar
example.com/test:foo // would call the indexAction on the default package and the parameter test will have the value foo
// All keys and parameters can be in any order, Bonefish will sort them for you
```

Templating
==========
You can either use your own Template Engine or whatever suits you, but you can also use the power of Latte.
Just create a directory called "Layouts" in your Package and put in a Default.latte file.
The Syntax is same as HTML but you can use macros like {bonefish.base} which would generate a <base> tag for you.
Check out the Latte Documentation for more information on macros and functions inside Latte.

To render this View just call $this->view->render() in your Action.
To assign variables use $this->view->assign($name,$value);
If you have custom Viewhelpers create them using the Bonefish Container and load them using $this->view->addMacro($viewhelper);

Dependency Injection
====================
If you need any dependency in your classes all you need is to define a public variable with a @var and @inject annotation. The @var one has to supply the fully qualified namespace.
```php
class Foo {

  /**
   * @var \some\Class
   * @inject
   */
  public $foo;
}
```

ORM
===
To use the ORM just extend either \Bonefish\ORM\Model or \Bonefish\ORM\Repository.
For more Information on the Usage of YetORM just check out the Documentation at https://github.com/uestla/YetORM

DISCLAIMER: Watch out automatic dependency injection with @inject is NOT performed on Models.
