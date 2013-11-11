##ThinFrame AOP

It is a AOP extension for Symfony2 Dependency Injection Container

##AOP ?
It stands for Aspect-Oriented Programming. Check <http://en.wikipedia.org/wiki/Aspect-oriented_programming>

##What does it do ?

Basically, it allows you to add before/after hooks to methods without changing/affecting your existing code.

##How ?

Magic ? Not really ...

#####Install it: `composer require thinframe/aop`

#####Update it: `composer update`

#####Use it:

1. Add the `thinframe.aop` tag to the desired service in di container.
2. Use `AopCompilerPass`
3. Expect for `thinframe.aop.before` and `thinframe.aop.after` events.

or just use the `AopApplication`

##Copyright

* MIT License - Sorin Badea <sorin.badea91@gmail.com>