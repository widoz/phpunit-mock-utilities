# PhpUnit Mock Utilities

![Continuous Integration](https://github.com/widoz/phpunit-mock-utilities/workflows/Continuous%20Integration/badge.svg)

A set of utilities to better mock objects and generic data for tests double.

## Why

I do not needed to have different mocking tools to be honest and for me the PhpUnit mock built in library works quite good.
What's annoying me most is the repetitive actions taken in order to obtain a configured mock.

I do not want to worry about if a class is abstract or it's an interface, I do not want to worry about configure methods when I need
a stub instead of a mock.

I also like sometimes to test inner methods such as `protected` or `private` methods and for that reason I need mostly a Proxy.

For that reason I found my self writing again and again the same portions of code in order to obtain a configured mock/stub.

This little utility library has been realized with that intent, make a bit more easier to create a mock/stub without taking care of
unnecessary details when those details are not needed to be known.

This library does not have the presuntion not the goal to be another mocking library for your unit tests, if you need something which
boost PhpUnit mock feature there are a lot of libraries out there :)

## Features

### Mock

### Proxy

### Fake Values

## Note

This library does require an esplicit dependency for phpunit therefore it's up to you require via composer the phpunit version you want to depends on.

## Requirements

- PHP >= 7.3
- PhpUnit >= 9

## License

This library is released under [MIT](LICENSE) license
