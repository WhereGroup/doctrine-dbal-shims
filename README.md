Shim for [Doctrine DBAL](https://github.com/doctrine/dbal) to extend database server support on old PHP versions just
enough so that common runtime errors go away.

The Doctrine DBAL project will perform no further maintenance on older release lines compatible with PHP 5, which
is a completely agreeable stance. The onus of work should lie with those still requiring support for obsolete
versions.

## PostgreSQL 10 schema update support
Included is a slightly extended PostgreSQL DBAL driver, with the sole purpose of returning a slightly extended schema
manager on PostgreSQL server >= 10. This fixes the `SQLSTATE[42703]: Undefined column: 7 ERROR: column "min_value" does not exist`
error commonly observed when attempting to run a `doctrine:schema:update` command.

See [(rejected) DBAL PR#3587](https://github.com/doctrine/dbal/pull/3587) for further context. 

[Doctrine DBAL 2.7.0](https://github.com/doctrine/dbal/releases/tag/v2.7.0) added full support for PostgreSQL server 10,
and in a much more complete and robust fashion. For this reason, if this shim is installed alongside DBAL >= 2.7, it
auto-disables its replacement logic and lets upstream DBAL take over completely.

## Oracle session variable preinitialization
Included is an event subscriber that automatically sets Oracle session variables required by DBAL and Doctrine ORM to
work properly (date formats etc).

Unlike the DBAL default implementation, this event subscriber does not need additional configuration to limit itself to
certain named connections, in the presence of non-Oracle DBAL connections. This makes it easier to use in mixed
multi-connection setups.

## Dependencies
We only require `doctrine/dbal:^2` and PHP >= 5.3.

ShimmedConnectionFactory and PassIndex can only reasonably be used on top of the suggested [doctrine/doctrine-bundle](https://packagist.org/packages/doctrine/doctrine-bundle).

## Usage
Even with `doctrine/dbal-bundle` installed, shim registration is in no way automatic. You will need to `register` individual
compiler passes, or call autoRegisterAll on the PassIndex, in the build method of either the kernel or a bundle of your choosing.

```php
# Bundle class
public function build(ContainerBuilder $container)
{
    <...>
    PassIndex::autoRegisterAll($container);
    <...>
}
```
```php
# Kernel class
public function buildContainer()
{
    $container = parent::buildContainer();
    <...>
    PassIndex::autoRegisterAll($container);
    <...>
    return $container;
}
```

### Standalone DBAL
Direct usages of DBAL, i.e. bypassing or completely without Symfony integration, can not rely on the provided container registration
and compiler passes.

To use the PostgreSQL driver extension, you would have to inject the appropriate `driverClass` option by some other means (connection
configuration or code).

The OnDemandOracleSessionInit subscriber instance needs to be added to the connection's event manager to come into effect.

## Other considerations
We have opted to use runtime DBAL version detection over code-exclusionary composer conflict rules due to
* unclear semantic versioning future of this package in the presence of conflict rule sets
* other potential shimming candidates (Oracle, Sqlite) with very different base DBAL version interactions
