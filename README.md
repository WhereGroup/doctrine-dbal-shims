NOTE: [older versions](https://github.com/WhereGroup/doctrine-dbal-shims/tree/v1.0.1) contained compatibility workarounds for
PostgreSQL >= 10 on unmaintained DBAL (<=2.6.x). This support has been removed. Only the Oracle
session init logic remains.

## Oracle session variable preinitialization
Included is an event subscriber that automatically sets Oracle session variables required by DBAL and Doctrine ORM to
work properly (date formats etc).

Unlike the DBAL default implementation, this can be added globally, and will check / only
act on Oracle DBAL connections. All other connection types will be completely left alone.

This makes it easier to use in mixed multi-connection setups, and indeed safe to use with
zero Oracle connections.

## Usage
With `doctrine/dbal-bundle` installed, you can register the included `AddOracleSessionInitPass`
into your Symfony container build, or use the PassIndex to do it for you.

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
Without Symfony and the Doctrine bundle, compiler passes won't work.

The OnDemandOracleSessionInit subscriber instance needs to be added to the connection's event manager in
some other way to do anything.
