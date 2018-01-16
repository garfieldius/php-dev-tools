# PHP Development Tools

This package contains a set of helpers I use frequently on PHP projects.

## Installation

Install it with `composer install --dev grossberger-georg/php-dev-tools`

## Components

There are currently two helper sets in this package:

#### Unit tests

The class `AbstractTestCase` provides two helper methods for creating unit tests:

1. `makeMock` creates a mock object of the given class without calling the constructor
2. `inject` can be used to set values of non-public properties of an object, so configuring a dependency injection service for every test is not necessary

The class `TYPO3TestCase` extends `AbstractTestCase` and contains the static method `setupBeforeClass` which defines several constants and properties used of TYPO3 functions. By using it as the parent for test cases, most TYPO3 methods can be called without setting up an entire TYPO3 context.

#### Code style

There are three fixers for [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

1. `GrossbergerGeorg/lower_header_comment` works like the default HeaderComment fixer, except it puts the header after the namespace declaration.
    It also does not update the header, if it is the same, but with a different year, given the template contains the marker `__YEAR__` instead of an actual year. This is useful for not updating every file in a project after every new years eve.
2. `GrossbergerGeorg/namespace_first` ensures that the namespace declaration is the first statement in a PHP file, even before any comment.
3. `GrossbergerGeorg/single_empty_line` ensures there are only single empty lines between statements.

## License

Released under Apache License 2.0, please see the file [LICENSE](LICENSE) of this package or <https://www.apache.org/licenses/LICENSE-2.0> for details.
